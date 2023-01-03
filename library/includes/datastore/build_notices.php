<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2023 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

if (!defined('BB_ROOT')) {
  die(basename(__FILE__));
}

$notices = [];

$sql = "SELECT notice_id, notice_active, notice_text FROM " . BB_NOTICES;

foreach (DB()->fetch_rowset($sql) as $row) {
  $notices[$row['notice_id']] = $row;
}

$this->store('notices', $notices);
