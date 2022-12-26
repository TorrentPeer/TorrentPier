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
  case 'users_today':
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

  case 'birthday_week':
    $datastore->enqueue('stats');
    if (!$stats = $datastore->get('stats')) {
      $datastore->update('stats');
      $stats = $datastore->get('stats');
    }

    $users = [];

    if ($stats['birthday_week_list']) {
      foreach ($stats['birthday_week_list'] as $week) {
        $users[] = profile_url($week) . ' <span class="small">(' . birthday_age($week['user_birthday']) . ')</span>';
      }

      $html = sprintf($lang['BIRTHDAY_WEEK'], $bb_cfg['birthday_check_day'], implode(", ", $users));
    } else {
      $html = sprintf($lang['NOBIRTHDAY_WEEK'], $bb_cfg['birthday_check_day']);
    }
    break;

  case 'birthday_today':
    $datastore->enqueue('stats');
    if (!$stats = $datastore->get('stats')) {
      $datastore->update('stats');
      $stats = $datastore->get('stats');
    }

    $users = [];

    if ($stats['birthday_today_list']) {
      foreach ($stats['birthday_today_list'] as $today) {
        $users[] = profile_url($today) . ' <span class="small">(' . birthday_age($today['user_birthday']) . ')</span>';
      }

      $html = $lang['BIRTHDAY_TODAY'] . implode(", ", $users);
    } else {
      $html = $lang['NOBIRTHDAY_TODAY'];
    }
    break;

  case 'get_forum_mods':
    $forum_id = (int)$this->request['forum_id'];

    $datastore->enqueue([
      'moderators',
      'cat_forums',
    ]);

    $moderators = [];
    $mod = $datastore->get('moderators');

    if (isset($mod['mod_users'][$forum_id])) {
      foreach ($mod['mod_users'][$forum_id] as $user_id) {
        $moderators[] = '<a href="' . PROFILE_URL . $user_id . '">' . $mod['name_users'][$user_id] . '</a>';
      }
    }

    if (isset($mod['mod_groups'][$forum_id])) {
      foreach ($mod['mod_groups'][$forum_id] as $group_id) {
        $moderators[] = '<a href="' . "group.php?" . POST_GROUPS_URL . "=" . $group_id . '">' . $mod['name_groups'][$group_id] . '</a>';
      }
    }

    $html = ':&nbsp;';
    $html .= ($moderators) ? implode(', ', $moderators) : $lang['NONE'];
    unset($moderators, $mod);
    $datastore->rm('moderators');
    break;

  case 'change_tz':
    $tz = (int)$this->request['tz'];
    if ($tz < -12) {
      $tz = -12;
    }
    if ($tz > 13) {
      $tz = 13;
    }
    if ($tz != $bb_cfg['board_timezone']) {
      // Set current user timezone
      DB()->query("UPDATE " . BB_USERS . " SET user_timezone = $tz WHERE user_id = " . $userdata['user_id']);
      $bb_cfg['board_timezone'] = $tz;
      \TorrentPier\Legacy\Sessions::cache_rm_user_sessions($userdata['user_id']);
    }
    break;

  case 'get_traf_stats':
    $user_id = (int)$this->request['user_id'];
    $btu = get_bt_userdata($user_id);
    $profiledata = get_userdata($user_id);

    $speed_up = ($btu['speed_up']) ? \TorrentPier\Legacy\Filesystem::humn_size($btu['speed_up']) . '/s' : '0 KB/s';
    $speed_down = ($btu['speed_down']) ? \TorrentPier\Legacy\Filesystem::humn_size($btu['speed_down']) . '/s' : '0 KB/s';
    $user_ratio = ($btu['u_down_total'] > MIN_DL_FOR_RATIO) ? '<b class="gen">' . get_bt_ratio($btu) . '</b>' : $lang['IT_WILL_BE_DOWN'] . ' <b>' . \TorrentPier\Legacy\Filesystem::humn_size(MIN_DL_FOR_RATIO) . '</b>';

    $html = '
			<tr class="row3">
				<th style="padding: 0;"></th>
				<th>' . $lang['DOWNLOADED'] . '</th>
				<th>' . $lang['UPLOADED'] . '</th>
				<th>' . $lang['RELEASED'] . '</th>
				<th>' . $lang['BONUS'] . '</th>';
    $html .= ($bb_cfg['seed_bonus_enabled']) ? '<th>' . $lang['SEED_BONUS'] . '</th>' : '';
    $html .= '</tr>
			<tr class="row1">
				<td>' . $lang['TOTAL_TRAF'] . '</td>
				<td id="u_down_total"><span class="editable bold leechmed">' . \TorrentPier\Legacy\Filesystem::humn_size($btu['u_down_total']) . '</span></td>
				<td id="u_up_total"><span class="editable bold seedmed">' . \TorrentPier\Legacy\Filesystem::humn_size($btu['u_up_total']) . '</span></td>
				<td id="u_up_release"><span class="editable bold seedmed">' . \TorrentPier\Legacy\Filesystem::humn_size($btu['u_up_release']) . '</span></td>
				<td id="u_up_bonus"><span class="editable bold seedmed">' . \TorrentPier\Legacy\Filesystem::humn_size($btu['u_up_bonus']) . '</span></td>';
    $html .= ($bb_cfg['seed_bonus_enabled']) ? '<td id="user_points"><span class="editable bold points">' . $profiledata['user_points'] . '</b></td>' : '';
    $html .= '</tr>
			<tr class="row5">
				<td colspan="1">' . $lang['MAX_SPEED'] . '</td>
				<td colspan="2">' . $lang['DL_DL_SPEED'] . ': ' . $speed_down . '</span></td>
				<td colspan="2">' . $lang['DL_UL_SPEED'] . ': ' . $speed_up . '</span></td>';
    $html .= ($bb_cfg['seed_bonus_enabled']) ? '<td colspan="1"></td>' : '';
    $html .= '</tr>';

    $this->response['user_ratio'] = '
			<th><a href="' . $bb_cfg['ratio_url_help'] . '" class="bold">' . $lang['USER_RATIO'] . '</a>:</th>
			<td>' . $user_ratio . '</td>
		';
    break;

  default:
    $this->ajax_die("Invalid mode: $mode");
}

$this->response['html'] = $html;
$this->response['mode'] = $mode;
