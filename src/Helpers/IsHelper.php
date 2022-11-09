<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Helpers;

/**
 * Class IsHelper
 * @package TorrentPier\Helpers
 */
class IsHelper
{
  /**
   * Determines if the current version of PHP is equal to or greater than the supplied value
   *
   * @param string
   * @return bool    TRUE if the current version is $version or higher
   */
  public static function is_php($version): bool
  {
    static $_is_php;
    $version = (string)$version;
    if (!isset($_is_php[$version])) {
      $_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
    }
    return $_is_php[$version];
  }

  /**
   * Return true if ajax request
   *
   * @return bool
   */
  public static function is_ajax(): bool
  {
    return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
  }

  /**
   * Return true if server have SSL
   *
   * @return bool
   */
  public static function is_https(): bool
  {
    $is_secure = false;
    if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') {
      $is_secure = true;
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
      $is_secure = true;
    }
    return $is_secure;
  }
}
