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

// Lock tables
DB()->lock([
  BB_TOPICS . ' t',
  BUF_TOPIC_VIEW . ' buf',
]);

// Flash buffered records
DB()->query("
	UPDATE
		" . BB_TOPICS . " t,
		" . BUF_TOPIC_VIEW . " buf
	SET
		t.topic_views = t.topic_views + buf.topic_views
	WHERE
		t.topic_id = buf.topic_id
");

// Delete buffered records
DB()->query("DELETE buf FROM " . BUF_TOPIC_VIEW . " buf");

// Unlock tables
DB()->unlock();
