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

global $bb_cfg, $lang, $userdata;

if ($bb_cfg['reputation']['disabled']) {
  $this->ajax_die($lang['MODULE_OFF']);
}

$user_id = (int)$this->request['user_id'];
$mode = (string)$this->request['mode'];
$repa = $userdata['user_reputation'];

if (!$user_id) {
  $this->ajax_die($lang['NO_USER_ID_SPECIFIED']);
}

if ($userdata['user_id'] == $user_id) {
  $this->ajax_die($lang['REPUTATION_SELF']);
}

$row = DB()->fetch_row("SELECT user_id FROM " . BB_REPUTATION . " WHERE user_id = $user_id AND poster_id = " . $userdata['user_id'] . " AND time > " . (TIMENOW - 86400));
if ($row) {
  $this->ajax_die($lang['REPUTATION_AGAIN']);
}

if (!IS_ADMIN) {
  if ($repa < $bb_cfg['reputation']['min_repa_out']) {
    $this->ajax_die($lang['REPUTATION_CANT']);
  }

  if ($userdata['user_posts'] < $bb_cfg['reputation']['min_posts']) {
    $this->ajax_die(sprintf($lang['REPUTATION_LOW_POSTS'], $bb_cfg['reputation']['min_posts']));
  }

  $row = DB()->fetch_row("SELECT COUNT(user_id) AS count FROM " . BB_REPUTATION . " WHERE poster_id = " . $userdata['user_id'] . " AND time > " . (TIMENOW - 86400));
  if ($row['count'] > $bb_cfg['reputation']['vote']) {
    $this->ajax_die(sprintf($lang['REPUTATION_COUNT'], declension($bb_cfg['reputation']['vote'], 'times')));
  }

  if (!IS_MOD && $bb_cfg['reputation']['ratio']['enabled']) {
    if (!$btu = get_bt_userdata($userdata['user_id'])) {
      \TorrentPier\Legacy\Torrent::generate_passkey($userdata['user_id'], true);
      $btu = get_bt_userdata($userdata['user_id']);
    }
    if (get_bt_ratio($btu) < $bb_cfg['reputation']['ratio']['min_ratio']) {
      $this->ajax_die(sprintf($lang['REPUTATION_LOW_RATIO'], $bb_cfg['reputation']['ratio']['min_ratio']));
    }
  }
}

$repa_out = '1';
if (IS_AM) {
  if ($userdata['user_level'] == MOD) {
    $repa_out = '5';
  }
  if ($userdata['user_level'] == ADMIN) {
    $repa_out = '10';
  }
}

$row = DB()->fetch_row("SELECT user_level, user_reputation FROM " . BB_USERS . " WHERE user_id = $user_id LIMIT 1");
switch ($mode) {
  case 'add':
    $to_add_repa = ($row['user_reputation'] + $repa_out);

    $repa_out = ($to_add_repa > $bb_cfg['reputation']['max_repa']) ? $this->ajax_die($lang['REPUTATION_TOO_HIGH']) : $repa_out;

    if (!IS_ADMIN) {
      DB()->query("INSERT INTO " . BB_REPUTATION . " (user_id, poster_id, mode, time) VALUES ('$user_id', '" . $userdata['user_id'] . "', '$mode', '" . TIMENOW . "')");
    }
    DB()->query("UPDATE " . BB_USERS . " SET user_reputation = $to_add_repa WHERE user_id = $user_id");

    $this->response['style'] = ($to_add_repa < 0) ? 'red' : 'green';
    $this->response['html'] = $to_add_repa;
    break;
  case 'del':
    $to_remove_repa = ($row['user_reputation'] - $repa_out);

    if (($row['user_level'] == ADMIN) || ($row['user_level'] == MOD)) {
      $repa_out = ($to_remove_repa < 0) ? $this->ajax_die($lang['REPUTATION_TOO_LOW']) : $repa_out;
    }
    $repa_out = ($to_remove_repa < $bb_cfg['reputation']['min_repa']) ? $this->ajax_die($lang['REPUTATION_TOO_LOW']) : $repa_out;

    if (!IS_ADMIN) {
      DB()->query("INSERT INTO " . BB_REPUTATION . " (user_id, poster_id, mode, time) VALUES ('$user_id', '" . $userdata['user_id'] . "', '$mode', '" . TIMENOW . "')");
    }
    DB()->query("UPDATE " . BB_USERS . " SET user_reputation = $to_remove_repa WHERE user_id = $user_id");

    $this->response['style'] = ($to_remove_repa < 0) ? 'red' : 'green';
    $this->response['html'] = $to_remove_repa;
    break;
  default:
    $this->ajax_die("Invalid mode: $mode");
}

$this->response['user_id'] = $user_id;
