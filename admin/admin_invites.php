<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2023 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

if (!empty($setmodules)) {
  $filename = basename(__FILE__);
  $module['MODS']['INVITE_ADMIN_RULES'] = $filename . '?mode=rules';
  $module['MODS']['INVITE_ADMIN_HIST'] = $filename . '?mode=history';
  return;
}
require __DIR__ . '/pagestart.php';

if (isset($_POST['mode']) || isset($_GET['mode'])) {
  $mode = (isset($_POST['mode'])) ? $_POST['mode'] : $_GET['mode'];
}

if (isset($_POST['change_rule'])) {
  $rule_change_list = \TorrentPier\Legacy\AttachMod\Attach::get_var('rule_change_list', [0]);
  $rule_user_rating_list = \TorrentPier\Legacy\AttachMod\Attach::get_var('rule_user_rating_list', [0]);
  $rule_user_age_list = \TorrentPier\Legacy\AttachMod\Attach::get_var('rule_user_age_list', [0]);
  $rule_user_group_list = \TorrentPier\Legacy\AttachMod\Attach::get_var('rule_user_group_list', [0]);
  $rule_invites_count_list = \TorrentPier\Legacy\AttachMod\Attach::get_var('rule_invites_count_list', [0]);

  $rules = [];
  for ($i = 0; $i < sizeof($rule_change_list); $i++) {
    $rules['_' . $rule_change_list[$i]]['user_rating'] = intval($rule_user_rating_list[$i]);
    $rules['_' . $rule_change_list[$i]]['user_age'] = intval($rule_user_age_list[$i]);
    $rules['_' . $rule_change_list[$i]]['user_group'] = intval($rule_user_group_list[$i]);
    $rules['_' . $rule_change_list[$i]]['invites_count'] = intval($rule_invites_count_list[$i]);
  }

  $sql = 'SELECT * FROM ' . BB_INVITE_RULES . ' ORDER BY rule_id';
  if (!($result = DB()->sql_query($sql))) {
    bb_die('Could not get a list of rules for the Invite');
  }

  $num_rows = DB()->num_rows($result);
  $rule_row = DB()->sql_fetchrowset($result);
  DB()->sql_freeresult($result);

  if ($num_rows > 0) {
    for ($i = 0; $i < sizeof($rule_row); $i++) {
      if (intval($rule_row[$i]['user_rating']) != intval($rules['_' . $rule_row[$i]['rule_id']]['user_rating']) || intval($rule_row[$i]['user_age']) != intval($rules['_' . $rule_row[$i]['rule_id']]['user_age']) || intval($rule_row[$i]['user_group']) != intval($rules['_' . $rule_row[$i]['rule_id']]['user_group']) || intval($rule_row[$i]['invites_count']) != intval($rules['_' . $rule_row[$i]['rule_id']]['invites_count'])) {
        $sql_ary = [
          'user_rating' => (int)$rules['_' . $rule_row[$i]['rule_id']]['user_rating'],
          'user_age' => (int)$rules['_' . $rule_row[$i]['rule_id']]['user_age'],
          'user_group' => (int)$rules['_' . $rule_row[$i]['rule_id']]['user_group'],
          'invites_count' => (int)$rules['_' . $rule_row[$i]['rule_id']]['invites_count'],
        ];

        $sql = 'UPDATE ' . BB_INVITE_RULES . ' SET ' . \TorrentPier\Legacy\AttachMod\Attach::attach_mod_sql_build_array('UPDATE', $sql_ary) . ' WHERE `rule_id` = ' . (int)$rule_row[$i]['rule_id'];
        if (!DB()->sql_query($sql)) {
          bb_die('Could not save data');
        }
      }
    }
  }

  // Удаление правил
  $rule_id_list = \TorrentPier\Legacy\AttachMod\Attach::get_var('rule_id_list', [0]);
  $rule_id_sql = implode(', ', $rule_id_list);

  if ($rule_id_sql != '') {
    $sql = 'DELETE FROM ' . BB_INVITE_RULES . ' WHERE rule_id IN (' . $rule_id_sql . ')';
    if (!$result = DB()->sql_query($sql)) {
      bb_die('Could not delete rule');
    }
  }
}

