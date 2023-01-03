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

$map = new TorrentPier\Legacy\Sitemap();
$map->createSitemap();

if (file_exists(SITEMAP_DIR . '/sitemap.xml')) {
  $map_link = make_url(SITEMAP_DIR . '/sitemap.xml');

  foreach ($bb_cfg['sitemap_sending'] as $source_name => $source_link) {
    $map->sendSitemap($source_link, $map_link);
  }
}
