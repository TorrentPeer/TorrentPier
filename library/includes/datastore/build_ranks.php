<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2018 TorrentPier (https://torrentpier.com)
 * @link      https://github.com/torrentpier/torrentpier for the canonical source repository
 * @license   https://github.com/torrentpier/torrentpier/blob/master/LICENSE MIT License
 */

if (!defined('BB_ROOT')) {
  \TorrentPier\Legacy\Dev::error_message(basename(__FILE__));
}

$ranks = [];

$sql = "SELECT rank_id, rank_title, rank_image, rank_style FROM " . BB_RANKS;

foreach (DB()->fetch_rowset($sql) as $row) {
  $ranks[$row['rank_id']] = $row;
}

$this->store('ranks', $ranks);
