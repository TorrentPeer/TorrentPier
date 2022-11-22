<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Legacy\Cache;

use MatthiasMullie\Scrapbook\Adapters\Sqlite as Lite;
use PDO;

use TorrentPier\Legacy\Dev;

/**
 * Class Sqlite
 * @package TorrentPier\Legacy\Cache
 */
class Sqlite extends Common
{
  private $sqlite;
  private $prefix;

  public $engine = 'sqlite';
  public $used = false;

  /**
   * Sqlite constructor.
   *
   * @param $file
   * @param null $prefix
   * @throws \Exception
   */
  public function __construct($file, $prefix = null)
  {
    if (!$this->is_installed()) {
      bb_simple_die("Error: {$this->engine} class not loaded");
    }

    $this->used = true;

    $client = new PDO("sqlite:{$file}");
    $this->sqlite = new Lite($client);

    $this->dbg_enabled = Dev::sql_dbg_enabled();
    $this->prefix = $prefix;
  }

  /**
   * Get a value by key name
   *
   * @param $name
   * @param string $get_miss_key_callback
   * @param int $ttl
   * @return array|bool|float|int|mixed|string
   */
  public function get($name, $get_miss_key_callback = '', $ttl = 0)
  {
    $this->cur_query = "Get cache: $name";
    $this->debug('start');

    if ($get = $this->sqlite->get($this->prefix . $name)) {
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
   * @return bool
   */
  public function set($name, $value, $ttl = 0)
  {
    $this->cur_query = "Set cache: $name";
    $this->debug('start');

    if ($set = $this->sqlite->set($this->prefix . $name, $value, $ttl)) {
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
   * @return bool
   */
  public function rm($name = '')
  {
    $name ? $this->cur_query = "Remove cache: $name" : $this->cur_query = "Remove all items from cache";

    $this->debug('start');

    if ($name) {
      $remove = $this->sqlite->delete($this->prefix . $name);
    } else {
      $remove = $this->sqlite->flush();
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
    return class_exists('MatthiasMullie\Scrapbook\Adapters\SQLite') && class_exists('PDO');
  }
}
