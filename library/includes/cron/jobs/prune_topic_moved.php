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

if ($bb_cfg['topic_moved_days_keep']) {
  $prune_time = TIMENOW - 86400 * $bb_cfg['topic_moved_days_keep'];

  DB()->query("
		DELETE FROM " . BB_TOPICS . "
		WHERE topic_status = " . TOPIC_MOVED . "
			AND topic_time < $prune_time
	");
}
