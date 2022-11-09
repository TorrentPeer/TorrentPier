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

$sql = "(SELECT i.invite_id
FROM " . BB_INVITES . " i
LEFT JOIN bb_users u ON (u.user_id = i.new_user_id)
WHERE i.new_user_id <> 0 AND u.username IS NULL)
UNION
(SELECT i.invite_id
FROM " . BB_INVITES . " i
LEFT JOIN bb_users u ON (u.user_id = i.user_id)
WHERE i.user_id <> 0 AND u.username IS NULL)";

$new_id = [];
foreach (DB()->fetch_rowset($sql) as $row) {
  {
    $new_id[] = $row['invite_id'];
  }
  $del_ids = implode(",", $new_id);
  DB()->query("DELETE FROM " . BB_INVITES . " WHERE invite_id IN($del_ids)");
}
