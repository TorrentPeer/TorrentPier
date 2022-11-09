<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2018 TorrentPier (https://torrentpier.com)
 * @link      https://github.com/torrentpier/torrentpier for the canonical source repository
 * @license   https://github.com/torrentpier/torrentpier/blob/master/LICENSE MIT License
 */

namespace TorrentPier\Legacy\Common;

use Sabre\HTTP as TP_HTTP;

/**
 * Class Http
 * @package TorrentPier\Legacy\Common
 */
class Http
{

  /**
   * Set a HTTP response code
   *
   * @param $code
   */
  public static function setCode($code)
  {
    $response = new TP_HTTP\Response();
    $response->setStatus($code);
    TP_HTTP\Sapi::sendResponse($response);
  }
}
