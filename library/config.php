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

$domain_name = 'yourdomain.com'; // The domain name from which this board runs
$domain_secure = false; // Set "true" if server has SSL
$server_port = 80; // The port your server is running on

$bb_cfg = [];

// Increase number after changing js or css
$bb_cfg['js_ver'] = $bb_cfg['css_ver'] = 1;

// App info
$bb_cfg['tp_name'] = 'TorrentPier';
$bb_cfg['tp_version'] = '2.4.0.5-beta';
$bb_cfg['tp_release_date'] = '23-11-22';
$bb_cfg['tp_release_codename'] = 'Kouprey';

// GZip
$bb_cfg['gzip_compress'] = false; // compress output

// Database
$bb_cfg['db'] = [
  // Available drivers - mysql, postgresql, sqlite (mysql by default)
  'driver' => 'mysql',
  // Настройка баз данных ['db']['srv_name'] => (array) srv_cfg;
  // порядок параметров srv_cfg (хост, порт, название базы, пользователь, пароль, charset, pconnect);
  'db' => ['localhost', 3306, 'torrentpier', 'root', 'root', 'utf8', false],
];

$bb_cfg['db_alias'] = [
  'log' => 'db', // BB_LOG
  'search' => 'db', // BB_TOPIC_SEARCH
  'sres' => 'db', // BB_BT_USER_SETTINGS, BB_SEARCH_RESULTS
  'u_ses' => 'db', // BB_USER_SES, BB_USER_LASTVISIT
  'dls' => 'db', // BB_BT_DLS_*
  'ip' => 'db', // BB_POSTS_IP
  'ut' => 'db', // BB_TOPICS_USER_POSTED
  'pm' => 'db', // BB_PRIVMSGS, BB_PRIVMSGS_TEXT
  'pt' => 'db', // BB_POSTS_TEXT
];

// Cache
$bb_cfg['cache'] = [
  'pconnect' => true,
  'cache_dir' => realpath(BB_ROOT) . '/internal_data/cache/',
  'prefix' => 'tp_',
  'memcached' => [
    'host' => '127.0.0.1',
    'port' => 11211,
  ],
  'redis' => [
    'host' => '127.0.0.1',
    'port' => 6379,
  ],
  'filecache' => [
    'fileExtension' => 'cache',
    'gzipCompression' => $bb_cfg['gzip_compress'],
  ],
  // Available cache types: filecache, memcached, sqlite, redis, apcu, postgresql, mysql (filecache by default)
  'engines' => [
    'bb_cache' => 'filecache',
    'bb_config' => 'filecache',
    'tr_cache' => 'filecache',
    'session_cache' => 'filecache',
    'bb_cap_sid' => 'filecache',
    'bb_login_err' => 'filecache',
    'bb_poll_data' => 'filecache',
  ],
];

// Datastore
// Available datastore types: filecache, memcached, sqlite, redis, apcu, postgresql, mysql (filecache by default)
$bb_cfg['datastore_type'] = 'filecache';

