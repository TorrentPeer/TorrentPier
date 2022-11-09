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

global $bf, $lang;

$user_id = (int)$this->request['user_id'];
$new_opt = json_decode($this->request['user_opt'], true);

if (!$user_id or !$u_data = get_userdata($user_id)) {
  $this->ajax_die("Invalid user_id: $user_id");
}

if (!is_array($new_opt)) {
  $this->ajax_die("Invalid new_opt: $new_opt");
}

foreach ($bf['user_opt'] as $opt_name => $opt_bit) {
  if (isset($new_opt[$opt_name])) {
    setbit($u_data['user_opt'], $opt_bit, !empty($new_opt[$opt_name]));
  }
}

DB()->query("UPDATE " . BB_USERS . " SET user_opt = {$u_data['user_opt']} WHERE user_id = $user_id");

// Удаляем данные из кеша
\TorrentPier\Legacy\Sessions::cache_rm_user_sessions($user_id);

$this->response['resp_html'] = $lang['SAVED'];
