<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2023 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

if (!empty($setmodules)) {
  $module['MODS']['NOTICES_SYSTEM'] = basename(__FILE__);
  return;
}
require __DIR__ . '/pagestart.php';

if (isset($_GET['mode']) || isset($_POST['mode'])) {
  $mode = $_GET['mode'] ?? $_POST['mode'];
} else {
  //
  // These could be entered via a form button
  //
  if (isset($_POST['add'])) {
    $mode = 'add';
  } elseif (isset($_POST['save'])) {
    $mode = 'save';
  } else {
    $mode = '';
  }
}

if ($mode != '') {
  switch ($mode) {

    case 'edit':
    case 'add':
      //
      // They want to add a new notice, show the form.
      //
      $notice_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

      $s_hidden_fields = '';

      if ($mode == 'edit') {
        if (empty($notice_id)) {
          bb_die($lang['ERROR']);
        }

        $sql = 'SELECT * FROM ' . BB_NOTICES . " WHERE notice_id = $notice_id";
        if (!$result = DB()->sql_query($sql)) {
          bb_die('Could not obtain notices data #1');
        }

        $notice_info = DB()->sql_fetchrow($result);
        $s_hidden_fields .= '<input type="hidden" name="id" value="' . $notice_id . '" />';
      }

      $s_hidden_fields .= '<input type="hidden" name="mode" value="save" />';

      $template->assign_vars([
        'TPL_NOTICES_EDIT' => true,

        'TEXT' => !empty($notice_info['notice_text']) ? (string)$notice_info['notice_text'] : '',
        'ACTIVE' => !empty($notice_info['notice_active']) ? (int)$notice_info['notice_active'] : '',

        'S_NOTICES_ACTION' => 'admin_notices.php',
        'S_HIDDEN_FIELDS' => $s_hidden_fields,
      ]);
      break;

    case 'save':
      //
      // Ok, they sent us our info, let's update it.
      //

      $notice_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
      $notice_text = isset($_POST['text']) ? (string)$_POST['text'] : '';
      $notice_active = isset($_POST['active']) ? trim((int)$_POST['active']) : '';

      if ($notice_text == '' || $notice_active == '') {
        bb_die($lang['FIELDS_EMPTY']);
      }

      if ($notice_id) {

        $sql = 'UPDATE ' . BB_NOTICES . "
				SET notice_text = '" . DB()->escape($notice_text) . "',
					notice_active = '" . DB()->escape($notice_active) . "'
				WHERE notice_id = $notice_id";

        $message = $lang['NOTICE_UPDATED'];
      } else {
        $sql = 'INSERT INTO ' . BB_NOTICES . " (notice_active, notice_text)
				VALUES ('" . DB()->escape($notice_active) . "', '" . DB()->escape($notice_text) . "')";

        $message = $lang['NOTICE_ADDED'];
      }

      if (!$result = DB()->sql_query($sql)) {
        bb_die('Could not update / insert into notices table');
      }

      $message .= '<br /><br />' . sprintf($lang['CLICK_RETURN_NOTICEADMIN'], '<a href="admin_notices.php">', '</a>') . '<br /><br />' . sprintf($lang['CLICK_RETURN_ADMIN_INDEX'], '<a href="index.php?pane=right">', '</a>');

      $datastore->update('notices');

      bb_die($message);
      break;

    case 'delete':
      //
      // Ok, they want to delete their notice
      //

      if (isset($_POST['id']) || isset($_GET['id'])) {
        $notice_id = isset($_POST['id']) ? (int)$_POST['id'] : (int)$_GET['id'];
      } else {
        $notice_id = 0;
      }

      if ($notice_id) {
        $sql = 'DELETE FROM ' . BB_NOTICES . " WHERE notice_id = $notice_id";

        if (!$result = DB()->sql_query($sql)) {
          bb_die('Could not delete notices data');
        }

        $datastore->update('notices');

        bb_die($lang['DELETE_NOTICE'] . '<br /><br />' . sprintf($lang['CLICK_RETURN_NOTICEADMIN'], '<a href="admin_notices.php">', '</a>') . '<br /><br />' . sprintf($lang['CLICK_RETURN_ADMIN_INDEX'], '<a href="index.php?pane=right">', '</a>'));
      } else {
        bb_die($lang['ERROR']);
      }
      break;

    default:
      bb_die("Invalid mode: $mode");
  }
} else {
  //
  // Show the default page
  //
  $sql = 'SELECT * FROM ' . BB_NOTICES . ' ORDER BY notice_id';
  if (!$result = DB()->sql_query($sql)) {
    bb_die('Could not obtain notices data #2');
  }
  $notices_count = DB()->num_rows($result);
  $notices_rows = DB()->sql_fetchrowset($result);

  $template->assign_vars([
    'TPL_NOTICES_LIST' => true,
    'S_NOTICES_ACTION' => 'admin_notices.php',
  ]);

  for ($i = 0; $i < $notices_count; $i++) {
    $notice_id = $notices_rows[$i]['notice_id'];

    $row_class = !($i % 2) ? 'row1' : 'row2';

    $template->assign_block_vars('notices', [
      'ROW_CLASS' => $row_class,
      'TEXT' => $notices_rows[$i]['notice_text'],
      'ACTIVE' => (bool)$notices_rows[$i]['notice_active'] ? $lang['ENABLED'] : $lang['DISABLED'],

      'U_NOTICE_EDIT' => "admin_notices.php?mode=edit&amp;id=$notice_id",
      'U_NOTICE_DELETE' => "admin_notices.php?mode=delete&amp;id=$notice_id",
    ]);
  }
}

print_page('admin_notices.tpl', 'admin');
