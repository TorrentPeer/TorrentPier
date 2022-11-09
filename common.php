<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2018 TorrentPier (https://torrentpier.com)
 * @link      https://github.com/torrentpier/torrentpier for the canonical source repository
 * @license   https://github.com/torrentpier/torrentpier/blob/master/LICENSE MIT License
 */

/**
 * And away we go!...
 */
if (isset($_REQUEST['GLOBALS'])) {
  die;
}

define('TIMESTART', utime());
define('TIMENOW', time());

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
  define('BB_SCRIPT', 'undefined');
}

header('X-Frame-Options: SAMEORIGIN');

// Cloudflare
if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
  $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
}

// Get all constants
require_once __DIR__ . '/library/defines.php';

// Composer
if (!file_exists(BB_PATH . '/vendor/autoload.php')) {
  die('Please <a href="https://getcomposer.org/download/" target="_blank" rel="noreferrer" style="color:#0a25bb;">install composer</a> and run <code style="background:#222;color:#00e01f;padding:2px 6px;border-radius:3px;">composer install</code>');
}
require_once BB_PATH . '/vendor/autoload.php';

// Get config
require_once __DIR__ . '/library/config.php'; // General config
if (file_exists(__DIR__ . '/library/config.local.php')) {
  require_once __DIR__ . '/library/config.local.php'; // Local config
}

// Server
$server_protocol = $bb_cfg['server_secure'] ? 'https://' : 'http://';
$server_port = in_array($bb_cfg['server_port'], [80, 443], true) ? '' : (':' . $bb_cfg['server_port']);
define('FORUM_PATH', $bb_cfg['script_path']);
define('FULL_URL', $server_protocol . $bb_cfg['server_name'] . $server_port . $bb_cfg['script_path']);
unset($server_protocol, $server_port);

// Debug options
define('DBG_USER', isset($_COOKIE[COOKIE_DBG]));

// Board / tracker shared constants and functions
define('BB_BT_TORRENTS', 'bb_bt_torrents');
define('BB_BT_TRACKER', 'bb_bt_tracker');
define('BB_BT_TRACKER_SNAP', 'bb_bt_tracker_snap');
define('BB_BT_USERS', 'bb_bt_users');

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
 * Debug
 */
new \TorrentPier\Legacy\Dev();

/**
 * Init database & cache
 */
$DBS = new TorrentPier\Legacy\Dbs($bb_cfg);
$CACHES = new TorrentPier\Legacy\Caches($bb_cfg);

/**
 * Database
 *
 * @param string $db_alias
 * @return mixed|\TorrentPier\Legacy\SqlDb
 */
function DB($db_alias = 'db')
{
  global $DBS;
  return $DBS->get_db_obj($db_alias);
}

/**
 * Cache
 *
 * @param $cache_name
 * @return mixed|\TorrentPier\Legacy\Cache\APCu|\TorrentPier\Legacy\Cache\File|\TorrentPier\Legacy\Cache\Memcache|\TorrentPier\Legacy\Cache\Redis|\TorrentPier\Legacy\Cache\Sqlite|\TorrentPier\Legacy\Cache\SqliteCommon
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
  case 'memcache':
    $datastore = new TorrentPier\Legacy\Datastore\Memcache($bb_cfg['cache']['memcache'], $bb_cfg['cache']['prefix']);
    break;

  case 'sqlite':
    $default_cfg = [
      'db_file_path' => $bb_cfg['cache']['db_dir'] . 'datastore.sqlite.db',
      'pconnect' => true,
      'con_required' => true,
    ];
    $datastore = new TorrentPier\Legacy\Datastore\Sqlite($default_cfg, $bb_cfg['cache']['prefix']);
    break;

  case 'redis':
    $datastore = new TorrentPier\Legacy\Datastore\Redis($bb_cfg['cache']['redis'], $bb_cfg['cache']['prefix']);
    break;

  case 'apcu':
    $datastore = new TorrentPier\Legacy\Datastore\APCu($bb_cfg['cache']['prefix']);
    break;

  case 'filecache':
  default:
    $datastore = new TorrentPier\Legacy\Datastore\File($bb_cfg['cache']['db_dir'] . 'datastore/', $bb_cfg['cache']['prefix']);
}

/**
 * Board or tracker init
 */
\TorrentPier\Helpers\BaseHelper::system_requirements();

/**
 * @return float|int
 */
function utime()
{
  return array_sum(explode(' ', microtime()));
}

if (!defined('IN_TRACKER')) {
  require INC_DIR . '/init_bb.php';
} else {
  define('DUMMY_PEER', pack('Nn', \TorrentPier\Helpers\IPHelper::encode_ip($_SERVER['REMOTE_ADDR']), !empty($_GET['port']) ? (int)$_GET['port'] : random_int(1000, 65000)));

  /**
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

    \TorrentPier\Legacy\Dev::error_message($output);
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
