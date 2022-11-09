<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Legacy;

use TorrentPier\Helpers\BaseHelper;

/**
 * Class Logging
 * @package TorrentPier\Legacy
 */
class Logging
{
  /**
   * Write log file
   *
   * @param $msg
   * @param $file_name
   * @param false $return_path
   * @return false|int|string
   */
  public static function bb_log($msg, $file_name, $return_path = false)
  {
    if (is_array($msg)) {
      $msg = implode(LOG_LF, $msg);
    }
    $file_name .= (LOG_EXT) ? '.' . LOG_EXT : '';

    $path = (LOG_DIR . '/' . $file_name);
    if ($return_path) {
      return $path;
    }
    return Filesystem::file_write($msg, $path);
  }

  /**
   * Log a get request
   *
   * @param string $file
   * @param false $prepend_str
   */
  public static function log_get($file = '', $prepend_str = false)
  {
    self::log_request($file, $prepend_str, false);
  }

  /**
   * Log a post request
   *
   * @param string $file
   * @param false $prepend_str
   */
  public static function log_post($file = '', $prepend_str = false)
  {
    self::log_request($file, $prepend_str, true);
  }

  /**
   * Logging a HTTP requests
   *
   * @param string $file
   * @param false $prepend_str
   * @param bool $add_post
   */
  public static function log_request($file = '', $prepend_str = false, $add_post = true)
  {
    global $user;

    $file = $file ?: 'req/' . date('m-d');
    $str = [];
    $str[] = date('m-d H:i:s');
    if ($prepend_str !== false) {
      $str[] = $prepend_str;
    }
    if (!empty($user->data)) {
      $str[] = $user->id . "\t" . html_entity_decode($user->name);
    }
    $str[] = sprintf('%-15s', $_SERVER['REMOTE_ADDR']);

    if (isset($_SERVER['REQUEST_URI'])) {
      $str[] = $_SERVER['REQUEST_URI'];
    }
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
      $str[] = $_SERVER['HTTP_USER_AGENT'];
    }
    if (isset($_SERVER['HTTP_REFERER'])) {
      $str[] = $_SERVER['HTTP_REFERER'];
    }

    if (!empty($_POST) && $add_post) {
      $str[] = "post: " . BaseHelper::str_compact(urldecode(http_build_query($_POST)));
    }
    $str = implode("\t", $str) . "\n";
    self::bb_log($str, $file);
  }
}
