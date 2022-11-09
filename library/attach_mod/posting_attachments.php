<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

if (!defined('BB_ROOT')) {
  \TorrentPier\Legacy\Dev::error_message(basename(__FILE__));
}

define('FILENAME_PREFIX', false);
define('FILENAME_PREFIX_LENGTH', 6);
define('FILENAME_MAX_LENGTH', 180);
define('FILENAME_CRYPTIC', false);
define('FILENAME_CRYPTIC_LENGTH', 64);

/**
 * Entry Point
 */
function execute_posting_attachment_handling()
{
  global $attachment_mod;

  $attachment_mod['posting'] = new TorrentPier\Legacy\AttachPosting();
  $attachment_mod['posting']->posting_attachment_mod();
}
