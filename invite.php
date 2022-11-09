<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

define('BB_SCRIPT', 'invite');
require __DIR__ . '/common.php';

$user->session_start();

if (!$bb_cfg['new_user_reg_only_by_invite']) {
  bb_die($lang['REG_INVITES_DISABLE']);
}

if (!$userdata['session_logged_in']) {
  redirect(LOGIN_URL . "?redirect={$_SERVER['REQUEST_URI']}");
}

$page_title = $lang['INVITES'];
$template->assign_vars(['PAGE_TITLE' => $page_title]);
$profiledata = get_userdata($userdata['user_id']);
$btu = get_bt_userdata($userdata['user_id']);
$user_rating = (!empty($btu['u_down_total']) && $btu['u_down_total'] > MIN_DL_FOR_RATIO) ? round((($btu['u_up_total'] + $btu['u_up_release'] + $btu['u_up_bonus']) / $btu['u_down_total']), 0) : 0;
$regdate = $profiledata['user_regdate'];
$user_age = max(0, (date('Y') * 12) + date('n') - (date('Y', $regdate) * 12) - date('n', $regdate));
$date_end = time();
$date_start = $date_end - 604800;

// User group
$view_user_id = $profiledata['user_id'];
$groups = [];
$user_group = [];
$sql = '
	SELECT
		g.group_id,
		g.group_name,
		g.group_type
	FROM
		' . BB_USER_GROUP . ' as l,
		' . BB_GROUPS . ' as g
	WHERE
		l.user_pending = 0 AND
		g.group_single_user = 0 AND
		l.user_id =' . $view_user_id . ' AND
		g.group_id = l.group_id
	ORDER BY
		g.group_name,
		g.group_id';

if (!($result = DB()->sql_query($sql))) bb_die('Could not read groups' . __LINE__ . ',' . __FILE__ . $sql);
while ($group = DB()->sql_fetchrow($result)) $groups[] = $group;

$user_group[0] = '0';
if (count($groups) > 0) {
  $groupsw = TRUE;
  for ($i = 0; $i < count($groups); $i++) {
    $is_ok = false;
    //
    // groupe invisible ?
    if (($groups[$i]['group_type'] != GROUP_HIDDEN) || ($userdata['user_level'] == ADMIN)) {
      $is_ok = true;
    } else {
      $group_id = $groups[$i]['group_id'];
      $sql = 'SELECT * FROM ' . BB_USER_GROUP . ' WHERE group_id=' . $group_id . ' AND user_id=' . $userdata['user_id'] . ' AND user_pending=0';
      if (!($result = DB()->sql_query($sql))) bb_die('Could not obtain viewer group list' . __LINE__ . ',' . __FILE__ . $sql);
      $is_ok = ($group = DB()->sql_fetchrow($result));
    }
    // end if ($view_list[$i]['group_type'] == GROUP_HIDDEN)
    //
    // groupe visible : afficher
    if ($is_ok) {
      $user_group[$i + 1] = $groups[$i]['group_id'];
      $u_group_name = 'groupcp.php?g=' . $groups[$i]['group_id'];
      $l_group_name = $groups[$i]['group_name'];
      $user_group_name = $l_group_name;
      $template->assign_block_vars('groups', [
          'U_GROUP_NAME' => $u_group_name,
          'L_GROUP_NAME' => $l_group_name,
        ]
      );
    }  // end if ($is_ok)
  }  // end for ($i=0; $i < count($groups); $i++)
}  // end if (count($groups) > 0)
else {
  $groupsw = false;
}

