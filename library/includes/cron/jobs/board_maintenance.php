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

// Синхронизация
\TorrentPier\Legacy\Admin\Common::sync('topic', 'all');
\TorrentPier\Legacy\Admin\Common::sync('user_posts', 'all');
\TorrentPier\Legacy\Admin\Common::sync_all_forums();

// Чистка bb_poll_users
if ($poll_max_days = (int)$bb_cfg['poll_max_days']) {
  $per_cycle = 20000;
  $row = DB()->fetch_row("SELECT MIN(topic_id) AS start_id, MAX(topic_id) AS finish_id FROM " . BB_POLL_USERS);
  $start_id = (int)$row['start_id'];
  $finish_id = (int)$row['finish_id'];

  while (true) {
    set_time_limit(600);
    $end_id = $start_id + $per_cycle - 1;

    DB()->query("
			DELETE FROM " . BB_POLL_USERS . "
			WHERE topic_id BETWEEN $start_id AND $end_id
				AND vote_dt < DATE_SUB(NOW(), INTERVAL $poll_max_days DAY)
		");

    if ($end_id > $finish_id) {
      break;
    }

    $start_id += $per_cycle;
  }
}

// Чистка user_newpasswd
DB()->query("UPDATE " . BB_USERS . " SET user_newpasswd = '' WHERE user_lastvisit < " . (TIMENOW - 7 * 86400));

// Чистка кеша постов
if ($posts_days = (int)$bb_cfg['posts_cache_days_keep']) {
  DB()->query("DELETE FROM " . BB_POSTS_HTML . " WHERE post_html_time < DATE_SUB(NOW(), INTERVAL $posts_days DAY)");
}
