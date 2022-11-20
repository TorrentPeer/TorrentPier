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

DB()->query("
	UPDATE
		" . BUF_LAST_SEEDER . " b,
		" . BB_BT_TORRENTS . " tor
	SET
		tor.seeder_last_seen = b.seeder_last_seen
	WHERE
		tor.topic_id = b.topic_id
");

DB()->query("TRUNCATE TABLE " . BUF_LAST_SEEDER);
