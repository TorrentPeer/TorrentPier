<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

if (!defined('IN_TRACKER')) {
  die(basename(__FILE__));
}

global $bb_cfg;

// Exit if tracker is disabled
if ($bb_cfg['tracker']['off']) {
  msg_die($bb_cfg['tracker']['off_reason']);
}

//
// Functions
//
function silent_exit()
{
  ob_end_clean();
  exit;
}

/**
 * @param string $msg
 */
function error_exit($msg = '')
{
  silent_exit();
  echo \SandFox\Bencode\Bencode::encode(['failure reason' => str_compact($msg)]);
  exit;
}
