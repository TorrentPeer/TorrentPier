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
use TorrentPier\Legacy\Filesystem;

/**
 * Class File
 * @package TorrentPier\Legacy\Datastore
 */
class File extends Common
{
  public $dir;
  public $prefix;
  public $engine = 'Filecache';

  /**
   * File constructor.
   *
   * @param $dir
   * @param null $prefix
   */
  public function __construct($dir, $prefix = null)
  {
    $this->prefix = $prefix;
    $this->dir = $dir;
    $this->dbg_enabled = Dev::sql_dbg_enabled();
  }

  /**
   * Store a value by name
   *
   * @param $title
   * @param $var
   * @return bool
   */
  public function store($title, $var)
  {
    $this->cur_query = "cache->set('$title')";
    $this->debug('start');

    $this->data[$title] = $var;

    $filename = $this->dir . Filesystem::clean_filename($this->prefix . $title) . '.php';

    $filecache = "<?php\n";
    $filecache .= "if (!defined('BB_ROOT')) die(basename(__FILE__));\n";
    $filecache .= '$filecache = ' . var_export($var, true) . ";\n";
    $filecache .= '?>';

    $this->debug('stop');
    $this->cur_query = null;
    $this->num_queries++;

    return (bool)Filesystem::file_write($filecache, $filename, false, true, true);
  }

  /**
   * Clean all datastore objects
   */
  public function clean()
  {
    $dir = $this->dir;

    if (is_dir($dir)) {
      if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
          if ($file != "." && $file != "..") {
            $filename = $dir . $file;

            unlink($filename);
          }
        }
        closedir($dh);
      }
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
      $src = $this->_debug_find_caller('enqueue');
      Dev::error_message("Datastore: item '$item' already enqueued [$src]");
    }

    foreach ($items as $item) {
      $filename = $this->dir . $this->prefix . $item . '.php';

      $this->cur_query = "cache->get('$item')";
      $this->debug('start');
      $this->debug('stop');
      $this->cur_query = null;
      $this->num_queries++;

      if (file_exists($filename)) {
        require($filename);

        $this->data[$item] = $filecache;
      }
    }
  }
}
