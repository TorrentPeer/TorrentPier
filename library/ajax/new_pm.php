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

global $lang, $userdata, $bb_cfg;

if (!$bb_cfg['pm_dynamic']['enabled']) {
  $this->ajax_die($lang['MODULE_OFF']);
}

$pm_info = $lang['NO_NEW_PM'];
$pm_text = '';
$have_new_pm = $have_unread_pm = 0;

$logged_in = (int)!empty($userdata['session_logged_in']);

if ($logged_in) {
  if ($userdata['user_new_privmsg']) {
    $have_new_pm = $userdata['user_new_privmsg'];
    $pm_info = declension($userdata['user_new_privmsg'], $lang['NEW_PMS_DECLENSION'], $lang['NEW_PMS_FORMAT']);

    if ($userdata['user_last_privmsg'] > $userdata['user_lastvisit'] && defined('IN_PM')) {
      $userdata['user_last_privmsg'] = $userdata['user_lastvisit'];

      \TorrentPier\Legacy\Sessions::db_update_userdata($userdata, [
        'user_last_privmsg' => $userdata['user_lastvisit'],
      ]);

      $have_new_pm = ($userdata['user_new_privmsg'] > 1);
    }
  }
  if (!$have_new_pm && $userdata['user_unread_privmsg']) {
    // sync unread pm count
    if (defined('IN_PM')) {
      $row = DB()->fetch_row("
				SELECT COUNT(*) AS pm_count
				FROM " . BB_PRIVMSGS . "
				WHERE privmsgs_to_userid = " . $userdata['user_id'] . "
					AND privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . "
				GROUP BY privmsgs_to_userid
			");

      $real_unread_pm_count = (int)$row['pm_count'];

      if ($userdata['user_unread_privmsg'] != $real_unread_pm_count) {
        $userdata['user_unread_privmsg'] = $real_unread_pm_count;

        \TorrentPier\Legacy\Sessions::db_update_userdata($userdata, [
          'user_unread_privmsg' => $real_unread_pm_count,
        ]);
      }
    }

    $pm_info = declension($userdata['user_unread_privmsg'], $lang['UNREAD_PMS_DECLENSION'], $lang['UNREAD_PMS_FORMAT']);
    $have_unread_pm = true;
  }
}

$privatemsgs = PM_URL . "?folder=inbox";
$read_pm = PM_URL . "?folder=inbox" . (($userdata['user_newest_pm_id'] && $userdata['user_new_privmsg'] == 1) ? "&mode=read&p={$userdata['user_newest_pm_id']}" : '');

if ($have_new_pm || $have_unread_pm) {
  $pm_text = '<a href="' . $read_pm . '" class="new-pm-link"><b>' . $lang['PRIVATE_MESSAGES'] . ': ' . $pm_info . '</b></a>';
} else {
  $pm_text = '<a href="' . $privatemsgs . '"><b>' . $lang['PRIVATE_MESSAGES'] . ': ' . $pm_info . '</b></a>';
}

$this->response['text'] = $pm_text;
$this->response['msg_count'] = (int)$have_new_pm;
