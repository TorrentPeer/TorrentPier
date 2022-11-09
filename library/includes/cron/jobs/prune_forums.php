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

if ($bb_cfg['prune_enable']) {
  $sql = "SELECT forum_id, prune_days FROM " . BB_FORUMS . " WHERE prune_days != 0";

  foreach (DB()->fetch_rowset($sql) as $row) {
    \TorrentPier\Legacy\Admin\Common::topic_delete('prune', $row['forum_id'], (TIMENOW - 86400 * $row['prune_days']));
  }
}
