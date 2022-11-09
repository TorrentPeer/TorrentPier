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
 * Class APCu
 * @package TorrentPier\Legacy\Cache
 */
class APCu extends Common
{
  public $used = true;
  public $engine = 'APCu';
  public $prefix;

  /**
   * APCu constructor.
   *
   * @param null $prefix
   * @throws \Exception
   */
  public function __construct($prefix = null)
  {
    if (!$this->is_installed()) {
      Dev::error_message('Error: APCu extension not installed');
    }

    $this->prefix = $prefix;
    $this->dbg_enabled = Dev::sql_dbg_enabled();
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
    $this->cur_query = "cache->get('$name')";
    $this->debug('start');
    $this->debug('stop');
    $this->cur_query = null;
    $this->num_queries++;

    return apcu_fetch($this->prefix . $name);
  }

  /**
   * Set a value by name
   *
   * @param $name
   * @param $value
   * @param int $ttl
   * @return array|bool
   */
  public function set($name, $value, $ttl = 0)
  {
    $this->cur_query = "cache->set('$name')";
    $this->debug('start');
    $this->debug('stop');
    $this->cur_query = null;
    $this->num_queries++;

    return apcu_store($this->prefix . $name, $value, $ttl);
  }

  /**
   * Remove key & value by name
   *
   * @param string $name
   * @return array|bool|string[]
   */
  public function rm($name = '')
  {
    if ($name) {
      $this->cur_query = "cache->rm('$name')";
      $this->debug('start');
      $this->debug('stop');
      $this->cur_query = null;
      $this->num_queries++;

      return apcu_delete($this->prefix . $name);
    }

    return apcu_clear_cache();
  }

  /**
   * Check if extension is installed
   *
   * @return bool
   */
  private function is_installed(): bool
  {
    return function_exists('apcu_enabled');
  }
}