if (isset($_POST['add_rule'])) {
  $rule_user_rating = \TorrentPier\Legacy\AttachMod\Attach::get_var('add_rule_user_rating', '');
  $rule_user_age = \TorrentPier\Legacy\AttachMod\Attach::get_var('add_rule_user_age', '');
  $rule_user_group = $_POST['add_rule_user_group']; //\TorrentPier\Legacy\AttachMod\Attach::get_var('add_rule_user_group', '');
  $rule_invites_count = \TorrentPier\Legacy\AttachMod\Attach::get_var('add_rule_invites_count', '');

  $sql_ary = [
    'user_rating' => (int)$rule_user_rating,
    'user_age' => (int)$rule_user_age,
    'user_group' => (int)$rule_user_group,
    'invites_count' => (int)$rule_invites_count
  ];

  $sql = 'INSERT INTO ' . BB_INVITE_RULES . ' ' . \TorrentPier\Legacy\AttachMod\Attach::attach_mod_sql_build_array('INSERT', $sql_ary);
  if (!DB()->sql_query($sql)) {
    bb_die('Could not add rule');
  }
}

switch ($mode) {
  case 'rules':
    $template->assign_vars([
        'TPL_INVITES_RULES' => true,
        'TPL_INVITES_HISTORY' => false,
        'S_ADD_GROUP_SELECT' => groupname('add_rule_user_group', '0', $groupid = false),
        'S_RULES_ACTION' => "admin_invites.php?mode=rules"
      ]
    );

    $sql = 'SELECT * FROM ' . BB_INVITE_RULES . ' ORDER BY `invites_count`';

    if (!($result = DB()->sql_query($sql))) {
      bb_die('Could not get a list of rules for the Invite');
    }

    $rule_row = DB()->sql_fetchrowset($result);
    $num_rule_row = DB()->num_rows($result);
    DB()->sql_freeresult($result);

    if ($num_rule_row > 0) {
      $rule_row = \TorrentPier\Legacy\AttachMod\Admin::sort_multi_array($rule_row, 'invites_count');

      for ($i = 0; $i < $num_rule_row; $i++) {
        $template->assign_block_vars('rule_row', [
            'RULE_ID' => $rule_row[$i]['rule_id'],
            'USER_RATING' => $rule_row[$i]['user_rating'],
            'USER_AGE' => $rule_row[$i]['user_age'],
            'USER_GROUP' => $rule_row[$i]['user_group'],
            'S_GROUP_SELECT' => groupname('rule_user_group_list[]', $rule_row[$i]['user_group'], $groupid = false),
            'INVITES_COUNT' => $rule_row[$i]['invites_count']
          ]
        );
      }
    }
    break;
  case 'history':
    $template->assign_vars([
        'TPL_INVITES_RULES' => false,
        'TPL_INVITES_HISTORY' => true
      ]
    );

    $sql = 'SELECT * FROM ' . BB_INVITES . ' ORDER BY `generation_date` DESC';
    if (!($result = DB()->sql_query($sql))) {
      bb_die('Could not get a list of invites');
    }

    $invite_row = DB()->sql_fetchrowset($result);
    $num_invite_row = DB()->num_rows($result);
    DB()->sql_freeresult($result);

    if ($num_invite_row > 0) {
      for ($i = 0; $i < $num_invite_row; $i++) {
        $user_data = get_userdata($invite_row[$i]['user_id']);
        $new_user_data = get_userdata($invite_row[$i]['new_user_id']);

        $template->assign_block_vars('invite_row', [
            'USER' => '<a href="http://' . $bb_cfg['server_name'] . $bb_cfg['script_path'] . 'profile.php?mode=viewprofile&u=' . $invite_row[$i]['user_id'] . '" target="_blank">' . $user_data['username'] . '</a>',
            'GENERATION_DATE' => date('d.m.Y H:i', $invite_row[$i]['generation_date']),
            'INVITE_CODE' => $invite_row[$i]['invite_code'],
            'ACTIVE' => ($invite_row[$i]['active'] == '1') ? $lang['YES'] : $lang['NO'],
            'NEW_USER' => ($invite_row[$i]['active'] == '1') ? '-' : '<a href="http://' . $bb_cfg['server_name'] . $bb_cfg['script_path'] . 'profile.php?mode=viewprofile&u=' . $invite_row[$i]['new_user_id'] . '" target="_blank">' . $new_user_data['username'] . '</a>',
            'ACTIVATION_DATE' => ($invite_row[$i]['active'] == '1') ? '-' : date('d.m.Y H:i', $invite_row[$i]['activation_date'])
          ]
        );
      }
    }
    break;
}

print_page('admin_invites.tpl', 'admin');
