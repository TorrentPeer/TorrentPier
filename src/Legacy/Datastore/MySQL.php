<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Legacy\Datastore;

use MatthiasMullie\Scrapbook\Adapters\MySQL as My;
use PDO;

use TorrentPier\Legacy\Dev;

/**
 * Class MySQL
 * @package TorrentPier\Legacy\Datastore
 */
class MySQL extends Common
{
  private $mysql;
  private $prefix;
  private $cfg;

  public $engine = 'MySQL';
  public $connected = false;

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
   * Store a value by name
   *
   * @param $title
   * @param $var
   * @return false|void
   */
  public function store($title, $var)
  {
    $this->connect();

    $this->data[$title] = $var;

    $this->cur_query = "Set datastore: $title";
    $this->debug('start');

    if ($store = $this->mysql->set($this->prefix . $title, $var)) {
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
    $this->connect();

    foreach ($this->known_items as $title => $script_name) {
      $this->cur_query = "Clean datastore";
      $this->debug('start');

      $this->mysql->delete($this->prefix . $title);

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
    $this->connect();

    if (!$items = $this->queued_items) {
      /** TODO
       * $src = $this->_debug_find_caller('enqueue');
       * Dev::error_message("Datastore: item '$item' already enqueued [$src]");
       */
    }

    foreach ($items as $item) {
      $this->cur_query = "Get datastore: $item";
      $this->debug('start');

      $this->data[$item] = $this->mysql->get($this->prefix . $item);

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
    return class_exists('MatthiasMullie\Scrapbook\Adapters\MySQL') && class_exists('PDO');
  }
}