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

global $userdata, $bb_cfg, $lang, $datastore;

$mode = (string)$this->request['mode'];

switch ($mode) {
  case 'tor_status':
    $topics = (string)$this->request['topic_ids'];
    $status = (int)$this->request['status'];

    // Валидность статуса
    if (!isset($lang['TOR_STATUS_NAME'][$status])) {
      $this->ajax_die($lang['STATUS_DOES_EXIST'] . $status);
    }

    $topic_ids = DB()->fetch_rowset("SELECT attach_id FROM " . BB_BT_TORRENTS . " WHERE topic_id IN($topics)", 'attach_id');

    foreach ($topic_ids as $attach_id) {
      \TorrentPier\Legacy\Torrent::change_tor_status($attach_id, $status);
    }
    $this->response['status'] = $bb_cfg['tor_icons'][$status];
    $this->response['topics'] = explode(',', $topics);
    break;

  case 'edit_topic_title':
    $topic_id = (int)$this->request['topic_id'];
    $topic_title = (string)$this->request['topic_title'];
    $new_title = clean_title($topic_title);

    if (!$topic_id) {
      $this->ajax_die($lang['INVALID_TOPIC_ID']);
    }
    if ($new_title == '') {
      $this->ajax_die($lang['DONT_MESSAGE_TITLE']);
    }

    if (!$t_data = DB()->fetch_row("SELECT forum_id FROM " . BB_TOPICS . " WHERE topic_id = $topic_id LIMIT 1")) {
      $this->ajax_die($lang['INVALID_TOPIC_ID_DB']);
    }
    $this->verify_mod_rights($t_data['forum_id']);

    $topic_title_sql = DB()->escape($new_title);

    DB()->query("UPDATE " . BB_TOPICS . " SET topic_title = '$topic_title_sql' WHERE topic_id = $topic_id");

    // Обновление кеша новостей на главной
    $news_forums = array_flip(explode(',', $bb_cfg['latest_news_forum_id']));
    if (isset($news_forums[$t_data['forum_id']]) && $bb_cfg['show_latest_news']) {
      $datastore->enqueue('latest_news');
      $datastore->update('latest_news');
    }

    $net_forums = array_flip(explode(',', $bb_cfg['network_news_forum_id']));
    if (isset($net_forums[$t_data['forum_id']]) && $bb_cfg['show_network_news']) {
      $datastore->enqueue('network_news');
      $datastore->update('network_news');
    }

    $this->response['topic_id'] = $topic_id;
    $this->response['topic_title'] = $new_title;
    break;

  case 'profile_ip':
    $user_id = (int)$this->request['user_id'];
    $profiledata = get_userdata($user_id);

    if (!$user_id) {
      $this->ajax_die($lang['NO_USER_ID_SPECIFIED']);
    }

    $reg_ip = DB()->fetch_rowset("SELECT username, user_id, user_rank FROM " . BB_USERS . "
			WHERE user_reg_ip = '{$profiledata['user_reg_ip']}'
				AND user_reg_ip != 0
				AND user_id != {$profiledata['user_id']}
			ORDER BY username ASC");

    $last_ip = DB()->fetch_rowset("SELECT username, user_id, user_rank FROM " . BB_USERS . "
			WHERE user_last_ip = '{$profiledata['user_last_ip']}'
				AND user_last_ip != 0
				AND user_id != {$profiledata['user_id']}");

    $link_reg_ip = $link_last_ip = '';

    if (!empty($reg_ip)) {
      $link_reg_ip .= $lang['OTHER_IP'] . ' ';
      foreach ($reg_ip as $row) {
        $link_reg_ip .= profile_url($row) . ' ';
      }
    }

    if (!empty($last_ip)) {
      $link_last_ip .= $lang['OTHER_IP'] . ' ';
      foreach ($last_ip as $row) {
        $link_last_ip .= profile_url($row) . ' ';
      }
    }

    if ($profiledata['user_level'] == ADMIN && !IS_ADMIN) {
      $reg_ip = $last_ip = $lang['HIDDEN'];
    } elseif ($profiledata['user_level'] == MOD && IS_MOD) {
      $reg_ip = $last_ip = $lang['HIDDEN'];
    } else {
      $user_reg_ip = \TorrentPier\Helpers\IPHelper::decode_ip($profiledata['user_reg_ip']);
      $user_last_ip = \TorrentPier\Helpers\IPHelper::decode_ip($profiledata['user_last_ip']);
      $reg_ip = '<a href="' . $bb_cfg['whois_info'] . $user_reg_ip . '" class="gen" target="_blank">' . $user_reg_ip . '</a>';
      $last_ip = '<a href="' . $bb_cfg['whois_info'] . $user_last_ip . '" class="gen" target="_blank">' . $user_last_ip . '</a>';
    }

    $this->response['ip_list_html'] = '
			<br /><table class="mod_ip bCenter borderless" cellspacing="1">
				<tr class="row5" >
					<td>' . $lang['REG_IP'] . '</td>
					<td class="tCenter">' . $reg_ip . '</td>
					<td><div>' . $link_reg_ip . '</div></td>
				</tr>
				<tr class="row4">
					<td>' . $lang['LAST_IP'] . '</td>
					<td class="tCenter">' . $last_ip . '</td>
					<td><div>' . $link_last_ip . '</div></td>
				</tr>
			</table><br />
		';
    break;

  case 'edit_topic_id':
    $topic_id = (int)$this->request['topic_id'];
    $new_data = (string)$this->request['data'];

    if (!$topic_id) {
      $this->ajax_die($lang['INVALID_TOPIC_ID']);
    }

    if (!\TorrentPier\Helpers\IsHelper::is_num($new_data)) {
      $this->ajax_die($lang['WRONG_INPUT']);
    }

    if (!$t_data = DB()->fetch_row("SELECT forum_id FROM " . BB_TOPICS . " WHERE topic_id = $topic_id LIMIT 1")) {
      $this->ajax_die($lang['INVALID_TOPIC_ID_DB']);
    }
    $this->verify_mod_rights($t_data['forum_id']);

    if (DB()->fetch_rowset("SELECT topic_id FROM " . BB_TOPICS . " WHERE topic_id = $new_data", 'topic_id')) {
      $this->ajax_die($lang['POST_TAKEN']);
    }

    DB()->query("UPDATE " . BB_TOPICS . " SET topic_id = $new_data WHERE topic_id = $topic_id");
    DB()->query("UPDATE " . BB_TOPICS_WATCH . " SET topic_id = $new_data WHERE topic_id = $topic_id");
    DB()->query("UPDATE " . BB_POSTS . " SET topic_id = $new_data WHERE topic_id = $topic_id");
    DB()->query("UPDATE " . BB_BT_TORRENTS . " SET topic_id = $new_data WHERE topic_id = $topic_id");
    DB()->query("UPDATE " . BB_BT_TRACKER . " SET topic_id = $new_data WHERE topic_id = $topic_id");
    DB()->query("UPDATE " . BB_BT_TRACKER_SNAP . " SET topic_id = $new_data WHERE topic_id = $topic_id");
    DB()->query("UPDATE " . BB_BT_TOR_DL_STAT . " SET topic_id = $new_data WHERE topic_id = $topic_id");
    DB()->query("UPDATE " . BB_BT_TORSTAT . " SET topic_id = $new_data WHERE topic_id = $topic_id");

    \TorrentPier\Legacy\Admin\Common::sync('topic', [$topic_id, $new_data]);

    $this->response['edit_topic_id'] = $new_data;
    break;

  case 'edit_topic_author':
    $topic_id = (int)$this->request['topic_id'];
    $new_data = (string)$this->request['data'];

    if (!$topic_id) {
      $this->ajax_die($lang['INVALID_TOPIC_ID']);
    }

    if (!$new_data = get_userdata($new_data)) {
      $this->ajax_die($lang['NO_USER_ID_SPECIFIED']);
    }

    if (!$t_data = DB()->fetch_row("SELECT forum_id FROM " . BB_TOPICS . " WHERE topic_id = $topic_id LIMIT 1")) {
      $this->ajax_die($lang['INVALID_TOPIC_ID_DB']);
    }
    $this->verify_mod_rights($t_data['forum_id']);

    $old_user = DB()->fetch_rowset("SELECT topic_poster FROM " . BB_TOPICS . " WHERE topic_id = $topic_id", 'topic_poster');

    DB()->query("UPDATE " . BB_TOPICS . " SET topic_poster = {$new_data['user_id']} WHERE topic_id = $topic_id");
    DB()->query("UPDATE " . BB_POSTS . " SET poster_id = {$new_data['user_id']}  WHERE topic_id = $topic_id");
    DB()->query("UPDATE " . BB_BT_TORRENTS . " SET poster_id = {$new_data['user_id']}  WHERE topic_id = $topic_id");
    DB()->query("UPDATE " . BB_BT_TRACKER . " SET user_id = {$new_data['user_id']}  WHERE topic_id = $topic_id");
    DB()->query("UPDATE " . BB_BT_TOR_DL_STAT . " SET user_id = {$new_data['user_id']} WHERE topic_id = $topic_id");
    DB()->query("UPDATE " . BB_BT_TORSTAT . " SET user_id = {$new_data['user_id']}  WHERE topic_id = $topic_id");

    \TorrentPier\Legacy\Admin\Common::sync('user_posts', $old_user);
    \TorrentPier\Legacy\Admin\Common::sync('user_posts', $new_data['user_id']);

    $this->response['edit_topic_author'] = true;
    break;

  default:
    $this->ajax_die("Invalid mode: $mode");
}
