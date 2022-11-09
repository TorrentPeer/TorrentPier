<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Helpers;

use Mobile_Detect;

/**
 * Class MobileHelper
 * @package TorrentPier\Helpers
 */
class MobileHelper
{
  /**
   * Пользователь использует телефон
   *
   * @return bool
   */
  public static function isMobile(): bool
  {
    $detect = new Mobile_Detect;
    return $detect->isMobile();
  }

  /**
   * Пользователь использует планшет
   *
   * @return bool
   */
  public static function isTablet(): bool
  {
    $detect = new Mobile_Detect;
    return $detect->isTablet();
  }

  /**
   * У пользователя телефон или планшет
   *
   * @return bool
   */
  public static function isSmart(): bool
  {
    $detect = new Mobile_Detect;
    return ($detect->isTablet() || $detect->isMobile());
  }
}
