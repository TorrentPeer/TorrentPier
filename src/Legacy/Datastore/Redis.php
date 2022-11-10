<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Legacy\Datastore;

use TorrentPier\Legacy\Dev;

/**
 * Class Redis
 * @package TorrentPier\Legacy\Datastore
 */
class Redis extends Common
{
  public $cfg;
  public $redis;
  public $prefix;
  public $connected = false;
  public $engine = 'Redis';

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
    $this->redis = new \Redis();
    $this->dbg_enabled = Dev::sql_dbg_enabled();
    $this->prefix = $prefix;
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
   * Store a value by name
   *
   * @param $title
   * @param $var
   * @return bool
   * @throws \Exception
   */
  public function store($title, $var)
  {
    if (!$this->connected) {
      $this->connect();
    }
    $this->data[$title] = $var;

    $this->cur_query = "cache->store('$title')";
    $this->debug('start');
    $this->debug('stop');
    $this->cur_query = null;
    $this->num_queries++;

    return (bool)$this->redis->set($this->prefix . $title, serialize($var));
  }

  /**
   * Clean all datastore objects
   *
   * @throws \Exception
   */
  public function clean()
  {
    if (!$this->connected) {
      $this->connect();
    }
    foreach ($this->known_items as $title => $script_name) {
      $this->cur_query = "cache->clean('$title')";
      $this->debug('start');
      $this->debug('stop');
      $this->cur_query = null;
      $this->num_queries++;

      $this->redis->del($this->prefix . $title);
    }
  }

  /**
   * Get values
   *
   * @throws \Exception
   */
  public function _fetch_from_store()
  {
    if (!$items = $this->queued_items) {
      /** TODO
       * $src = $this->_debug_find_caller('enqueue');
       * Dev::error_message("Datastore: item '$item' already enqueued [$src]");
       */
    }

    if (!$this->connected) {
      $this->connect();
    }
    foreach ($items as $item) {
      $this->cur_query = "cache->_fetch_from_store('$item')";
      $this->debug('start');
      $this->debug('stop');
      $this->cur_query = null;
      $this->num_queries++;

      $this->data[$item] = unserialize($this->redis->get($this->prefix . $item));
    }
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
