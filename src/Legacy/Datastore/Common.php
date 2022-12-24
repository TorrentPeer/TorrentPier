<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Legacy\Datastore;

use Exception;
use TorrentPier\Legacy\Dev;
use function in_array;

/**
 * Class Common
 * @package TorrentPier\Legacy\Datastore
 */
class Common
{
  /**
   * Директория с builder-скриптами (внутри INC_DIR)
   */
  public $ds_dir = 'datastore';

  /**
   * Готовая к употреблению data
   * array('title' => data)
   */
  public $data = [];

  /**
   * Список элементов, которые будут извлечены из хранилища при первом же запросе get()
   * до этого момента они ставятся в очередь $queued_items для дальнейшего извлечения _fetch()'ем
   * всех элементов одним запросом
   * array('title1', 'title2'...)
   */
  public $queued_items = [];

  /**
   * 'title' => 'builder script name' inside "includes/datastore" dir
   */
  public $known_items = [
    'cat_forums' => 'build_cat_forums.php',
    'jumpbox' => 'build_cat_forums.php',
    'viewtopic_forum_select' => 'build_cat_forums.php',
    'latest_news' => 'build_cat_forums.php',
    'network_news' => 'build_cat_forums.php',
    'ads' => 'build_cat_forums.php',
    'moderators' => 'build_moderators.php',
    'stats' => 'build_stats.php',
    'ranks' => 'build_ranks.php',
    'attach_extensions' => 'build_attach_extensions.php',
    'smile_replacements' => 'build_smilies.php',
    'notices' => 'build_notices.php',
  ];

  /**
   * Enqueue
   *
   * @param $items (item1_title, item2_title...) or single item's title
   */
  public function enqueue($items)
  {
    foreach ((array)$items as $item) {
      // игнор уже поставленного в очередь либо уже извлеченного
      if (!in_array($item, $this->queued_items) && !isset($this->data[$item])) {
        $this->queued_items[] = $item;
      }
    }
  }

  /**
   * Get
   *
   * @param $title
   * @return mixed
   * @throws Exception
   */
  public function &get($title)
  {
    if (!isset($this->data[$title])) {
      $this->enqueue($title);
      $this->_fetch();
    }
    return $this->data[$title];
  }

  /**
   * Store
   *
   * @param $item_name
   * @param $item_data
   */
  public function store($item_name, $item_data)
  {
  }

  /**
   * Remove
   *
   * @param $items
   */
  public function rm($items)
  {
    foreach ((array)$items as $item) {
      unset($this->data[$item]);
    }
  }

  /**
   * Update
   *
   * @param $items
   * @throws Exception
   */
  public function update($items)
  {
    if ($items == 'all') {
      $items = array_keys(array_unique($this->known_items));
    }
    foreach ((array)$items as $item) {
      $this->_build_item($item);
    }
  }

  /**
   * Fetch
   *
   * @return void
   * @throws Exception
   */
  public function _fetch()
  {
    $this->_fetch_from_store();

    foreach ($this->queued_items as $title) {
      if (!isset($this->data[$title]) || $this->data[$title] === false) {
        $this->_build_item($title);
      }
    }

    $this->queued_items = [];
  }

  /**
   * Fetch from store
   *
   * @return void
   */
  public function _fetch_from_store()
  {
  }

  /**
   * Build item
   *
   * @param $title
   * @throws Exception
   */
  public function _build_item($title)
  {
    $file = INC_DIR . '/' . $this->ds_dir . '/' . $this->known_items[$title];
    if (isset($this->known_items[$title]) && file_exists($file)) {
      require $file;
    } else {
      bb_simple_die("Unknown datastore item: $title");
    }
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
