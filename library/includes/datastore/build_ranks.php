<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
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
