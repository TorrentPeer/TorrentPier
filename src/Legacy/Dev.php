<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2023 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Legacy;

use Bugsnag\Client;
use Bugsnag\Handler;

use Exception;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Logger;

use Whoops\Handler\PlainTextHandler;
use Monolog\Handler\StreamHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

use TorrentPier\Legacy\Common\Http;

/**
 * Class Dev
 * @package TorrentPier\Legacy
 */
class Dev
{
  /**
   * Dev constructor.
   *
   * @throws Exception
   */
  public function __construct()
  {
    global $bb_cfg;

    /**
     * No show errors by default
     */
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);

    /**
     * Bugsnag initialize
     */
    if ($bb_cfg['bugsnag']['enabled']) {
      $bugsnag = Client::make($bb_cfg['bugsnag']['api_key']);
      Handler::register($bugsnag);
    }

    if (DBG_USER) {
      /**
       * Show all errors
       */
      ini_set('display_errors', 1);
      ini_set('display_startup_errors', 1);

      /**
       * Variables
       */
      $loggerName = $bb_cfg['tp_name'];
      $loggerFile = WHOOPS_LOG_FILE;

      /**
       * Whoops initialize
       */
      $whoops = new Run();

      /**
       * Show errors on page
       */
      $whoops->pushHandler(new PrettyPageHandler());

      /**
       * Show log in browser console
       */
      $loggingInConsole = new PlainTextHandler();
      $loggingInConsole->loggerOnly(true);
      $loggingInConsole->setLogger((new Logger($loggerName, [(new BrowserConsoleHandler())->setFormatter((new LineFormatter(null, null, true)))])));
      $whoops->pushHandler($loggingInConsole);

      /**
       * Log errors in file
       */
      if (ini_get('log_errors') == 1) {
        $loggingInFile = new PlainTextHandler();
        $loggingInFile->loggerOnly(true);
        $loggingInFile->setLogger((new Logger($loggerName, [(new StreamHandler(Logging::bb_log('', $loggerFile, true)))->setFormatter((new LineFormatter(null, null, true)))])));
        $whoops->pushHandler($loggingInFile);
      }

      /**
       * Finally startup
       */
      $whoops->register();
    }
  }

  /**
   * Show error message
   *
   * @param $message
   * @throws Exception
   */
  public static function error_message($message)
  {
    global $bb_cfg;

    if (DBG_USER) {
      throw new Exception($message);
    } else {
      header('Content-Type: text/plain; charset=' . $bb_cfg['charset']);
      Http::setCode(500);

      return die($message);
    }
  }

  /**
   * Get SQL debug log
   *
   * @return string
   */
  public static function get_sql_log(): string
  {
    global $DBS, $CACHES, $datastore;

    $log = '';

    foreach ($DBS->srv as $srv_name => $db_obj) {
      $log .= !empty($db_obj) ? self::get_sql_log_html($db_obj, "pdo: [$srv_name]:") : '';
    }

    foreach ($CACHES->obj as $cache_name => $cache_obj) {
      if (!empty($cache_obj->db)) {
        $log .= self::get_sql_log_html($cache_obj->db, "cache: $cache_name [{$cache_obj->db->engine}]:");
      } elseif (!empty($cache_obj->engine)) {
        $log .= self::get_sql_log_html($cache_obj, "cache: $cache_name [{$cache_obj->engine}]:");
      }
    }

    if (!empty($datastore->db->dbg)) {
      $log .= self::get_sql_log_html($datastore->db, 'cache: datastore [' . $datastore->engine . ']:');
    } elseif (!empty($datastore->dbg)) {
      $log .= self::get_sql_log_html($datastore, 'cache: datastore [' . $datastore->engine . ']:');
    }

    return $log;
  }

  /**
   * Get SQL query html log
   *
   * @param object $db_obj
   * @param string $log_name
   *
   * @return string
   */
  private static function get_sql_log_html($db_obj, $log_name): string
  {
    $db_obj->dbg ? $log = '<div class="sqlLogTitle">' . $log_name . '</div>' : $log = '';

    foreach ($db_obj->dbg as $i => $dbg) {
      $id = "sql_{$i}_" . \TorrentPier\Legacy\Crypt::get_hash_number();
      $sql = Dev::short_query($dbg['sql'], true);
      $time = sprintf('%.4f', $dbg['time']);
      $perc = @sprintf('[%2d]', $dbg['time'] * 100 / $db_obj->sql_timetotal);
      $info = !empty($dbg['info']) ? $dbg['info'] . ' [' . $dbg['src'] . ']' : $dbg['src'];

      $log .= ''
        . '<div onmouseout="$(this).removeClass(\'sqlHover\');" onmouseover="$(this).addClass(\'sqlHover\');" onclick="$(this).toggleClass(\'sqlHighlight\');" class="sqlLogRow" title="' . $info . '">'
        . '<span style="letter-spacing: -1px;">' . $time . ' </span>'
        . '<span class="copyElement" title="Copy to clipboard" data-clipboard-target="#' . $id . '" style="color: gray; letter-spacing: -1px;">' . $perc . '</span>'
        . ' '
        . '<span style="letter-spacing: 0;" id="' . $id . '">' . $sql . '</span>'
        . '<span style="color: gray"> # ' . $info . ' </span>'
        . '</div>'
        . "\n";
    }

    return $log;
  }

  /**
   * Return current DBG status
   *
   * @return bool
   */
  public static function sql_dbg_enabled(): bool
  {
    return (SQL_DEBUG && DBG_USER && !empty($_COOKIE['sql_log']));
  }

  /**
   * Cuts the long query
   *
   * @param $sql
   * @param bool $esc_html
   * @return string
   */
  public static function short_query($sql, bool $esc_html = false): string
  {
    $max_len = 100;
    $sql = str_compact($sql);

    if (!empty($_COOKIE['sql_log_full'])) {
      if (mb_strlen($sql, 'UTF-8') > $max_len) {
        $sql = mb_substr($sql, 0, 50) . ' [...cut...] ' . mb_substr($sql, -50);
      }
    }

    return $esc_html ? htmlCHR($sql, true) : $sql;
  }
}
