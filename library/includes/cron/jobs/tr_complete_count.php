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

global $bb_cfg;

if ($bb_cfg['ocelot']['enabled']) {
  // Update TORRENT "completed" counters
  DB()->query("
		UPDATE
			" . BB_BT_TORRENTS . " tor,
			" . BB_BT_TRACKER_SNAP . " snap
		SET
			tor.complete_count = snap.complete
		WHERE
			tor.topic_id = snap.topic_id
	");
} else {
  // Get complete counts
  DB()->query("
		CREATE TEMPORARY TABLE tmp_complete_count
		SELECT
			topic_id, COUNT(*) AS compl_cnt
		FROM " . BB_BT_TORSTAT . "
		WHERE completed = 0
		GROUP BY topic_id
	");

  // Update USER "completed" counters
  DB()->query("UPDATE " . BB_BT_TORSTAT . " SET completed = 1");

  // Update TORRENT "completed" counters
  DB()->query("
		UPDATE
			" . BB_BT_TORRENTS . " tor,
			tmp_complete_count tmp
		SET
			tor.complete_count = tor.complete_count + tmp.compl_cnt
		WHERE
			tor.topic_id = tmp.topic_id
	");

  // Drop tmp table
  DB()->query("DROP TEMPORARY TABLE tmp_complete_count");
}
