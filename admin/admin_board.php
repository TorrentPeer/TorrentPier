<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

if (!empty($setmodules)) {
  $module['GENERAL']['CONFIGURATION'] = basename(__FILE__) . '?mode=config';
  $module['MODS']['CONFIGURATION'] = basename(__FILE__) . '?mode=config_mods';
  return;
}
require __DIR__ . '/pagestart.php';

$mode = $_GET['mode'] ?? '';

$return_links = [
  'index' => '<br /><br />' . sprintf($lang['CLICK_RETURN_ADMIN_INDEX'], '<a href="index.php?pane=right">', '</a>'),
  'config' => '<br /><br />' . sprintf($lang['CLICK_RETURN_CONFIG'], '<a href="admin_board.php?mode=config">', '</a>'),
  'config_mods' => '<br /><br />' . sprintf($lang['CLICK_RETURN_CONFIG_MODS'], '<a href="admin_board.php?mode=config_mods">', '</a>')
];

/**
 * Pull all config data
 */
$sql = 'SELECT * FROM ' . BB_CONFIG;
if (!$result = DB()->sql_query($sql)) {
  bb_die('Could not query config information in admin_board');
} else {
  while ($row = DB()->sql_fetchrow($result)) {
    $config_name = $row['config_name'];
    $config_value = $row['config_value'];
    $default_config[$config_name] = $config_value;

    $new[$config_name] = $_POST[$config_name] ?? $default_config[$config_name];

    if (isset($_POST['submit']) && $row['config_value'] != $new[$config_name]) {
      if ($config_name == 'seed_bonus_points' ||
        $config_name == 'seed_bonus_release' ||
        $config_name == 'bonus_upload' ||
        $config_name == 'bonus_upload_price'
      ) {
        $new[$config_name] = serialize(str_replace(',', '.', $new[$config_name]));
      }
      bb_update_config([$config_name => $new[$config_name]]);
    }
  }

  if (isset($_POST['submit'])) {
    bb_die($lang['CONFIG_UPDATED'] . $return_links[$mode] . $return_links['index']);
  }
}

// Logo find
$logo_dir = opendir(BB_ROOT . $new['logo_image_path']);
$count = 0;
$logo = [];
while ($file = @readdir($logo_dir)) {
  if (!is_dir(bb_realpath(BB_ROOT . $new['logo_image_path'] . '/' . $file))) {
    if (preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $file)) {
      $logo[$count] = $file;
      $count++;
    }
  }
}
closedir($logo_dir);

// Logo select
$logo_list = '';
for ($i = 0; $i < count($logo); $i++) {
  if ($logo[$i] == $new['logo_image']) {
    $logo_list .= '<option value="' . $logo[$i] . '" selected="selected">' . $logo[$i] . '</option>';
  } else {
    $logo_list .= '<option value="' . $logo[$i] . '">' . $logo[$i] . '</option>';
  }
}

