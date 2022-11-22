<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Legacy\Cache;

use belomaxorka\DOFileCache\DOFileCache;

use TorrentPier\Legacy\Dev;

/**
 * Class File
 * @package TorrentPier\Legacy\Cache
 */
class File extends Common
{
  private $prefix;
  private $filecache;

  public $engine = 'files';
  public $used = false;

  /**
   * File constructor.
   *
   * @param $dir
   * @param $cfg
   * @param null $prefix
   * @throws \Exception
   */
  public function __construct($dir, $cfg, $prefix = null)
  {
    if (!$this->is_installed()) {
      bb_simple_die("Error: {$this->engine} class not loaded");
    }

    $this->filecache = new DOFileCache();

    $this->filecache->changeConfig([
      'cacheDirectory' => $dir,
      'gzipCompression' => $cfg['gzipCompression'],
      'fileExtension' => $cfg['fileExtension'],
      'unixLoadUpperThreshold' => '-1',
      'newStyledFilesOrganization' => false,
    ]);

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
   * @throws \Exception
   */
  public function get($name, $get_miss_key_callback = '', $ttl = 0)
  {
    $this->cur_query = "Get cache: $name";
    $this->debug('start');

    if ($get = $this->filecache->get($this->prefix . $name)) {
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

    if ($set = $this->filecache->set($this->prefix . $name, $value, $ttl)) {
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
      $remove = $this->filecache->delete($this->prefix . $name);
    } else {
      $remove = $this->filecache->flush();
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
    return class_exists('belomaxorka\DOFileCache\DOFileCache');
  }
}
