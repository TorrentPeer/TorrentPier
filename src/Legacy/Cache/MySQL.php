<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Legacy\Cache;

use MatthiasMullie\Scrapbook\Adapters\MySQL as My;
use PDO;

use TorrentPier\Legacy\Dev;

/**
 * Class MySQL
 * @package TorrentPier\Legacy\Cache
 */
class MySQL extends Common
{
  private $mysql;
  private $prefix;
  private $cfg;

  public $engine = 'MySQL';
  public $connected = false;
  public $used = false;

  /**
   * MySQL constructor.
   *
   * @param $cfg
   * @param null $prefix
   * @throws \Exception
   */
  public function __construct($cfg, $prefix = null)
  {
    if (!$this->is_installed()) {
      Dev::error_message("Error: {$this->engine} class not loaded");
    }

    $this->cfg = $cfg;
    $this->used = true;

    $this->dbg_enabled = Dev::sql_dbg_enabled();
    $this->prefix = $prefix;
  }

  /**
   * Connect to host
   */
  private function connect()
  {
    $client = new PDO("mysql:dbname={$this->cfg['dbname']};host={$this->cfg['dbhost']};port={$this->cfg['dbport']}", $this->cfg['dbuser'], $this->cfg['dbpasswd']);

    if ($client && !$this->connected) {
      $this->connected = true;

      $this->cur_query = "Connect to: {$this->cfg['dbhost']}:{$this->cfg['dbport']}";
      $this->debug('start');

      $this->mysql = new My($client, 'bb_cache');

      $this->debug('stop');
      $this->cur_query = null;
      $this->num_queries++;
    }
  }

  /**
   * Get a value by key name
   *
   * @param $name
   * @param string $get_miss_key_callback
   * @param int $ttl
   * @return array|false|mixed
   */
  public function get($name, $get_miss_key_callback = '', $ttl = 0)
  {
    $this->connect();

    $this->cur_query = "Get cache: $name";
    $this->debug('start');

    if ($get = $this->mysql->get($this->prefix . $name)) {
      $this->debug('stop');
      $this->cur_query = null;
      $this->num_queries++;

      return $get;
    }

    return false;
  }

  /**
   * Set a value by name
   *
   * @param $name
   * @param $value
   * @param int $ttl
   * @return false
   */
  public function set($name, $value, $ttl = 0)
  {
    $this->connect();

    $this->cur_query = "Set cache: $name";
    $this->debug('start');

    if ($set = $this->mysql->set($this->prefix . $name, $value, $ttl)) {
      $this->debug('stop');
      $this->cur_query = null;
      $this->num_queries++;

      return $set;
    }

    return false;
  }

  /**
   * Remove key & value by name
   *
   * @param string $name
   * @return false
   */
  public function rm($name = '')
  {
    $this->connect();

    $name ? $this->cur_query = "Remove cache: $name" : $this->cur_query = "Remove all items from cache";

    $this->debug('start');

    if ($name) {
      $remove = $this->mysql->delete($this->prefix . $name);
    } else {
      $remove = $this->mysql->flush();
    }

    $this->debug('stop');

    $this->cur_query = null;
    $this->num_queries++;

    return $remove;
  }

  /**
   * Check if extension is installed
   *
   * @return bool
   */
  private function is_installed(): bool
  {
    return class_exists('MatthiasMullie\Scrapbook\Adapters\MySQL') && class_exists('PDO');
  }
}