switch ($mode) {
  case 'config_mods':
    $template->assign_vars([
      'S_CONFIG_ACTION' => 'admin_board.php?mode=config_mods',
      'CONFIG_MODS' => true,

      'MAGNET_LINKS_ENABLED' => $new['magnet_links_enabled'],
      'GENDER' => $new['gender'],
      'CALLSEED' => $new['callseed'],
      'INVITE_ENABLED' => $new['new_user_reg_only_by_invite'],
      'TOR_STATS' => $new['tor_stats'],
      'SHOW_LATEST_NEWS' => $new['show_latest_news'],
      'MAX_NEWS_TITLE' => $new['max_news_title'],
      'LATEST_NEWS_COUNT' => $new['latest_news_count'],
      'LATEST_NEWS_FORUM_ID' => $new['latest_news_forum_id'],
      'SHOW_NETWORK_NEWS' => $new['show_network_news'],
      'MAX_NET_TITLE' => $new['max_net_title'],
      'NETWORK_NEWS_COUNT' => $new['network_news_count'],
      'NETWORK_NEWS_FORUM_ID' => $new['network_news_forum_id'],
      'WHOIS_INFO' => $new['whois_info'],
      'SHOW_MOD_INDEX' => $new['show_mod_index'],
      'BIRTHDAY_ENABLED' => $new['birthday_enabled'],
      'BIRTHDAY_MAX_AGE' => $new['birthday_max_age'],
      'BIRTHDAY_MIN_AGE' => $new['birthday_min_age'],
      'BIRTHDAY_CHECK_DAY' => $new['birthday_check_day'],
      'PREMOD' => $new['premod'],
      'TOR_COMMENT' => $new['tor_comment'],
      'SEED_BONUS_ENABLED' => $new['seed_bonus_enabled'],
      'SEED_BONUS_TOR_SIZE' => $new['seed_bonus_tor_size'],
      'SEED_BONUS_USER_REGDATE' => $new['seed_bonus_user_regdate'],
      'USE_DYNAMIC_DESCRIPTION' => $new['use_dynamic_description'],
      'USE_DYNAMIC_KEYWORDS' => $new['use_dynamic_keywords'],
      'GLOBAL_DESCRIPTION' => $new['global_description'],
      'GLOBAL_KEYWORDS' => $new['global_keywords'],
      'APPEND_GLOBAL_DESCRIPTION' => $new['append_global_description'],
      'APPEND_GLOBAL_KEYWORDS' => $new['append_global_keywords'],
      'APPEND_KEYWORDS_FIRST' => $new['append_keywords_first'],
      'DESCRIPTION_WORD_COUNT' => $new['description_word_count'],
      'KEYWORD_WORD_COUNT' => $new['keyword_word_count'],
    ]);

    if ($new['seed_bonus_points'] && $new['seed_bonus_release']) {
      $seed_bonus = unserialize($new['seed_bonus_points']);
      $seed_release = unserialize($new['seed_bonus_release']);

      foreach ($seed_bonus as $i => $row) {
        if (!$row || !$seed_release[$i]) {
          continue;
        }

        $template->assign_block_vars('seed_bonus', [
          'RELEASE' => $seed_release[$i],
          'POINTS' => $row,
        ]);
      }
    }

    if ($new['bonus_upload'] && $new['bonus_upload_price']) {
      $upload_row = unserialize($new['bonus_upload']);
      $price_row = unserialize($new['bonus_upload_price']);

      foreach ($upload_row as $i => $row) {
        if (!$row || !$price_row[$i]) {
          continue;
        }

        $template->assign_block_vars('bonus_upload', [
          'UP' => $row,
          'PRICE' => $price_row[$i],
        ]);
      }
    }
    break;

  default:
    $template->assign_vars([
      'S_CONFIG_ACTION' => 'admin_board.php?mode=config',
      'CONFIG' => true,

      'SITENAME' => htmlCHR($new['sitename']),
      'CONFIG_SITE_DESCRIPTION' => htmlCHR($new['site_desc']),
      'DISABLE_BOARD' => $new['board_disable'] ? true : false,
      'ALLOW_AUTOLOGIN' => $new['allow_autologin'] ? true : false,
      'AUTOLOGIN_TIME' => (int)$new['max_autologin_time'],
      'MAX_POLL_OPTIONS' => $new['max_poll_options'],
      'FLOOD_INTERVAL' => $new['flood_interval'],
      'TOPICS_PER_PAGE' => $new['topics_per_page'],
      'POSTS_PER_PAGE' => $new['posts_per_page'],
      'HOT_TOPIC' => $new['hot_threshold'],
      'DEFAULT_DATEFORMAT' => $new['default_dateformat'],
      'LANG_SELECT' => \TorrentPier\Legacy\Select::language($new['default_lang'], 'default_lang'),
      'TIMEZONE_SELECT' => \TorrentPier\Legacy\Select::timezone($new['board_timezone'], 'board_timezone'),
      'LOGO_PATH' => $new['logo_image_path'],
      'LOGO_IMAGE_DIR' => BB_ROOT . $new['logo_image_path'],
      'LOGO_LIST' => $logo_list,
      'LOGO_IMAGE' => (file_exists(BB_ROOT . $bb_cfg['logo_image_path'] . '/' . $new['logo_image'])) ? (BB_ROOT . $bb_cfg['logo_image_path'] . '/' . $new['logo_image']) : '',
      'LOGO_WIDTH' => $new['logo_image_w'],
      'LOGO_HEIGHT' => $new['logo_image_h'],
      'MAX_LOGIN_ATTEMPTS' => $new['max_login_attempts'],
      'LOGIN_RESET_TIME' => $new['login_reset_time'],
      'PRUNE_ENABLE' => $new['prune_enable'] ? true : false,
      'ALLOW_BBCODE' => $new['allow_bbcode'] ? true : false,
      'ALLOW_SMILIES' => $new['allow_smilies'] ? true : false,
      'ALLOW_SIG' => $new['allow_sig'] ? true : false,
      'SIG_SIZE' => $new['max_sig_chars'],
      'ALLOW_NAMECHANGE' => $new['allow_namechange'] ? true : false,
      'SMILIES_PATH' => $new['smilies_path'],
    ]);
    break;
}

print_page('admin_board.tpl', 'admin');
