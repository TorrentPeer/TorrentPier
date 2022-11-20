<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

if (!defined('BB_ROOT')) {
  die(basename(__FILE__));
}

set_die_append_msg();

if (!$bb_cfg['emailer']['enabled']) {
  bb_die($lang['EMAILER_DISABLED']);
}

$need_captcha = ($_GET['mode'] == 'sendpassword' && !IS_ADMIN && !$bb_cfg['captcha']['disabled']);

if (isset($_POST['submit'])) {
  if ($need_captcha && !bb_captcha('check')) {
    bb_die($lang['CAPTCHA_WRONG']);
  }
  $email = (!empty($_POST['email'])) ? trim(strip_tags(htmlspecialchars($_POST['email']))) : '';
  $sql = "SELECT * FROM " . BB_USERS . " WHERE user_email = '" . DB()->escape($email) . "'";
  if ($result = DB()->sql_query($sql)) {
    if ($row = DB()->sql_fetchrow($result)) {
      if (!$row['user_active']) {
        bb_die($lang['NO_SEND_ACCOUNT_INACTIVE']);
      }
      if (in_array($row['user_level'], [MOD, ADMIN])) {
        bb_die($lang['NO_SEND_ACCOUNT']);
      }

      $username = $row['username'];
      $user_id = $row['user_id'];

      $user_actkey = \TorrentPier\Helpers\BaseHelper::make_rand_str(ACTKEY_LENGHT);
      $user_password = \TorrentPier\Helpers\BaseHelper::make_rand_str(NEWPASSWD_LENGTH);

      $sql = "UPDATE " . BB_USERS . "
				SET user_newpasswd = '$user_password', user_actkey = '$user_actkey'
				WHERE user_id = " . $row['user_id'];
      if (!DB()->sql_query($sql)) {
        bb_die('Could not update new password information');
      }

      /** @var TorrentPier\Legacy\Emailer() $emailer */
      $emailer = new TorrentPier\Legacy\Emailer();

      $emailer->set_from($bb_cfg['board_email']);
      $emailer->set_to($row['user_email']);
      $emailer->set_subject($lang['EMAILER_SUBJECT']['USER_ACTIVATE_PASSWD']);

      $emailer->set_template('user_activate_passwd', $row['user_lang']);
      $emailer->assign_vars([
        'SITENAME' => $bb_cfg['sitename'],
        'USERNAME' => $username,
        'PASSWORD' => $user_password,
        'U_ACTIVATE' => make_url('profile.php?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey)
      ]);

      $emailer->send();

      bb_die($lang['PASSWORD_UPDATED']);
    } else {
      bb_die($lang['NO_EMAIL_MATCH']);
    }
  } else {
    bb_die('Could not obtain user information for sendpassword');
  }
} else {
  $email = $username = '';
}

$template->assign_vars([
  'USERNAME' => $username,
  'EMAIL' => $email,
  'CAPTCHA_HTML' => ($need_captcha) ? bb_captcha('get') : '',
  'S_HIDDEN_FIELDS' => '',
  'S_PROFILE_ACTION' => "profile.php?mode=sendpassword",
]);

print_page('usercp_sendpasswd.tpl');
