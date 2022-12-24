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
use function is_array;

/**
 * Class Common
 * @package TorrentPier\Legacy\Cache
 */
class Common
{
  /**
   * Is used?
   *
   * @var bool
   */
  public $used = false;

  /**
   * Returns value of variable
   *
   * @param $name
   * @param $get_miss_key_callback
   * @param $ttl
   * @return array|false|mixed
   */
  public function get($name, $get_miss_key_callback = '', $ttl = 604800)
  {
    if ($get_miss_key_callback) {
      return $get_miss_key_callback($name);
    }
    return is_array($name) ? [] : false;
  }

  /**
   * Store value of variable
   *
   * @param $name
   * @param $value
   * @param $ttl
   * @return false
   */
  public function set($name, $value, $ttl = 604800)
  {
    return false;
  }

  /**
   * Remove variable
   *
   * @param $name
   * @return false
   */
  public function rm($name = '')
  {
    return false;
  }

  public $num_queries = 0;
  public $sql_starttime = 0;
  public $sql_inittime = 0;
  public $sql_timetotal = 0;
  public $cur_query_time = 0;

  public $dbg = [];
  public $dbg_id = 0;
  public $dbg_enabled = false;
  public $cur_query;

  /**
   * Return a debug info
   *
   * @param $mode
   * @param null $cur_query
   */
  public function debug($mode, $cur_query = null)
  {
    if (!$this->dbg_enabled) {
      return;
    }

    $id =& $this->dbg_id;
    $dbg =& $this->dbg[$id];

    if ($mode == 'start') {
      $this->sql_starttime = utime();

      $dbg['sql'] = isset($cur_query) ? Dev::short_query($cur_query) : Dev::short_query($this->cur_query);
      $dbg['src'] = $this->debug_find_source();
      $dbg['file'] = $this->debug_find_source('file');
      $dbg['line'] = $this->debug_find_source('line');
      $dbg['time'] = '';
    } elseif ($mode == 'stop') {
      $this->cur_query_time = utime() - $this->sql_starttime;
      $this->sql_timetotal += $this->cur_query_time;
      $dbg['time'] = $this->cur_query_time;
      $id++;
    }
  }

  /**
   * Debug [Find source]
   *
   * @param string $mode
   * @return mixed|string
   */
  public function debug_find_source(string $mode = '')
  {
    foreach (debug_backtrace() as $trace) {
      if ($trace['file'] !== __FILE__) {
        switch ($mode) {
          case 'file':
            return $trace['file'];
          case 'line':
            return $trace['line'];
          default:
            return hide_bb_path($trace['file']) . '(' . $trace['line'] . ')';
        }
      }
    }

    return 'src not found';
  }
}
