<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Legacy\Cache;

use TorrentPier\Legacy\Dev;

/**
 * Class Redis
 * @package TorrentPier\Legacy\Cache
 */
class Redis extends Common
{
  public $used = true;
  public $engine = 'Redis';
  public $cfg;
  public $redis;
  public $prefix;
  public $connected = false;

  /**
   * Redis constructor.
   *
   * @param $cfg
   * @param null $prefix
   * @throws \Exception
   */
  public function __construct($cfg, $prefix = null)
  {
    if (!$this->is_installed()) {
      Dev::error_message('Error: Redis extension not installed');
    }

    $this->cfg = $cfg;
    $this->prefix = $prefix;
    $this->redis = new \Redis();
    $this->dbg_enabled = Dev::sql_dbg_enabled();
  }

  /**
   * Connect to redis host
   *
   * @throws \Exception
   */
  public function connect()
  {
    $this->cur_query = 'connect ' . $this->cfg['host'] . ':' . $this->cfg['port'];
    $this->debug('start');

    if (@$this->redis->connect($this->cfg['host'], $this->cfg['port'])) {
      $this->connected = true;
    }

    if (!$this->connected && $this->cfg['con_required']) {
      Dev::error_message('Could not connect to redis server');
    }

    $this->debug('stop');
    $this->cur_query = null;
  }

  /**
   * Get a value by key name
   *
   * @param $name
   * @param string $get_miss_key_callback
   * @param int $ttl
   * @return array|false|mixed
   * @throws \Exception
   */
  public function get($name, $get_miss_key_callback = '', $ttl = 0)
  {
    if (!$this->connected) {
      $this->connect();
    }

    $this->cur_query = "cache->get('$name')";
    $this->debug('start');
    $this->debug('stop');
    $this->cur_query = null;
    $this->num_queries++;

    return ($this->connected) ? unserialize($this->redis->get($this->prefix . $name)) : false;
  }

  /**
   * Set a value by name
   *
   * @param $name
   * @param $value
   * @param int $ttl
   * @return bool
   * @throws \Exception
   */
  public function set($name, $value, $ttl = 0)
  {
    if (!$this->connected) {
      $this->connect();
    }

    $this->cur_query = "cache->set('$name')";
    $this->debug('start');

    if ($this->redis->set($this->prefix . $name, serialize($value))) {
      if ($ttl > 0) {
        $this->redis->expire($this->prefix . $name, $ttl);
      }

      $this->debug('stop');
      $this->cur_query = null;
      $this->num_queries++;

      return true;
    }

    return false;
  }

  /**
   * Remove key & value by name
   *
   * @param string $name
   * @return bool|int
   * @throws \Exception
   */
  public function rm($name = '')
  {
    if (!$this->connected) {
      $this->connect();
    }

    if ($name) {
      $this->cur_query = "cache->rm('$name')";
      $this->debug('start');
      $this->debug('stop');
      $this->cur_query = null;
      $this->num_queries++;

      return ($this->connected) ? $this->redis->del($this->prefix . $name) : false;
    }

    return ($this->connected) ? $this->redis->flushDB() : false;
  }

  /**
   * Check if extension is installed
   *
   * @return bool
   */
  private function is_installed(): bool
  {
    return class_exists('Redis');
  }
}
