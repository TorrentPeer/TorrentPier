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

global $bb_cfg, $lang, $userdata;

$mode = (string)$this->request['mode'];

$html = '<img src="./styles/images/good.gif">';
switch ($mode) {
  case 'check_name':
    $username = clean_username($this->request['username']);

    if (empty($username)) {
      $html = '<img src="./styles/images/bad.gif"> <span class="leechmed bold">' . $lang['CHOOSE_A_NAME'] . '</span>';
    } elseif ($err = \TorrentPier\Legacy\Validate::username($username)) {
      $html = '<img src="./styles/images/bad.gif"> <span class="leechmed bold">' . $err . '</span>';
    }
    break;

  case 'check_email':
    $email = (string)$this->request['email'];

    if (empty($email)) {
      $html = '<img src="./styles/images/bad.gif"> <span class="leechmed bold">' . $lang['CHOOSE_E_MAIL'] . '</span>';
    } elseif ($err = \TorrentPier\Legacy\Validate::email($email)) {
      $html = '<img src="./styles/images/bad.gif"> <span class="leechmed bold">' . $err . '</span>';
    }
    break;

  case 'check_pass':
    $pass = (string)$this->request['pass'];
    $pass_confirm = (string)$this->request['pass_confirm'];

    if (empty($pass) || empty($pass_confirm)) {
      $html = '<img src="./styles/images/bad.gif"> <span class="leechmed bold">' . $lang['CHOOSE_PASS'] . '</span>';
    } else {
      if ($err = \TorrentPier\Legacy\Validate::password($pass, $pass_confirm)) {
        $html = '<img src="./styles/images/bad.gif"> <span class="leechmed bold">' . $err . '</span>';
      } else {
        $text = (IS_GUEST) ? $lang['CHOOSE_PASS_REG_OK'] : $lang['CHOOSE_PASS_OK'];
        $html = '<img src="./styles/images/good.gif"> <span class="seedmed bold">' . $text . '</span>';
      }
    }
    break;

  case 'check_invite':
    if ($bb_cfg['new_user_reg_only_by_invite']) {
      $invite_code = (string)$this->request['invite_code'];
      if (empty($invite_code)) {
        $html = '<img src="./styles/images/bad.gif"> <span class="leechmed bold">' . $lang['AJAX_INVITE_FIELD_BLANK'] . '</span>';
      } else if ($err = \TorrentPier\Legacy\Validate::validate_invite_code($invite_code)) {
        $html = '<img src="./styles/images/bad.gif"> <span class="leechmed bold">' . $err . '</span>';
      }
    }
    break;

  default:
    $this->ajax_die("Invalid mode: $mode");
}

$this->response['html'] = $html;
$this->response['mode'] = $mode;
