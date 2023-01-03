<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2023 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

if (!defined('BB_ROOT')) {
  die(basename(__FILE__));
}

$announce_urls = [];

// Here you can define additional allowed announce urls
// For example, if you want to add http://example.com
// add this line: $announce_urls[] = 'http://example.com/bt/announce.php';

// $announce_urls[] = 'http://example.com/bt/announce.php';
