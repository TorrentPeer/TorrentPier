<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2023 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Legacy\Datastore;

use belomaxorka\DOFileCache\DOFileCache;

use Exception;
use TorrentPier\Legacy\Dev;

/**
 * Class File
 * @package TorrentPier\Legacy\Datastore
 */
class File extends Common
{
  private $filecache;
  private $prefix;

  public $engine = 'files';

  /**
   * File constructor.
   *
   * @param $dir
   * @param $cfg
   * @param null $prefix
   * @throws Exception
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

    $this->dbg_enabled = Dev::sql_dbg_enabled();
    $this->prefix = $prefix;
  }

  /**
   * Store a value by name
   *
   * @param $title
   * @param $var
   * @return bool|void
   */
  public function store($title, $var)
  {
    $this->data[$title] = $var;

    $this->cur_query = "Set datastore: $title";
    $this->debug('start');

    if ($store = $this->filecache->set($this->prefix . $title, $var)) {
      $this->debug('stop');
      $this->cur_query = null;
      $this->num_queries++;

      return $store;
    }

    return false;
  }

  /**
   * Clean all datastore objects
   */
  public function clean()
  {
    foreach ($this->known_items as $title => $script_name) {
      $this->cur_query = "Clean datastore";
      $this->debug('start');

      $this->filecache->delete($this->prefix . $title);

      $this->debug('stop');
      $this->cur_query = null;
      $this->num_queries++;
    }
  }

  /**
   * Get values
   */
  public function _fetch_from_store()
  {
    if (!$items = $this->queued_items) {
      /** TODO
       * $src = $this->_debug_find_caller('enqueue');
       * bb_simple_die("Datastore: item '$item' already enqueued [$src]");
       */
    }

    foreach ($items as $item) {
      $this->cur_query = "Get datastore: $item";
      $this->debug('start');

      $this->data[$item] = $this->filecache->get($this->prefix . $item);

      $this->debug('stop');
      $this->cur_query = null;
      $this->num_queries++;
    }
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
