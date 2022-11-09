<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Legacy\AttachMod;

/**
 * Class Selects
 * @package TorrentPier\Legacy\AttachMod
 */
class Selects
{
  /**
   * select group
   *
   * @param $select_name
   * @param int $default_group
   * @return string
   * @throws \Exception
   */
  public static function group_select($select_name, $default_group = 0)
  {
    global $lang;

    $sql = 'SELECT group_id, group_name FROM ' . BB_EXTENSION_GROUPS . ' ORDER BY group_name';

    if (!($result = DB()->sql_query($sql))) {
      bb_die('Could not query extension groups table #1');
    }

    $group_select = '<select name="' . $select_name . '">';

    $group_name = DB()->sql_fetchrowset($result);
    $num_rows = DB()->num_rows($result);
    DB()->sql_freeresult($result);

    if ($num_rows > 0) {
      $group_name[$num_rows]['group_id'] = 0;
      $group_name[$num_rows]['group_name'] = $lang['NOT_ASSIGNED'];

      for ($i = 0, $iMax = count($group_name); $i < $iMax; $i++) {
        if (!$default_group) {
          $selected = ($i == 0) ? ' selected="selected"' : '';
        } else {
          $selected = ($group_name[$i]['group_id'] == $default_group) ? ' selected="selected"' : '';
        }

        $group_select .= '<option value="' . $group_name[$i]['group_id'] . '"' . $selected . '>' . $group_name[$i]['group_name'] . '</option>';
      }
    }

    $group_select .= '</select>';

    return $group_select;
  }

  /**
   * select download mode
   *
   * @param $select_name
   * @param int $group_id
   * @return string
   * @throws \Exception
   */
  public static function download_select($select_name, $group_id = 0)
  {
    global $types_download, $modes_download;

    if ($group_id) {
      $sql = 'SELECT download_mode
			FROM ' . BB_EXTENSION_GROUPS . '
			WHERE group_id = ' . (int)$group_id;

      if (!($result = DB()->sql_query($sql))) {
        bb_die('Could not query extension groups table #2');
      }
      $row = DB()->sql_fetchrow($result);
      DB()->sql_freeresult($result);

      if (!isset($row['download_mode'])) {
        return '';
      }

      $download_mode = $row['download_mode'];
    }

    $group_select = '<select name="' . $select_name . '">';

    for ($i = 0, $iMax = count($types_download); $i < $iMax; $i++) {
      if (!$group_id) {
        $selected = ($types_download[$i] == INLINE_LINK) ? ' selected="selected"' : '';
      } else {
        $selected = ($row['download_mode'] == $types_download[$i]) ? ' selected="selected"' : '';
      }

      $group_select .= '<option value="' . $types_download[$i] . '"' . $selected . '>' . $modes_download[$i] . '</option>';
    }

    $group_select .= '</select>';

    return $group_select;
  }

  /**
   * select category types
   *
   * @param $select_name
   * @param int $group_id
   * @return string
   * @throws \Exception
   */
  public static function category_select($select_name, $group_id = 0)
  {
    global $types_category, $modes_category;

    $sql = 'SELECT group_id, cat_id FROM ' . BB_EXTENSION_GROUPS;

    if (!($result = DB()->sql_query($sql))) {
      bb_die('Could not select category');
    }

    $rows = DB()->sql_fetchrowset($result);
    $num_rows = DB()->num_rows($result);
    DB()->sql_freeresult($result);

    $type_category = 0;

    if ($num_rows > 0) {
      for ($i = 0; $i < $num_rows; $i++) {
        if ($group_id == $rows[$i]['group_id']) {
          $category_type = $rows[$i]['cat_id'];
        }
      }
    }

    $types = [NONE_CAT];
    $modes = ['none'];

    for ($i = 0, $iMax = count($types_category); $i < $iMax; $i++) {
      $types[] = $types_category[$i];
      $modes[] = $modes_category[$i];
    }

    $group_select = '<select name="' . $select_name . '" style="width:100px">';

    for ($i = 0, $iMax = count($types); $i < $iMax; $i++) {
      if (!$group_id) {
        $selected = ($types[$i] == NONE_CAT) ? ' selected="selected"' : '';
      } else {
        $selected = ($types[$i] == $category_type) ? ' selected="selected"' : '';
      }

      $group_select .= '<option value="' . $types[$i] . '"' . $selected . '>' . $modes[$i] . '</option>';
    }

    $group_select .= '</select>';

    return $group_select;
  }

