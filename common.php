<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

/**
 * And away we go!...
 */
if (isset($_REQUEST['GLOBALS'])) {
  die;
}

define('TIMESTART', utime());
define('TIMENOW', time());

$rootPath = __DIR__;
if (DIRECTORY_SEPARATOR != '/') $rootPath = str_replace(DIRECTORY_SEPARATOR, '/', $rootPath);
define('BB_PATH', $rootPath);

if (empty($_SERVER['REMOTE_ADDR'])) {
  $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
}
if (empty($_SERVER['HTTP_USER_AGENT'])) {
  $_SERVER['HTTP_USER_AGENT'] = '';
}
if (empty($_SERVER['HTTP_REFERER'])) {
  $_SERVER['HTTP_REFERER'] = '';
}
if (empty($_SERVER['SERVER_NAME'])) {
  $_SERVER['SERVER_NAME'] = '';
}

if (!defined('BB_ROOT')) {
  define('BB_ROOT', './');
}
if (!defined('BB_SCRIPT')) {
  define('BB_SCRIPT', '');
}

header('X-Frame-Options: SAMEORIGIN');

// Cloudflare
if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
  $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
}

// Composer
if (!file_exists(BB_PATH . '/vendor/autoload.php')) {
  die('Please <a href="https://getcomposer.org/download/" target="_blank" rel="noreferrer" style="color:#0a25bb;">install composer</a> and run <code style="background:#222;color:#00e01f;padding:2px 6px;border-radius:3px;">composer install</code>');
}
require_once BB_PATH . '/vendor/autoload.php';

// Get all constants
require_once BB_PATH . '/library/defines.php';

// Get config
require_once BB_PATH . '/library/config.php'; // General config
if (file_exists(BB_PATH . LOCAL_CONFIG)) {
  require_once BB_PATH . LOCAL_CONFIG; // Local config
}

/**
 * Debug
 */
define('DBG_USER', isset($_COOKIE[COOKIE_DBG]));
new \TorrentPier\Legacy\Dev();

/**
 * Simple die
 *
 * @param $txt
 * @return void
 * @throws Exception
 */
function bb_simple_die($txt)
{
  \TorrentPier\Legacy\Dev::error_message($txt);
}

/**
 * Deep array
 *
 * @param $var
 * @param $fn
 * @param bool $one_dimensional
 * @param bool $array_only
 */
function array_deep(&$var, $fn, bool $one_dimensional = false, bool $array_only = false)
{
  if (is_array($var)) {
    foreach ($var as $k => $v) {
      if (is_array($v)) {
        if ($one_dimensional) {
          unset($var[$k]);
        } elseif ($array_only) {
          $var[$k] = $fn($v);
        } else {
          array_deep($var[$k], $fn);
        }
      } elseif (!$array_only) {
        $var[$k] = $fn($v);
      }
    }
  } elseif (!$array_only) {
    $var = $fn($var);
  }
}

/**
 * @param string|null $str
 * @return string|null
 */
function str_compact(?string $str): ?string
{
  return preg_replace('#\s+#u', ' ', trim($str));
}

/**
 * System utilities (Sys info)
 *
 * @param $param
 * @return int|string|void
 * @throws \Exception
 */
function sys($param)
{
  switch ($param) {
    case 'la':
      return function_exists('sys_getloadavg') ? implode(' ', sys_getloadavg()) : 0;
      break;
    case 'mem':
      return memory_get_usage();
      break;
    case 'mem_peak':
      return memory_get_peak_usage();
      break;
    default:
      bb_simple_die("Invalid param: $param");
  }
  return;
}

/**
 * Verify ID
 *
 * @param $id
 * @param $length
 * @return bool
 */
function verify_id($id, $length)
{
  return (is_string($id) && preg_match('#^[a-zA-Z0-9]{' . $length . '}$#', $id));
}

/**
 * Скрывает путь BB_PATH
 *
 * @param $path
 * @return string
 */
function hide_bb_path($path): string
{
  return ltrim(str_replace(BB_PATH, '', $path), '/\\');
}

/**
 * Generate a "random" alpha-numeric string.
 *
 * Should not be considered sufficient for cryptography, etc.
 *
 * @param int $length
 * @return string
 */
function make_rand_str($length = 10): string
{
  $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

  return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
}

/**
 * Microtimer
 *
 * @return float|int
 */
function utime()
{
  return array_sum(explode(' ', microtime()));
}

// Server
$server_protocol = $bb_cfg['server_secure'] ? 'https://' : 'http://';
$server_port = in_array($bb_cfg['server_port'], [80, 443]) ? '' : (':' . $bb_cfg['server_port']);
define('FORUM_PATH', $bb_cfg['script_path']);
define('FULL_URL', $server_protocol . $bb_cfg['server_name'] . $server_port . $bb_cfg['script_path']);
unset($server_protocol, $server_port);

// Board / tracker shared constants and functions
define('BB_BT_TORRENTS', 'bb_bt_torrents');
define('BB_BT_TRACKER', 'bb_bt_tracker');
define('BB_BT_TRACKER_SNAP', 'bb_bt_tracker_snap');
define('BB_BT_USERS', 'bb_bt_users');
define('BB_BT_TOR_DL_STAT', 'bb_bt_tor_dl_stat');

define('BB_CACHE', 'bb_cache');

