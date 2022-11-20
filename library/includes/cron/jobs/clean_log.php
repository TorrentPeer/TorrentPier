<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

if (!defined('BB_ROOT')) {
  die(basename(__FILE__));
}

$log_days_keep = (int)$bb_cfg['log_days_keep'];

DB()->query("
	DELETE FROM " . BB_LOG . "
	WHERE log_time < " . (TIMENOW - 86400 * $log_days_keep) . "
");