  /**
   * Select size mode
   *
   * @param $select_name
   * @param $size_compare
   * @return string
   */
  public static function size_select($select_name, $size_compare)
  {
    global $lang;

    $size_types_text = [$lang['BYTES'], $lang['KB'], $lang['MB']];
    $size_types = ['b', 'kb', 'mb'];

    $select_field = '<select name="' . $select_name . '">';

    for ($i = 0, $iMax = count($size_types_text); $i < $iMax; $i++) {
      $selected = ($size_compare == $size_types[$i]) ? ' selected="selected"' : '';
      $select_field .= '<option value="' . $size_types[$i] . '"' . $selected . '>' . $size_types_text[$i] . '</option>';
    }

    $select_field .= '</select>';

    return $select_field;
  }

  /**
   * select quota limit
   *
   * @param $select_name
   * @param int $default_quota
   * @return string
   * @throws \Exception
   */
  public static function quota_limit_select($select_name, $default_quota = 0)
  {
    global $lang;

    $sql = 'SELECT quota_limit_id, quota_desc FROM ' . BB_QUOTA_LIMITS . ' ORDER BY quota_limit ASC';

    if (!($result = DB()->sql_query($sql))) {
      bb_die('Could not query quota limits table #1');
    }

    $quota_select = '<select name="' . $select_name . '">';
    $quota_name[0]['quota_limit_id'] = 0;
    $quota_name[0]['quota_desc'] = $lang['NOT_ASSIGNED'];

    while ($row = DB()->sql_fetchrow($result)) {
      $quota_name[] = $row;
    }
    DB()->sql_freeresult($result);

    foreach ($quota_name as $i => $iValue) {
      $selected = ($quota_name[$i]['quota_limit_id'] == $default_quota) ? ' selected="selected"' : '';
      $quota_select .= '<option value="' . $quota_name[$i]['quota_limit_id'] . '"' . $selected . '>' . $quota_name[$i]['quota_desc'] . '</option>';
    }
    $quota_select .= '</select>';

    return $quota_select;
  }

  /**
   * select default quota limit
   *
   * @param $select_name
   * @param int $default_quota
   * @return string
   * @throws \Exception
   */
  public static function default_quota_limit_select($select_name, $default_quota = 0)
  {
    global $lang;

    $sql = 'SELECT quota_limit_id, quota_desc FROM ' . BB_QUOTA_LIMITS . ' ORDER BY quota_limit ASC';

    if (!($result = DB()->sql_query($sql))) {
      bb_die('Could not query quota limits table #2');
    }

    $quota_select = '<select name="' . $select_name . '">';
    $quota_name[0]['quota_limit_id'] = 0;
    $quota_name[0]['quota_desc'] = $lang['NO_QUOTA_LIMIT'];

    while ($row = DB()->sql_fetchrow($result)) {
      $quota_name[] = $row;
    }
    DB()->sql_freeresult($result);

    foreach ($quota_name as $i => $iValue) {
      $selected = ($quota_name[$i]['quota_limit_id'] == $default_quota) ? ' selected="selected"' : '';
      $quota_select .= '<option value="' . $quota_name[$i]['quota_limit_id'] . '"' . $selected . '>' . $quota_name[$i]['quota_desc'] . '</option>';
    }
    $quota_select .= '</select>';

    return $quota_select;
  }
}
