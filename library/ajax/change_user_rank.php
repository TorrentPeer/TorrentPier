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

global $datastore, $lang;

$ranks = $datastore->get('ranks');
$rank_id = (int)$this->request['rank_id'];

if (!$user_id = (int)$this->request['user_id'] or !$profiledata = get_userdata($user_id)) {
  $this->ajax_die($lang['NO_USER_ID_SPECIFIED']);
}

if ($rank_id != 0 && !isset($ranks[$rank_id])) {
  $this->ajax_die("Invalid rank_id: $rank_id");
}

DB()->query("UPDATE " . BB_USERS . " SET user_rank = $rank_id WHERE user_id = $user_id");

\TorrentPier\Legacy\Sessions::cache_rm_user_sessions($user_id);

$user_rank = ($rank_id) ? '<span class="' . $ranks[$rank_id]['rank_style'] . '">' . $ranks[$rank_id]['rank_title'] . '</span>' : $lang['USER'];
$rank_image = ($rank_id && $ranks[$rank_id]['rank_image']) ? '<img src="' . $ranks[$rank_id]['rank_image'] . '" alt="" title="" border="0" />' : '';

$this->response['html'] = ($rank_id) ? $lang['AWARDED_RANK'] . "<b> $user_rank </b>" : $lang['SHOT_RANK'];
$this->response['rank_name'] = $user_rank;
$this->response['rank_image'] = $rank_image;