define('BT_AUTH_KEY_LENGTH', 10);

define('PEER_HASH_PREFIX', 'peer_');
define('PEERS_LIST_PREFIX', 'peers_list_');
define('PEER_HASH_EXPIRE', round($bb_cfg['announce_interval'] * (0.85 * $bb_cfg['tracker']['expire_factor']))); // sec
define('PEERS_LIST_EXPIRE', round($bb_cfg['announce_interval'] * 0.7)); // sec

define('DL_STATUS_RELEASER', -1);
define('DL_STATUS_DOWN', 0);
define('DL_STATUS_COMPLETE', 1);
define('DL_STATUS_CANCEL', 3);
define('DL_STATUS_WILL', 4);

define('TOR_TYPE_GOLD', 1);
define('TOR_TYPE_SILVER', 2);

define('GUEST_UID', -1);
define('BOT_UID', -746);

/**
 * Init database & cache
 */
$DBS = new TorrentPier\Legacy\Dbs($bb_cfg);
$CACHES = new TorrentPier\Legacy\Caches($bb_cfg);

/**
 * Database
 *
 * @return mixed|\TorrentPier\Legacy\SqlDb
 * @throws Exception
 */
function DB()
{
  global $DBS;

  return $DBS->get_db_obj();
}

/**
 * Cache
 *
 * @param $cache_name
 * @return mixed|\TorrentPier\Legacy\Cache\APCu|\TorrentPier\Legacy\Cache\File|\TorrentPier\Legacy\Cache\Memcached|\TorrentPier\Legacy\Cache\MySQL|\TorrentPier\Legacy\Cache\PostgreSQL|\TorrentPier\Legacy\Cache\Redis|\TorrentPier\Legacy\Cache\Sqlite
 * @throws \Exception
 */
function CACHE($cache_name)
{
  global $CACHES;

  return $CACHES->get_cache_obj($cache_name);
}

/**
 * Datastore
 */
switch ($bb_cfg['datastore_type']) {
  case 'memcached':
    $datastore = new \TorrentPier\Legacy\Datastore\Memcached($bb_cfg['cache']['memcached'], $bb_cfg['cache']['prefix']);
    break;

  case 'sqlite':
    $datastore = new \TorrentPier\Legacy\Datastore\Sqlite(DB()->pdo, DB()->cfg, $bb_cfg['cache']['prefix']);
    break;

  case 'redis':
    $datastore = new \TorrentPier\Legacy\Datastore\Redis($bb_cfg['cache']['redis'], $bb_cfg['cache']['prefix']);
    break;

  case 'apcu':
    $datastore = new \TorrentPier\Legacy\Datastore\APCu($bb_cfg['cache']['prefix']);
    break;

  case 'postgresql':
    $datastore = new \TorrentPier\Legacy\Datastore\PostgreSQL(DB()->pdo, DB()->cfg, $bb_cfg['cache']['prefix']);
    break;

  case 'mysql':
    $datastore = new \TorrentPier\Legacy\Datastore\MySQL(DB()->pdo, DB()->cfg, $bb_cfg['cache']['prefix']);
    break;

  case 'filecache':
  default:
    $datastore = new \TorrentPier\Legacy\Datastore\File($bb_cfg['cache']['cache_dir'] . 'filecache/datastore/', $bb_cfg['cache']['filecache'], $bb_cfg['cache']['prefix']);
}

/**
 * Board or tracker init
 */
if (CHECK_REQIREMENTS['status'] && !CACHE('bb_cache')->get('system_req')) {
  // [1] Check PHP Version
  if (!\TorrentPier\Helpers\IsHelper::is_php(CHECK_REQIREMENTS['php_min_version'])) {
    bb_simple_die("TorrentPier requires PHP version " . CHECK_REQIREMENTS['php_min_version'] . "+ Your PHP version " . PHP_VERSION);
  }

  // [2] Check installed PHP Extensions on server
  $data = [];
  foreach (CHECK_REQIREMENTS['ext_list'] as $ext) {
    if (!extension_loaded($ext)) {
      $data[] = $ext;
    }
  }
  if (!empty($data)) {
    bb_simple_die(sprintf("TorrentPier requires %s extension(s) installed on server", implode(', ', $data)));
  }

  CACHE('bb_cache')->set('system_req', true);
}

if (!defined('IN_TRACKER')) {
  require INC_DIR . '/init_bb.php';
} else {
  define('DUMMY_PEER', pack('Nn', \TorrentPier\Helpers\IPHelper::encode_ip($_SERVER['REMOTE_ADDR']), !empty($_GET['port']) ? (int)$_GET['port'] : random_int(1000, 65000)));

  /**
   * Dummy exit
   *
   * @param int $interval
   * @throws \Exception
   */
  function dummy_exit($interval = 1800)
  {
    $output = \SandFox\Bencode\Bencode::encode([
      'interval' => (int)$interval,
      'min interval' => (int)$interval,
      'peers' => (string)DUMMY_PEER,
    ]);

    bb_simple_die($output);
  }

  header('Content-Type: text/plain');
  header('Pragma: no-cache');

  if (!defined('IN_ADMIN')) {
    // Exit if tracker is disabled via ON/OFF trigger
    if (file_exists(BB_DISABLED)) {
      dummy_exit(random_int(60, 2400));
    }
  }
}
