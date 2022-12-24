<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

if (!defined('IN_AJAX')) {
  die(basename(__FILE__));
}

global $bb_cfg, $lang, $userdata, $datastore;

$mode = (string)$this->request['mode'];
$html = '';

switch ($mode) {
  case 'list':
    $day = TIMENOW - (date('H', TIMENOW) * 60 * 60) - (date('i', TIMENOW) * 60) - date('s', TIMENOW);
    $get_users = DB()->fetch_rowset("SELECT username, user_id, user_rank, user_opt FROM " . BB_USERS . " WHERE user_session_time > $day AND  user_active = 1 ORDER BY username");

    $user_count = 0;
    $users = [];

    foreach ($get_users as $rows) {
      if ($rows['user_id'] == $userdata['user_id'] || !bf($rows['user_opt'], 'user_opt', 'user_viewonline')) {
        $users[] = profile_url($rows);
        $user_count++;
      }
    }

    if ($user_count != 0 && !empty($users)) {
      $html = $lang['USERS_TODAY'] . '&nbsp;(<b>' . $user_count . '</b>)' . ':&nbsp;' . implode(", ", $users);
    } else {
      $html = $lang['USERS_TODAY_NONE'];
    }
    break;

  default:
    $this->ajax_die("Invalid mode: $mode");
}

$this->response['html'] = $html;
$this->response['mode'] = $mode;