if (isset($_GET['mode']) && $_GET['mode'] == 'getinvite') {
  $sql = 'SELECT COUNT(`invite_id`) AS `invites_count_week` FROM ' . BB_INVITES . ' WHERE `user_id`=' . $userdata['user_id'] . ' AND `generation_date`>=' . $date_start . ' AND `generation_date`<=' . $date_end;
  if (!($result = DB()->sql_query($sql))) bb_die('Could not get a list of invites' . __LINE__ . ',' . __FILE__ . $sql);
  $row = DB()->sql_fetchrowset($result);
  $num_row = DB()->num_rows($result);
  DB()->sql_freeresult($result);
  if ($num_row > 0) $invites_count_week = $row[0]['invites_count_week']; else $invites_count_week = 0;

  $sql = 'SELECT `invites_count` FROM ' . BB_INVITE_RULES . ' WHERE `user_rating`<=' . $user_rating . ' AND `user_age`<=' . $user_age . ' AND (';
  for ($i = 0; $i < count($user_group); $i++) {
    $sql = $sql . '`user_group`=' . $user_group[$i];
    if ($i < count($user_group) - 1) {
      $sql = $sql . ' OR ';
    }
  }
  $sql = $sql . ') ORDER BY `invites_count` DESC LIMIT 1';
  if (!($result = DB()->sql_query($sql))) bb_die('Could not get a list of rules for the invite' . __LINE__ . ',' . __FILE__ . $sql);
  $row = DB()->sql_fetchrowset($result);
  $num_row = DB()->num_rows($result);
  DB()->sql_freeresult($result);
  if ($num_row > 0) {
    if ($invites_count_week < $row[0]['invites_count']) {
      $invite_code = substr(md5(time()), rand(1, 14), 16);
      $sql = "INSERT INTO " . BB_INVITES . " (`invite_id`,`user_id`,`new_user_id`,`invite_code`,`active`,`generation_date`,`activation_date`) VALUES(null," . (int)$userdata['user_id'] . ",0,'" . $invite_code . "','1'," . time() . ",0)";
      if (!DB()->sql_query($sql)) {
        $message = $lang['CAN_GET_INVITE'] . '' . sprintf($lang['GO_TO_INVITE_LIST'], '<a href="invite.php">', '</a>');
        bb_die($message);
      } else {
        $message = $lang['INVITE_GET_SUCCESSFULLY'] . '' . sprintf($lang['GO_TO_INVITE_LIST'], '<a href="invite.php">', '</a>');
        bb_die($message);
      }
    }
  } else {
    $message = $lang['CAN_GET_INVITE'] . '' . sprintf($lang['GO_TO_INVITE_LIST'], '<a href="invite.php">', '</a>');
    bb_die($message);
  }
}

$referend_by_sql = 'SELECT * FROM ' . BB_INVITES . ' WHERE `new_user_id` =' . $userdata['user_id'];
if (!($referend_by_result = DB()->sql_query($referend_by_sql))) bb_die('Could not get a data of invites' . __LINE__ . ',' . __FILE__ . $referend_by_sql);
$referend_by_row = DB()->sql_fetchrow($referend_by_result);
$referend_by_user_data = get_userdata($referend_by_row['user_id']);
$num_referend_by_row = DB()->num_rows($referend_by_result);
if ($num_referend_by_row !== 0) {
  $template->assign_vars([
    'REFEREND' => true,]);
  $template->assign_block_vars('referend_by', [
    'REF_GENERATION_DATE' => date('d.m.Y H:i', $referend_by_row['generation_date']),
    'REF_INVITE_CODE' => $referend_by_row['invite_code'],
    'REF_USER' => '<a href="profile.php?mode=viewprofile&u=' . $referend_by_row['user_id'] . '">' . $referend_by_user_data['username'] . '</a>',
    'REF_ACTIVATION_DATE' => date('d.m.Y H:i', $referend_by_row['activation_date']),
  ]);
} else {
  $template->assign_vars([
    'REFEREND' => false,]);
}

$sql = 'SELECT * FROM ' . BB_INVITES . ' WHERE `user_id`=' . $userdata['user_id'] . ' ORDER BY `generation_date` DESC';
if (!($result = DB()->sql_query($sql))) bb_die('Could not get a list of invites' . __LINE__ . ',' . __FILE__ . $sql);
$invite_row = DB()->sql_fetchrowset($result);
$num_invite_row = DB()->num_rows($result);
DB()->sql_freeresult($result);
if ($num_invite_row > 0) {
  $template->assign_vars(['INVITES_PRESENT' => true]);
  for ($i = 0; $i < $num_invite_row; $i++) {
    $new_user_data = get_userdata($invite_row[$i]['new_user_id']);
    $template->assign_block_vars('invite_row', [
        'GENERATION_DATE' => date('d.m.Y H:i', $invite_row[$i]['generation_date']),
        'INVITE_CODE' => $invite_row[$i]['invite_code'],
        'ACTIVE' => ($invite_row[$i]['active'] == '1') ? $lang['YES'] : $lang['NO'],
        'NEW_USER' => ($invite_row[$i]['active'] == '1') ? '-' : '<a href="profile.php?mode=viewprofile&u=' . $invite_row[$i]['new_user_id'] . '">' . $new_user_data['username'] . '</a>',
        'ACTIVATION_DATE' => ($invite_row[$i]['active'] == '1') ? '-' : date('d.m.Y H:i', $invite_row[$i]['activation_date']),]
    );
  }
} else $template->assign_vars(['INVITES_PRESENT' => false]);

