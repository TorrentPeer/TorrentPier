<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

if (!empty($setmodules)) {
  $module['GENERAL']['PHP_INFO'] = basename(__FILE__);
  return;
}

require __DIR__ . '/pagestart.php';

/** @noinspection ForgottenDebugOutputInspection */
phpinfo();
