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

global $bb_cfg;

$smilies = [];

$rowset = DB()->fetch_rowset("SELECT * FROM " . BB_SMILIES);
sort($rowset);

foreach ($rowset as $smile) {
  $smilies['orig'][] = '#(?<=^|\W)' . preg_quote($smile['code'], '#') . '(?=$|\W)#';
  $smilies['repl'][] = ' <img class="smile" src="' . $bb_cfg['smilies_path'] . '/' . $smile['smile_url'] . '" alt="' . $smile['emoticon'] . '" align="absmiddle" border="0" />';
  $smilies['smile'][] = $smile;
}

$this->store('smile_replacements', $smilies);
