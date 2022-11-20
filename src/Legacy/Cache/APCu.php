<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Legacy\Cache;

use MatthiasMullie\Scrapbook\Adapters\Apc;

use TorrentPier\Legacy\Dev;

/**
 * Class APCu
 * @package TorrentPier\Legacy\Cache
 */
class APCu extends Common
{
  private $prefix;
  private $apcu;

  public $engine = 'APCu';
  public $used = false;

  /**
   * APCu constructor.
   *
   * @param null $prefix
   * @throws \Exception
   */
  public function __construct($prefix = null)
  {
    if (!$this->is_installed()) {
      bb_simple_die("Error: {$this->engine} class not loaded");
    }

    $this->apcu = new Apc();

    $this->used = true;
    $this->dbg_enabled = Dev::sql_dbg_enabled();
    $this->prefix = $prefix;
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
    $this->cur_query = "Get cache: $name";
    $this->debug('start');

    if ($get = $this->apcu->get($this->prefix . $name)) {
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
   * @return bool|bool[]
   */
  public function set($name, $value, $ttl = 0)
  {
    $this->cur_query = "Set cache: $name";
    $this->debug('start');

    if ($set = $this->apcu->set($this->prefix . $name, $value, $ttl)) {
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
   * @return bool|string[]
   */
  public function rm($name = '')
  {
    $name ? $this->cur_query = "Remove cache: $name" : $this->cur_query = "Remove all items from cache";

    $this->debug('start');

    if ($name) {
      $remove = $this->apcu->delete($this->prefix . $name);
    } else {
      $remove = $this->apcu->flush();
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
    return class_exists('MatthiasMullie\Scrapbook\Adapters\Apc');
  }
}
