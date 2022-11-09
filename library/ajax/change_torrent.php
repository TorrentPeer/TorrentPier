<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

if (!defined('IN_AJAX')) {
  \TorrentPier\Legacy\Dev::error_message(basename(__FILE__));
}

global $userdata, $bb_cfg, $lang;

if (!isset($this->request['attach_id'])) {
  $this->ajax_die($lang['EMPTY_ATTACH_ID']);
}
if (!isset($this->request['type'])) {
  $this->ajax_die('Empty type');
}
$attach_id = (int)$this->request['attach_id'];
$type = (string)$this->request['type'];

$torrent = DB()->fetch_row("
		SELECT
			a.post_id, d.physical_filename, d.extension, d.tracker_status,
			t.topic_first_post_id,
			p.poster_id, p.topic_id, p.forum_id,
			f.allow_reg_tracker
		FROM
			" . BB_ATTACHMENTS . " a,
			" . BB_ATTACHMENTS_DESC . " d,
			" . BB_POSTS . " p,
			" . BB_TOPICS . " t,
			" . BB_FORUMS . " f
		WHERE
			    a.attach_id = $attach_id
			AND d.attach_id = $attach_id
			AND p.post_id = a.post_id
			AND t.topic_id = p.topic_id
			AND f.forum_id = p.forum_id
		LIMIT 1
	");

if (!$torrent) {
  $this->ajax_die($lang['INVALID_ATTACH_ID']);
}

if ($torrent['poster_id'] == $userdata['user_id'] && !IS_AM) {
  switch ($type) {
    case 'del_torrent':
    case 'reg':
    case 'unreg':
      break;
    default:
      $this->ajax_die($lang['ONLY_FOR_MOD']);
  }
} elseif (!IS_AM) {
  $this->ajax_die($lang['ONLY_FOR_MOD']);
}

$title = $url = '';
switch ($type) {
  case 'set_gold':
  case 'set_silver':
  case 'unset_silver_gold':
    if ($type == 'set_silver') {
      $tor_type = TOR_TYPE_SILVER;
    } elseif ($type == 'set_gold') {
      $tor_type = TOR_TYPE_GOLD;
    } else {
      $tor_type = 0;
    }
    \TorrentPier\Legacy\Torrent::change_tor_type($attach_id, $tor_type);
    $title = $lang['CHANGE_TOR_TYPE'];
    $url = make_url(TOPIC_URL . $torrent['topic_id']);
    break;

  case 'reg':
    \TorrentPier\Legacy\Torrent::tracker_register($attach_id);
    $url = (TOPIC_URL . $torrent['topic_id']);
    break;

  case 'unreg':
    \TorrentPier\Legacy\Torrent::tracker_unregister($attach_id);
    $url = (TOPIC_URL . $torrent['topic_id']);
    break;

  case 'del_torrent':
    if (empty($this->request['confirmed'])) {
      $this->prompt_for_confirm($lang['DEL_TORRENT']);
    }
    \TorrentPier\Legacy\Torrent::delete_torrent($attach_id);
    $url = make_url(TOPIC_URL . $torrent['topic_id']);
    break;

  case 'del_torrent_move_topic':
    if (empty($this->request['confirmed'])) {
      $this->prompt_for_confirm($lang['DEL_MOVE_TORRENT']);
    }
    \TorrentPier\Legacy\Torrent::delete_torrent($attach_id);
    $url = make_url("modcp.php?t={$torrent['topic_id']}&mode=move&sid={$userdata['session_id']}");
    break;

  default:
    $this->ajax_die("Invalid type: $type");
}

$this->response['url'] = $url;
$this->response['title'] = $title;
