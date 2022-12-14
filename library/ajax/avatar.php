<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2023 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

if (!defined('IN_AJAX')) {
  die(basename(__FILE__));
}

global $bb_cfg, $lang, $user;

$mode = (string)$this->request['mode'];
$user_id = (int)$this->request['user_id'];

if (!$user_id or !$u_data = get_userdata($user_id)) {
  $this->ajax_die($lang['NO_USER_ID_SPECIFIED']);
}

if (!IS_ADMIN && $user_id != $user->id) {
  $this->ajax_die($lang['NOT_ADMIN']);
}

$response = '';

switch ($mode) {
  case 'delete':
    \TorrentPier\Legacy\Avatar::deleteAvatar($user_id, $u_data['avatar_ext_id']);
    $new_ext_id = 0;
    $response = \TorrentPier\Legacy\Avatar::getAvatar(false, $user_id, $new_ext_id);
    break;
  default:
    $this->ajax_die("Invalid mode: $mode");
}

DB()->query("UPDATE " . BB_USERS . " SET avatar_ext_id = $new_ext_id WHERE user_id = $user_id");

\TorrentPier\Legacy\Sessions::cache_rm_user_sessions($user_id);

$this->response['avatar_html'] = $response;
