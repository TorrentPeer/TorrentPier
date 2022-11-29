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
    $get_users = DB()->fetch_rowset("SELECT username, user_id, user_rank FROM " . BB_USERS . " WHERE user_session_time > $day AND  user_active = 1 ORDER BY username");

    if (!$get_users) {
      $html = $lang['USERS_TODAY_NONE'];
    } else {
      $user_count = 0;
      foreach ($get_users as $row) {
        $user_count++;
      }

      $html = $lang['USERS_TODAY'] . '&nbsp;(<b>' . $user_count . '</b>)' . ':&nbsp;';
      $users = [];
      foreach ($get_users as $rows) {
        $users[] = profile_url($rows);
      }

      $html .= implode(", ", $users);
    }
    break;

  default:
    $this->ajax_die("Invalid mode: $mode");
}

$this->response['html'] = $html;
$this->response['mode'] = $mode;
