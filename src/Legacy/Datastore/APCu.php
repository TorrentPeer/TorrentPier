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
 * Class APCu
 * @package TorrentPier\Legacy\Datastore
 */
class APCu extends Common
{
  public $prefix;
  public $engine = 'APCu';

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

    $this->dbg_enabled = Dev::sql_dbg_enabled();
    $this->prefix = $prefix;
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
    $this->data[$title] = $var;

    $this->cur_query = "cache->set('$title')";
    $this->debug('start');
    $this->debug('stop');
    $this->cur_query = null;
    $this->num_queries++;

    return (bool)apcu_store($this->prefix . $title, $var);
  }

  /**
   * Clean all datastore objects
   */
  public function clean()
  {
    foreach ($this->known_items as $title => $script_name) {
      $this->cur_query = "cache->rm('$title')";
      $this->debug('start');
      $this->debug('stop');
      $this->cur_query = null;
      $this->num_queries++;

      apcu_delete($this->prefix . $title);
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
      $this->cur_query = "cache->get('$item')";
      $this->debug('start');
      $this->debug('stop');
      $this->cur_query = null;
      $this->num_queries++;

      $this->data[$item] = apcu_fetch($this->prefix . $item);
    }
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