// Server
$bb_cfg['server_name'] = $domain_name = (!empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $domain_name);
$bb_cfg['server_secure'] = \TorrentPier\Helpers\IsHelper::is_https() ? true : $domain_secure;
$bb_cfg['server_port'] = !empty($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : $server_port;

$bb_cfg['script_path'] = '/'; // The path where FORUM is located relative to the domain name

// Tracker
$bb_cfg['tracker_torstatus'] = true; // Search by torrent status
$bb_cfg['announce_interval'] = 2400; // Announce interval (default: 1800)
$bb_cfg['passkey_key'] = 'uk'; // Passkey key name in GET request
$bb_cfg['ignore_reported_ip'] = false; // Ignore IP reported by client
$bb_cfg['verify_reported_ip'] = true; // Verify IP reported by client against $_SERVER['HTTP_X_FORWARDED_FOR']
$bb_cfg['allow_internal_ip'] = false; // Allow internal IP (10.xx.. etc.)

// Ocelot
$bb_cfg['ocelot'] = [
  'enabled' => false,
  'host' => $domain_name,
  'port' => 34000,
  'url' => "http://$domain_name:34000/", // with '/'
  'secret' => 'some_10_chars', // 10 chars
  'stats' => 'some_10_chars', // 10 chars
];

// FAQ url help link
$bb_cfg['how_to_download_url_help'] = 'viewtopic.php?t=1'; // Как скачивать?
$bb_cfg['what_is_torrent_url_help'] = 'viewtopic.php?t=2'; // Что такое торрент?
$bb_cfg['ratio_url_help'] = 'viewtopic.php?t=3'; // Рейтинг и ограничения
$bb_cfg['search_help_url'] = 'viewtopic.php?t=4'; // Помощь по поиску

// Torrents
$bb_cfg['bt_min_ratio_allow_dl_tor'] = 0.3; // 0 - disable
$bb_cfg['bt_min_ratio_warning'] = 0.6; // 0 - disable

$bb_cfg['show_dl_status_in_search'] = true;
$bb_cfg['show_dl_status_in_forum'] = true;
$bb_cfg['show_tor_info_in_dl_list'] = true;
$bb_cfg['allow_dl_list_names_mode'] = true;

// Days to keep torrent registered
$bb_cfg['seeder_last_seen_days_keep'] = 0; // сколько дней назад был сид последний раз
$bb_cfg['seeder_never_seen_days_keep'] = 0; // сколько дней имеется статус "Сида не было никогда"

// DL-Status (days to keep user's dlstatus records)
$bb_cfg['dl_will_days_keep'] = 360;
$bb_cfg['dl_down_days_keep'] = 180;
$bb_cfg['dl_complete_days_keep'] = 180;
$bb_cfg['dl_cancel_days_keep'] = 30;

// Tor-Stats
$bb_cfg['torstat_days_keep'] = 60; // days to keep user's per-torrent stats

// Tor-Help
$bb_cfg['torhelp_enabled'] = false; // find dead torrents (without seeder) that user might help seeding

// URL's
$bb_cfg['ajax_url'] = 'ajax.php'; # "http://{$_SERVER['SERVER_NAME']}/ajax.php"
$bb_cfg['dl_url'] = 'dl.php?id='; # "http://{$domain_name}/dl.php?id="
$bb_cfg['login_url'] = 'login.php'; # "http://{$domain_name}/login.php"
$bb_cfg['posting_url'] = 'posting.php'; # "http://{$domain_name}/posting.php"
$bb_cfg['pm_url'] = 'privmsg.php'; # "http://{$domain_name}/privmsg.php"

// Language
$bb_cfg['charset'] = 'UTF-8'; // page charset
$bb_cfg['lang'] = [
  'en' => [
    'name' => 'English',
    'locale' => 'en_US.UTF-8',
  ],
  'ru' => [
    'name' => 'Russian',
    'locale' => 'ru_RU.UTF-8',
  ]
];

// Templates
$bb_cfg['templating'] = [
  'templates' => [
    'default' => 'TorrentPier',
  ],
  'tpl_name' => 'default',
  'stylesheet' => 'main.css',
  'javascript' => 'scripts.js',
];

$bb_cfg['show_sidebar1_on_every_page'] = false;
$bb_cfg['show_sidebar2_on_every_page'] = false;
$bb_cfg['show_copyright_on_pages'] = true;

// Cookie
$bb_cfg['cookie_domain'] = in_array($domain_name, [$_SERVER['SERVER_ADDR'], 'localhost'], true) ? '' : ".$domain_name";
$bb_cfg['cookie_prefix'] = 'bb_'; // Set a cookie prefix
$bb_cfg['cookie_same_site'] = 'Lax'; // Lax, None, Strict | https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie/SameSite

// Sessions
$bb_cfg['session_update_intrv'] = 180; // sec
$bb_cfg['user_session_duration'] = 1800; // sec
$bb_cfg['admin_session_duration'] = 6 * 3600; // sec
$bb_cfg['user_session_gc_ttl'] = 1800; // number of seconds that a staled session entry may remain in sessions table
$bb_cfg['session_cache_gc_ttl'] = 1200; // sec
$bb_cfg['max_last_visit_days'] = 14; // days
$bb_cfg['last_visit_update_intrv'] = 3600; // sec

// Registration
$bb_cfg['invalid_logins'] = 5; // Количество неверных попыток ввода пароля, перед выводом проверки капчей
$bb_cfg['new_user_reg_disabled'] = false; // Запретить регистрацию новых учетных записей
$bb_cfg['unique_ip'] = false; // Запретить регистрацию нескольких учетных записей с одного ip
$bb_cfg['new_user_reg_restricted'] = false; // Ограничить регистрацию новых пользователей по времени по указанному ниже интервалу
$bb_cfg['new_user_reg_interval'] = [0, 1, 2, 3, 4, 5, 6, 7, 8, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23]; // Допустимые часы регистрации
$bb_cfg['reg_email_activation'] = false; // Требовать активацию учетной записи по email

// Email
$bb_cfg['emailer'] = [
  // Read more: https://symfony.com/doc/current/mailer.html
  'enabled' => false,
  'dsn' => 'smtp://user:pass@smtp.example.com:25',
];
$bb_cfg['extended_email_validation'] = true; // расширенная проверка почты

$bb_cfg['board_email'] = "noreply@$domain_name"; // admin email address
$bb_cfg['board_email_form'] = false; // can users send email to each other via board
$bb_cfg['board_email_sig'] = ''; // this text will be attached to all emails the board sends
$bb_cfg['board_email_sitename'] = $domain_name; // sitename used in all emails header

$bb_cfg['topic_notify_enabled'] = true;
$bb_cfg['pm_notify_enabled'] = true;
$bb_cfg['group_send_email'] = true;
$bb_cfg['email_change_disabled'] = false; // disable changing email by user

$bb_cfg['bounce_email'] = "bounce@$domain_name"; // bounce email address
$bb_cfg['tech_admin_email'] = "admin@$domain_name"; // email for sending error reports
$bb_cfg['abuse_email'] = "abuse@$domain_name";
$bb_cfg['adv_email'] = "adv@$domain_name";

// Bugsnag error reporting
$bb_cfg['bugsnag'] = [
  // Read more: https://docs.bugsnag.com/api/
  'enabled' => false,
  'api_key' => 'YOUR_BUGSNAG_KEY', // your bugsnag API key
];

// Special users
$bb_cfg['dbg_users'] = [
#	user_id => 'name',
  2 => 'admin',
];
$bb_cfg['unlimited_users'] = [
#	user_id => 'name',
  2 => 'admin',
];
$bb_cfg['super_admins'] = [
#	user_id => 'name',
  2 => 'admin',
];

// Date format
$bb_cfg['date_format'] = 'Y-m-d';

// Subforums
$bb_cfg['sf_on_first_page_only'] = true;

// Forums
$bb_cfg['allowed_topics_per_page'] = [50, 100, 150, 200, 250, 300];

// Topics
$bb_cfg['show_who_is_looking'] = true;
$bb_cfg['show_quick_reply'] = true;
$bb_cfg['show_rank_text'] = false;
$bb_cfg['show_rank_image'] = true;
$bb_cfg['show_poster_joined'] = true;
$bb_cfg['show_poster_posts'] = true;
$bb_cfg['show_poster_from'] = true;
$bb_cfg['show_bot_nick'] = false;
$bb_cfg['text_buttons'] = false; // replace EDIT, QUOTE... images with text links
$bb_cfg['parse_ed2k_links'] = true; // make ed2k links clickable
$bb_cfg['post_date_format'] = 'd-M-Y H:i';
$bb_cfg['ext_link_new_win'] = true; // open external links in new window

$bb_cfg['scroll_to_top'] = [
  // Up to top button
  'enabled' => true,
  'speed' => 700,
];

$bb_cfg['similar_topics'] = 5; // number of topics displayed (if 0 - mod is disabled)
$bb_cfg['similar_title'] = 3; // number of words to search
$bb_cfg['similar_title_len'] = 3; // minimum length of words to search

$bb_cfg['topic_moved_days_keep'] = 7; // remove topic moved links after xx days (or FALSE to disable)
$bb_cfg['allowed_posts_per_page'] = [15, 30, 50, 100];
$bb_cfg['user_signature_start'] = '<div class="signature"><br />_________________<br />';
$bb_cfg['user_signature_end'] = '</div>'; // Это позволит использовать html теги, которые требуют закрытия. Например <table> или <font color>

// Posts
$bb_cfg['use_posts_cache'] = true;
$bb_cfg['posts_cache_days_keep'] = 14;
$bb_cfg['max_post_length'] = 120000;
$bb_cfg['use_ajax_posts'] = true;

// Search
$bb_cfg['search_engine_type'] = 'mysql'; // none, mysql, sphinx

$bb_cfg['sphinx'] = [
  'host' => '127.0.0.1',
  'port' => 3312,
  'path' => '../_project/sphinx/sphinx.conf', // path to config file on server
  'connect_timeout' => 5, // seconds
  'limits' => [
    'offset' => 0,
    'limit' => 100, // max results
  ],
];

$bb_cfg['disable_ft_search_in_posts'] = false; // disable searching in post bodies
$bb_cfg['disable_search_for_guest'] = true;
$bb_cfg['allow_search_in_bool_mode'] = true;
$bb_cfg['max_search_words_per_post'] = 200;
$bb_cfg['search_min_word_len'] = 3;
$bb_cfg['search_max_word_len'] = 35;
$bb_cfg['limit_max_search_results'] = false;

// Posting
$bb_cfg['prevent_multiposting'] = true; // replace "reply" with "edit last msg" if user (not admin or mod) is last topic poster
$bb_cfg['max_smilies'] = 25; // Максимальное число смайлов в посте (0 - без ограничения)

// PM
$bb_cfg['privmsg_disable'] = false; // отключить систему личных сообщений на форуме
$bb_cfg['max_outgoing_pm_cnt'] = 10; // ограничение на кол. одновременных исходящих лс (для замедления рассылки спама)
$bb_cfg['max_inbox_privmsgs'] = 500; // максимальное число сообщений в папке входящие
$bb_cfg['max_savebox_privmsgs'] = 500; // максимальное число сообщений в папке сохраненные
$bb_cfg['max_sentbox_privmsgs'] = 500; // максимальное число сообщений в папке отправленные
$bb_cfg['pm_days_keep'] = 180; // время хранения ЛС
$bb_cfg['pm_dynamic'] = [
  // Динамическое появление новых ЛС (без перезагрузки страницы)
  'enabled' => true,
  'check_interval' => 7 * 1000, // интервал проверки на наличие новых писем (7 секунд)
];

// Actions log
$bb_cfg['log_days_keep'] = 90;

// Users
$bb_cfg['color_nick'] = true; // Окраска ников пользователей по user_rank
$bb_cfg['user_not_activated_days_keep'] = 7; // "not activated" == "not finished registration"
$bb_cfg['user_not_active_days_keep'] = 180; // inactive users but only with no posts

// Groups
$bb_cfg['group_members_per_page'] = 50;

// Tidy
$bb_cfg['tidy_post'] = (!in_array('tidy', get_loaded_extensions(), true)) ? false : true;

// Misc
$bb_cfg['mem_on_start'] = memory_get_usage();
$bb_cfg['translate_dates'] = true; // in displaying time
$bb_cfg['use_word_censor'] = true; // авто цензура слов

$bb_cfg['reputation'] = [
  // Reputation
  'disabled' => false,
  'vote' => '10',
  'min_posts' => '2',
  'min_repa_out' => '0',
  'min_repa' => '-1000',
  'max_repa' => '30000',
  'ratio' => [
    'enabled' => false,
    'min_ratio' => '1.00',
  ],
];

$bb_cfg['scrolling_title'] = [
  // Animated page title
  'enabled' => false,
  'speed' => 250,
  'separator' => ' | ',
];

$bb_cfg['last_visit_date_format'] = 'd-M H:i';
$bb_cfg['last_post_date_format'] = 'd-M-y H:i';
$bb_cfg['poll_max_days'] = 180; // сколько дней с момента создания темы опрос будет активным

$bb_cfg['allow_change'] = [
  'language' => true,
  'dateformat' => false,
];

$bb_cfg['trash_forum_id'] = 0; // (int) 7

$bb_cfg['tor_help_block'] = '<p>Put here something :D (Edit: <b>$bb_cfg[\'tor_help_block\']</b> in <b>config.php</b>)</p>';

$bb_cfg['first_logon_redirect_url'] = 'index.php';
$bb_cfg['terms_and_conditions_url'] = 'terms.php';

$bb_cfg['user_agreement_url'] = 'info.php?show=user_agreement';
$bb_cfg['copyright_holders_url'] = 'info.php?show=copyright_holders';
$bb_cfg['advert_url'] = 'info.php?show=advert';

$bb_cfg['zodiac_sign'] = [
# 'знак зодиака' => [месяц начала, день начала, месяц конца, день конца]
  'aries' => [3, 21, 4, 20],
  'taurus' => [4, 21, 5, 21],
  'gemini' => [5, 22, 6, 21],
  'cancer' => [6, 22, 7, 22],
  'leo' => [7, 23, 8, 21],
  'virgo' => [8, 22, 9, 23],
  'libra' => [9, 24, 10, 23],
  'scorpio' => [10, 24, 11, 22],
  'sagittarius' => [11, 23, 12, 22],
  'capricorn' => [12, 23, 1, 20],
  'aquarius' => [1, 21, 2, 19],
  'pisces' => [2, 20, 3, 20],
];

// Extensions
$bb_cfg['file_id_ext'] = [
  1 => 'gif',
  2 => 'gz',
  3 => 'jpg',
  4 => 'png',
  5 => 'rar',
  6 => 'tar',
  7 => 'tiff',
  8 => 'torrent',
  9 => 'zip',
];

// Attachments
$bb_cfg['attach'] = [
  'upload_path' => DATA_DIR . '/torrent_files', // путь к директории с torrent файлами
  'max_size' => 5 * 1024 * 1024, // максимальный размер файла в байтах
];

$bb_cfg['tor_forums_allowed_ext'] = ['torrent', 'zip', 'rar']; // для разделов с раздачами
$bb_cfg['gen_forums_allowed_ext'] = ['zip', 'rar']; // для обычных разделов

// Sitemap (Ping list)
$bb_cfg['sitemap_sending'] = [
# 'Source name' => 'http://ping_url'
  'Google' => 'http://google.com/webmasters/sitemaps/ping?sitemap=',
];

// Avatars
$bb_cfg['avatars'] = [
  'allowed_ext' => ['gif', 'jpg', 'jpeg', 'png'], // разрешенные форматы файлов
  'bot_avatar' => '/gallery/bot.gif', // аватара бота
  'max_size' => 100 * 1024, // размер аватары в байтах
  'max_height' => 100, // высота аватара в px
  'max_width' => 100, // ширина аватара в px
  'avatar_provider' => [
    // Read more: https://gravatar.com/site/implement/images/
    'enabled' => false,
    'rating' => 'g', // g | pg | r | x
    'default_avatar' => 'retro', // 404 | mp | identicon | monsterid | wavatar | retro | robohash | blank
  ],
  'no_avatar' => '/gallery/noavatar.png', // дефолтная аватара
  'display_path' => '/data/avatars', // путь к директории с аватарами
  'upload_path' => BB_PATH . '/data/avatars/', // путь к директории с аватарами
  'up_allowed' => true, // разрешить загрузку аватар
];

// Group avatars
$bb_cfg['group_avatars'] = [
  'allowed_ext' => ['gif', 'jpg', 'jpeg', 'png'], // разрешенные форматы файлов
  'max_size' => 300 * 1024, // размер аватары в байтах
  'max_height' => 300, // высота аватара в px
  'max_width' => 300, // ширина аватара в px
  'up_allowed' => true, // разрешить загрузку аватар
];

// Captcha
$bb_cfg['captcha'] = [
  // Get a Google reCAPTCHA API Key: https://www.google.com/recaptcha/admin
  'disabled' => true,
  'public_key' => 'YOUR_CAPTCHA_PUBLIC_KEY', // your public key
  'secret_key' => 'YOUR_CAPTCHA_SECRET_KEY', // your secret key
  'theme' => 'light', // light or dark theme
];

// Atom feed
$bb_cfg['atom'] = [
  'path' => INT_DATA_DIR . '/atom', // without ending slash
  'url' => './internal_data/atom', // without ending slash
];

// Nofollow
$bb_cfg['nofollow'] = [
  'disabled' => false,
  'allowed_url' => [$domain_name], // 'allowed.site', 'www.allowed.site'
];

// Page settings
$bb_cfg['page'] = [
  'show_torhelp' => [
    #BB_SCRIPT => true
    'index' => true,
    'tracker' => true,
  ],
  'show_sidebar1' => [
    #BB_SCRIPT => true
    'index' => true,
  ],
  'show_sidebar2' => [
    #BB_SCRIPT => true
    'index' => true,
  ],
  'show_notices' => [
    #BB_SCRIPT => true
    'index' => true,
  ],
];

// Tracker settings
$bb_cfg['tracker'] = [
  'autoclean' => true,
  'off' => false,
  'off_reason' => 'temporarily disabled',
  'numwant' => 50,
  'update_dlstat' => true,
  'expire_factor' => 2.5,
  'compact_mode' => true,
  'upd_user_up_down_stat' => true,
  'browser_redirect_url' => '',
  'scrape' => true,
  'limit_active_tor' => true,
  'limit_seed_count' => 0,
  'limit_leech_count' => 8,
  'leech_expire_factor' => 60,
  'limit_concurrent_ips' => false,
  'limit_seed_ips' => 0,
  'limit_leech_ips' => 0,
  'tor_topic_up' => true,
  'gold_silver_enabled' => true,
  'retracker' => true,
  'retracker_host' => 'http://retracker.local/announce',
  'freeleech' => false,
  'guest_tracker' => true,
];

// Ratio settings
// Don't change the order of ratios (from 0 to 1)
// rating < 0.4 -- allow only 1 torrent for leeching
// rating < 0.5 -- only 2
// rating < 0.6 -- only 3
// rating > 0.6 -- depend on your tracker config limits (in "ACP - Tracker Config - Limits")
$bb_cfg['rating'] = [
  '0.4' => 1,
  '0.5' => 2,
  '0.6' => 3,
];

// Иконки статусов раздач
$bb_cfg['tor_icons'] = [
  TOR_NOT_APPROVED => '<span class="tor-icon tor-not-approved">*</span>',
  TOR_CLOSED => '<span class="tor-icon tor-closed">x</span>',
  TOR_APPROVED => '<span class="tor-icon tor-approved">&radic;</span>',
  TOR_NEED_EDIT => '<span class="tor-icon tor-need-edit">?</span>',
  TOR_NO_DESC => '<span class="tor-icon tor-no-desc">!</span>',
  TOR_DUP => '<span class="tor-icon tor-dup">D</span>',
  TOR_CLOSED_CPHOLD => '<span class="tor-icon tor-closed-cp">&copy;</span>',
  TOR_CONSUMED => '<span class="tor-icon tor-consumed">&sum;</span>',
  TOR_DOUBTFUL => '<span class="tor-icon tor-approved">#</span>',
  TOR_CHECKING => '<span class="tor-icon tor-checking">%</span>',
  TOR_TMP => '<span class="tor-icon tor-dup">T</span>',
  TOR_PREMOD => '<span class="tor-icon tor-dup">&#8719;</span>',
  TOR_REPLENISH => '<span class="tor-icon tor-dup">R</span>',
];

// Запрет на скачивание
$bb_cfg['tor_frozen'] = [
  TOR_CHECKING => true,
  TOR_CLOSED => true,
  TOR_CLOSED_CPHOLD => true,
  TOR_CONSUMED => true,
  TOR_DUP => true,
  TOR_NO_DESC => true,
  TOR_PREMOD => true,
];

// Разрешение на скачку автором, если закрыто на скачивание.
$bb_cfg['tor_frozen_author_download'] = [
  TOR_CHECKING => true,
  TOR_NO_DESC => true,
  TOR_PREMOD => true,
];

// Запрет на редактирование головного сообщения
$bb_cfg['tor_cannot_edit'] = [
  TOR_CHECKING => true,
  TOR_CLOSED => true,
  TOR_CONSUMED => true,
  TOR_DUP => true,
];

// Запрет на создание новых раздач если стоит статус недооформлено/не оформлено/сомнительно
$bb_cfg['tor_cannot_new'] = [TOR_NEED_EDIT, TOR_NO_DESC, TOR_DOUBTFUL];

// Разрешение на ответ релизера, если раздача исправлена.
$bb_cfg['tor_reply'] = [TOR_NEED_EDIT, TOR_NO_DESC, TOR_DOUBTFUL];

// Если такой статус у релиза, то статистика раздачи будет скрыта
$bb_cfg['tor_no_tor_act'] = [
  TOR_CLOSED => true,
  TOR_DUP => true,
  TOR_CLOSED_CPHOLD => true,
  TOR_CONSUMED => true,
];

// Vote graphic length defines the maximum length of a vote result graphic, ie. 100% = this length
$bb_cfg['vote_graphic_length'] = 205;
$bb_cfg['privmsg_graphic_length'] = 175;
$bb_cfg['topic_left_column_witdh'] = 150;

// Images auto-resize
$bb_cfg['post_img_width_decr'] = 52;
$bb_cfg['attach_img_width_decr'] = 130;

if (isset($bb_cfg['default_lang']) && file_exists(LANG_ROOT_DIR . '/' . $bb_cfg['default_lang'])) {
  $bb_cfg['default_lang_dir'] = LANG_ROOT_DIR . '/' . $bb_cfg['default_lang'] . '/';
} else {
  $bb_cfg['default_lang_dir'] = LANG_ROOT_DIR . '/en/';
}