$sql = 'SELECT COUNT(`invite_id`) AS `invites_count_all` FROM ' . BB_INVITES . ' WHERE `user_id`=' . $userdata['user_id'];
if (!($result = DB()->sql_query($sql))) bb_die('Could not get a list of invites' . __LINE__ . ',' . __FILE__ . $sql);
$row = DB()->sql_fetchrowset($result);
$num_row = DB()->num_rows($result);
DB()->sql_freeresult($result);
if ($num_row > 0) $invites_count_all = $row[0]['invites_count_all']; else $invites_count_all = 0;

$sql = 'SELECT COUNT(`invite_id`) AS `invites_count_week` FROM ' . BB_INVITES . ' WHERE `user_id`=' . $userdata['user_id'] . ' AND `generation_date`>=' . $date_start . ' AND `generation_date`<=' . $date_end;
if (!($result = DB()->sql_query($sql))) bb_die('Could not get a list of invites' . __LINE__ . ',' . __FILE__ . $sql);
$row = DB()->sql_fetchrowset($result);
$num_row = DB()->num_rows($result);
DB()->sql_freeresult($result);
if ($num_row > 0) $invites_count_week = $row[0]['invites_count_week']; else $invites_count_week = 0;

$sql = 'SELECT `invites_count` FROM ' . BB_INVITE_RULES . ' WHERE `user_rating`<=' . $user_rating . ' AND `user_age`<=' . $user_age . ' AND (';
for ($i = 0; $i < count($user_group); $i++) {
  $sql = $sql . '`user_group`=' . $user_group[$i];
  if ($i < count($user_group) - 1) {
    $sql = $sql . ' OR ';
  }
}
$sql = $sql . ') ORDER BY `invites_count` DESC';

if (!($result = DB()->sql_query($sql))) bb_die('Could not get a list of rules for the invite' . __LINE__ . ',' . __FILE__ . $sql);
$row = DB()->sql_fetchrowset($result);
$num_row = DB()->num_rows($result);
DB()->sql_freeresult($result);
if ($num_row > 0) {
  $invites_may_get = $row[0]['invites_count'] - $invites_count_week;
  if ($invites_may_get > 0) $template->assign_vars(['CAN_INVITE' => false]); else $template->assign_vars(['CAN_INVITE' => true]);
} else {
  $invites_may_get = 0;
  $template->assign_vars(['CAN_INVITE' => true]);
}

$template->assign_vars([
    'USER_RATING' => $user_rating,
    'USER_AGE' => $user_age,
    'USER_GROUP' => $user_group[0],
    'INVITES_GETTED_ALL' => $invites_count_all,
    'INVITES_GETTED_WEEK' => $invites_count_week,
    'INVITES_MAY_GET' => $invites_may_get
  ]
);

$sql = 'SELECT * FROM ' . BB_INVITE_RULES . ' ORDER BY `invites_count`';
if (!($result = DB()->sql_query($sql))) bb_die('Could not get a list of rules for the invite' . __LINE__ . ',' . __FILE__ . $sql);
$rule_row = DB()->sql_fetchrowset($result);
$num_rule_row = DB()->num_rows($result);
DB()->sql_freeresult($result);
if ($num_rule_row > 0) {
  for ($i = 0; $i < $num_rule_row; $i++) {
    $template->assign_block_vars('rule_row', [
        'USER_RATING' => $rule_row[$i]['user_rating'],
        'USER_AGE' => $rule_row[$i]['user_age'],
        'USER_GROUP' => (groupname($select_name = false, $select_ary = false, $rule_row[$i]['user_group'])) ? (get_groupname($select_name = false, $select_ary = false, $rule_row[$i]['user_group'])) : $lang['USERS'],
        'INVITES_COUNT' => $rule_row[$i]['invites_count'],]
    );
  }
}

print_page('invite.tpl');
