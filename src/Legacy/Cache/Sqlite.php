<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2018 TorrentPier (https://torrentpier.com)
 * @link      https://github.com/torrentpier/torrentpier for the canonical source repository
 * @license   https://github.com/torrentpier/torrentpier/blob/master/LICENSE MIT License
 */

namespace TorrentPier\Legacy\Cache;

use SQLite3;
use TorrentPier\Helpers\BaseHelper;
use TorrentPier\Legacy\Dev;

/**
 * Class Sqlite
 * @package TorrentPier\Legacy\Cache
 */
class Sqlite extends Common
{
  public $used = true;
  public $db;
  public $prefix;
  public $cfg = [
    'db_file_path' => '/path/to/cache.db.sqlite',
    'table_name' => 'cache',
    'table_schema' => 'CREATE TABLE cache (
	                cache_name        VARCHAR(255),
	                cache_expire_time INT,
	                cache_value       TEXT,
	                PRIMARY KEY (cache_name)
	        )',
    'pconnect' => true,
    'con_required' => true,
    'log_name' => 'CACHE',
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
   * Get a value by key name
   *
   * @param $name
   * @param string $get_miss_key_callback
   * @param int $ttl
   * @return array|false|mixed
   * @throws \Exception
   */
  public function get($name, $get_miss_key_callback = '', $ttl = 604800)
  {
    if (empty($name)) {
      return \is_array($name) ? [] : false;
    }
    $this->db->shard($name);
    $cached_items = [];
    $this->prefix_len = \strlen($this->prefix);
    $this->prefix_sql = SQLite3::escapeString($this->prefix);

    $name_ary = $name_sql = (array)$name;
    BaseHelper::array_deep($name_sql, 'SQLite3::escapeString');

    // get available items
    $rowset = $this->db->fetch_rowset("
			SELECT cache_name, cache_value
			FROM " . $this->cfg['table_name'] . "
			WHERE cache_name IN('$this->prefix_sql" . implode("','$this->prefix_sql", $name_sql) . "') AND cache_expire_time > " . TIMENOW . "
			LIMIT " . \count($name_ary) . "
		");

    $this->db->debug('start', 'unserialize()');
    foreach ($rowset as $row) {
      $cached_items[substr($row['cache_name'], $this->prefix_len)] = unserialize($row['cache_value']);
    }
    $this->db->debug('stop');

    // get miss items
    if ($get_miss_key_callback and $miss_key = array_diff($name_ary, array_keys($cached_items))) {
      foreach ($get_miss_key_callback($miss_key) as $k => $v) {
        $this->set($this->prefix . $k, $v, $ttl);
        $cached_items[$k] = $v;
      }
    }
    // return
    if (\is_array($this->prefix . $name)) {
      return $cached_items;
    }

    return $cached_items[$name] ?? false;
  }

  /**
   * Set a value by name
   *
   * @param $name
   * @param $value
   * @param int $ttl
   * @return bool
   * @throws \Exception
   */
  public function set($name, $value, $ttl = 604800)
  {
    $this->db->shard($this->prefix . $name);
    $name_sql = SQLite3::escapeString($this->prefix . $name);
    $expire = TIMENOW + $ttl;
    $value_sql = SQLite3::escapeString(serialize($value));

    $result = $this->db->query("REPLACE INTO " . $this->cfg['table_name'] . " (cache_name, cache_expire_time, cache_value) VALUES ('$name_sql', $expire, '$value_sql')");
    return (bool)$result;
  }

  /**
   * Remove key & value by name
   *
   * @param string $name
   * @return bool
   * @throws \Exception
   */
  public function rm($name = '')
  {
    if ($name) {
      $this->db->shard($this->prefix . $name);
      $result = $this->db->query("DELETE FROM " . $this->cfg['table_name'] . " WHERE cache_name = '" . SQLite3::escapeString($this->prefix . $name) . "'");
    } else {
      $result = $this->db->query("DELETE FROM " . $this->cfg['table_name']);
    }
    return (bool)$result;
  }

  /**
   * @param int $expire_time
   * @return int
   * @throws \Exception
   */
  public function gc($expire_time = TIMENOW)
  {
    $result = $this->db->query("DELETE FROM " . $this->cfg['table_name'] . " WHERE cache_expire_time < $expire_time");
    return $result ? $this->db->changes() : 0;
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
