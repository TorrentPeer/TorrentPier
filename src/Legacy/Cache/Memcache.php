<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2018 TorrentPier (https://torrentpier.com)
 * @link      https://github.com/torrentpier/torrentpier for the canonical source repository
 * @license   https://github.com/torrentpier/torrentpier/blob/master/LICENSE MIT License
 */

namespace TorrentPier\Legacy\Cache;

use TorrentPier\Legacy\Dev;

/**
 * Class Memcache
 * @package TorrentPier\Legacy\Cache
 */
class Memcache extends Common
{
  public $used = true;
  public $engine = 'Memcache';
  public $cfg;
  public $prefix;
  public $memcache;
  public $connected = false;

  /**
   * Memcache constructor.
   *
   * @param $cfg
   * @param null $prefix
   * @throws \Exception
   */
  public function __construct($cfg, $prefix = null)
  {
    if (!$this->is_installed()) {
      Dev::error_message('Error: Memcached extension not installed');
    }

    $this->cfg = $cfg;
    $this->prefix = $prefix;
    $this->memcache = new \Memcache();
    $this->dbg_enabled = Dev::sql_dbg_enabled();
  }

  /**
   * Connect to memcache host
   *
   * @throws \Exception
   */
  public function connect()
  {
    $connect_type = ($this->cfg['pconnect']) ? 'pconnect' : 'connect';

    $this->cur_query = $connect_type . ' ' . $this->cfg['host'] . ':' . $this->cfg['port'];
    $this->debug('start');

    if (@$this->memcache->$connect_type($this->cfg['host'], $this->cfg['port'])) {
      $this->connected = true;
    }

    if (!$this->connected && $this->cfg['con_required']) {
      Dev::error_message('Could not connect to memcached server');
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
   * @return array|false|mixed|string
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

    return ($this->connected) ? $this->memcache->get($this->prefix . $name) : false;
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
    $this->debug('stop');
    $this->cur_query = null;
    $this->num_queries++;

    return ($this->connected) ? $this->memcache->set($this->prefix . $name, $value, false, $ttl) : false;
  }

  /**
   * Remove key & value by name
   *
   * @param string $name
   * @return bool
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

      return ($this->connected) ? $this->memcache->delete($this->prefix . $name, 0) : false;
    }

    return ($this->connected) ? $this->memcache->flush() : false;
  }

  /**
   * Check if extension is installed
   *
   * @return bool
   */
  private function is_installed(): bool
  {
    return class_exists('Memcache');
  }
}
