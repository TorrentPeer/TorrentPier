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

global $bb_cfg, $userdata, $lang;

if (!$group_id = (int)$this->request['group_id'] or !$group_info = \TorrentPier\Legacy\Group::get_group_data($group_id)) {
  $this->ajax_die($lang['NO_GROUP_ID_SPECIFIED']);
}

if (!$mode = (string)$this->request['mode']) {
  $this->ajax_die('Empty mode');
}

$value = $this->request['value'] = (string)(isset($this->request['value'])) ? $this->request['value'] : 0;

if (!IS_ADMIN && $userdata['user_id'] != $group_info['group_moderator']) {
  $this->ajax_die($lang['ONLY_FOR_MOD']);
}

switch ($mode) {
  case 'group_name':
  case 'group_signature':
  case 'group_description':
    $value = htmlCHR($value, false, ENT_NOQUOTES);
    $this->response['new_value'] = $value;
    break;

  case 'release_group':
  case 'group_type':
    $this->response['new_value'] = $value;
    break;

  case 'delete_avatar':
    \TorrentPier\Legacy\Avatar::deleteAvatar(GROUP_AVATAR_MASK . $group_id, $group_info['avatar_ext_id']);
    $value = 0;
    $this->response['group_avatar'] = \TorrentPier\Legacy\Avatar::getAvatar(true, GROUP_AVATAR_MASK . $group_id, $value);
    $mode = 'avatar_ext_id';
    break;

  default:
    $this->ajax_die("Invalid mode: $mode");
}

$value_sql = DB()->escape($value, true);
DB()->query("UPDATE " . BB_GROUPS . " SET $mode = $value_sql WHERE group_id = $group_id");
