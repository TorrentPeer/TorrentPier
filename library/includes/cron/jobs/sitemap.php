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

$map = new TorrentPier\Legacy\Sitemap();
$map->createSitemap();

if (file_exists(SITEMAP_DIR . '/sitemap.xml')) {
  $map_link = make_url('sitemap/sitemap.xml');

  $map->sendSitemap('http://google.com/webmasters/sitemaps/ping?sitemap=', $map_link);
  $map->sendSitemap('http://www.bing.com/ping?sitemap=', $map_link);
}
