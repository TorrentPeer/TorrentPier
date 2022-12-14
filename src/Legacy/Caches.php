<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2023 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Legacy;

use Exception;
use TorrentPier\Legacy\Cache\APCu;
use TorrentPier\Legacy\Cache\File;
use TorrentPier\Legacy\Cache\Memcached;
use TorrentPier\Legacy\Cache\MySQL;
use TorrentPier\Legacy\Cache\PostgreSQL;
use TorrentPier\Legacy\Cache\Redis;
use TorrentPier\Legacy\Cache\Sqlite;

/**
 * Class Caches
 * @package TorrentPier\Legacy
 */
class Caches
{
  public $cfg = []; // конфиг
  public $obj = []; // кеш-объекты
  public $ref = []; // ссылки на $obj (имя_кеша => кеш_объект)

  /**
   * Caches constructor.
   *
   * @param $cfg
   */
  public function __construct($cfg)
  {
    $this->cfg = $cfg['cache'];
    $this->obj['__stub'] = new Cache\Common();
  }

  /**
   * Get a cache object by cache name
   *
   * @param $cache_name
   * @return mixed|APCu|File|Memcached|MySQL|PostgreSQL|Redis|Sqlite
   * @throws Exception
   */
  public function get_cache_obj($cache_name)
  {
    if (!isset($this->ref[$cache_name])) {
      if (!$engine_cfg =& $this->cfg['engines'][$cache_name]) {
        $this->ref[$cache_name] =& $this->obj['__stub'];
      } else {
        $cache_type =& $engine_cfg;

        switch ($cache_type) {
          case 'memcached':
            if (!isset($this->obj[$cache_name])) {
              $this->obj[$cache_name] = new Cache\Memcached($this->cfg['memcached'], $this->cfg['prefix']);
            }
            $this->ref[$cache_name] =& $this->obj[$cache_name];
            break;

          case 'sqlite':
            if (!isset($this->obj[$cache_name])) {
              $this->obj[$cache_name] = new Cache\Sqlite(DB()->pdo, DB()->cfg, $this->cfg['prefix']);
            }
            $this->ref[$cache_name] =& $this->obj[$cache_name];
            break;

          case 'redis':
            if (!isset($this->obj[$cache_name])) {
              $this->obj[$cache_name] = new Cache\Redis($this->cfg['redis'], $this->cfg['prefix']);
            }
            $this->ref[$cache_name] =& $this->obj[$cache_name];
            break;

          case 'apcu':
            if (!isset($this->obj[$cache_name])) {
              $this->obj[$cache_name] = new Cache\APCu($this->cfg['prefix']);
            }
            $this->ref[$cache_name] =& $this->obj[$cache_name];
            break;

          case 'postgresql':
            if (!isset($this->obj[$cache_name])) {
              $this->obj[$cache_name] = new Cache\PostgreSQL(DB()->pdo, DB()->cfg, $this->cfg['prefix']);
            }
            $this->ref[$cache_name] =& $this->obj[$cache_name];
            break;

          case 'mysql':
            if (!isset($this->obj[$cache_name])) {
              $this->obj[$cache_name] = new Cache\MySQL(DB()->pdo, DB()->cfg, $this->cfg['prefix']);
            }
            $this->ref[$cache_name] =& $this->obj[$cache_name];
            break;

          case 'filecache':
          default:
            if (!isset($this->obj[$cache_name])) {
              $this->obj[$cache_name] = new Cache\File($this->cfg['cache_dir'] . "filecache/$cache_name/", $this->cfg['filecache'], $this->cfg['prefix']);
            }
            $this->ref[$cache_name] =& $this->obj[$cache_name];
            break;
        }
      }
    }

    return $this->ref[$cache_name];
  }
}
