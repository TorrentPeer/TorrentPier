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
$pm_text = $pm_main_nav = '';
$have_new_pm = $have_unread_pm = 0;

if ($userdata['user_new_privmsg']) {
  $have_new_pm = $userdata['user_new_privmsg'];
  $pm_info = declension($userdata['user_new_privmsg'], $lang['NEW_PMS_DECLENSION'], $lang['NEW_PMS_FORMAT']);

  if ($userdata['user_last_privmsg'] > $userdata['user_lastvisit'] && defined('IN_PM')) {
    $have_new_pm = ($userdata['user_new_privmsg'] > 1);
  }
  if (!$have_new_pm && $userdata['user_unread_privmsg']) {
    $pm_info = declension($userdata['user_unread_privmsg'], $lang['UNREAD_PMS_DECLENSION'], $lang['UNREAD_PMS_FORMAT']);
    $have_unread_pm = true;
  }
}

$privatemsgs = PM_URL . "?folder=inbox";
$read_pm = PM_URL . "?folder=inbox" . (($userdata['user_newest_pm_id'] && $userdata['user_new_privmsg'] == 1) ? "&mode=read&p={$userdata['user_newest_pm_id']}" : '');

if ($have_new_pm) {
  $pm_main_nav = 'new-pm'; // class name
}

if ($have_unread_pm || $have_new_pm) {
  $pm_text = '<a href="' . $read_pm . '" class="new-pm-link"><b>' . $lang['PRIVATE_MESSAGES'] . ': ' . $pm_info . '</b></a>';
} else {
  $pm_text = '<a href="' . $privatemsgs . '"><b>' . $lang['PRIVATE_MESSAGES'] . ': ' . $pm_info . '</b></a>';
}

$this->response['text'] = $pm_text;
$this->response['main_nav'] = $pm_main_nav;
