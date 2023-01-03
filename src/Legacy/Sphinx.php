<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2023 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Legacy;

use Sphinx\SphinxClient;

/**
 * Class Sphinx
 * @package TorrentPier\Legacy
 */
class Sphinx extends SphinxClient
{
  /**
   * Sphinx constructor.
   */
  public function __construct()
  {
    global $bb_cfg;

    parent::__construct();

    $this->setConnectTimeout($bb_cfg['sphinx']['connect_timeout']);
    $this->setLimits($bb_cfg['sphinx']['limits']['offset'], $bb_cfg['sphinx']['limits']['limit']);
    $this->setRankingMode(self::SPH_RANK_NONE);
    $this->setMatchMode(self::SPH_MATCH_BOOLEAN);
  }

  /**
   * Logging sphinx errors
   *
   * @param $err_type
   * @param $err_msg
   * @param string $query
   */
  public static function log_sphinx_error($err_type, $err_msg, string $query = '')
  {
    if (!SPHINX_LOG_ERRORS) {
      return;
    }

    $ignore_err_txt = [
      'negation on top level',
      'Query word length is less than min prefix length',
    ];

    if (!count($ignore_err_txt) || !preg_match('#' . implode('|', $ignore_err_txt) . '#i', $err_msg)) {
      $orig_query = strtr($_REQUEST['nm'], ["\n" => '\n']);
      Logging::bb_log(date('m-d H:i:s') . " | $err_type | $err_msg | $orig_query | $query" . LOG_LF, SPHINX_LOG_NAME);
    }
  }
}
