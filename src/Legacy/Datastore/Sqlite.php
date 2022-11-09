<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2018 TorrentPier (https://torrentpier.com)
 * @link      https://github.com/torrentpier/torrentpier for the canonical source repository
 * @license   https://github.com/torrentpier/torrentpier/blob/master/LICENSE MIT License
 */

namespace TorrentPier\Legacy\Datastore;

use SQLite3;
use TorrentPier\Helpers\BaseHelper;
use TorrentPier\Legacy\Dev;

/**
 * Class Sqlite
 * @package TorrentPier\Legacy\Datastore
 */
class Sqlite extends Common
{
  public $engine = 'SQLite';
  public $db;
  public $prefix;
  public $cfg = [
    'db_file_path' => '/path/to/datastore.db.sqlite',
    'table_name' => 'datastore',
    'table_schema' => 'CREATE TABLE datastore (
	            ds_title       VARCHAR(255),
	            ds_data        TEXT,
	            PRIMARY KEY (ds_title)
	        )',
    'pconnect' => true,
    'con_required' => true,
    'log_name' => 'DATASTORE',
  ];

  /**
   * Sqlite constructor.
   *
   * @param $cfg
   * @param null $prefix
   * @throws \Exception
   */
  public function __construct($cfg, $prefix = null)
  {
    if (!$this->is_installed()) {
      Dev::error_message('Error: SQLite3 extension not installed');
    }

    $this->cfg = array_merge($this->cfg, $cfg);
    $this->db = new SqliteCommon($this->cfg);
    $this->prefix = $prefix;
  }

  /**
   * Store a value by name
   *
   * @param $item_name
   * @param $item_data
   * @return bool
   * @throws \Exception
   */
  public function store($item_name, $item_data)
  {
    $this->data[$item_name] = $item_data;

    $ds_title = SQLite3::escapeString($this->prefix . $item_name);
    $ds_data = SQLite3::escapeString(serialize($item_data));

    $result = $this->db->query("REPLACE INTO " . $this->cfg['table_name'] . " (ds_title, ds_data) VALUES ('$ds_title', '$ds_data')");

    return (bool)$result;
  }

  /**
   * Clean all datastore objects
   */
  public function clean()
  {
    $this->db->query("DELETE FROM " . $this->cfg['table_name']);
  }

  /**
   * Get values
   */
  public function _fetch_from_store()
  {
    if (!$items = $this->queued_items) {
      return;
    }

    $prefix_len = \strlen($this->prefix);
    $prefix_sql = SQLite3::escapeString($this->prefix);

    BaseHelper::array_deep($items, 'SQLite3::escapeString');
    $items_list = $prefix_sql . implode("','$prefix_sql", $items);

    $rowset = $this->db->fetch_rowset("SELECT ds_title, ds_data FROM " . $this->cfg['table_name'] . " WHERE ds_title IN ('$items_list')");

    $this->db->debug('start', "unserialize()");
    foreach ($rowset as $row) {
      $this->data[substr($row['ds_title'], $prefix_len)] = unserialize($row['ds_data']);
    }
    $this->db->debug('stop');
  }

  /**
   * Check if extension is installed
   *
   * @return bool
   */
  private function is_installed(): bool
  {
    return extension_loaded('sqlite3');
  }
}
