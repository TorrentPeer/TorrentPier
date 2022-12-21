<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Legacy;

/**
 * Class Crypt
 * @package TorrentPier\Legacy
 */
class Crypt
{
  /**
   * @param $str
   * @return float
   */
  public static function bb_crc32($str): float
  {
    return (float)sprintf('%u', crc32($str));
  }

  /**
   * @param $value
   * @return string
   */
  public static function hexhex($value): string
  {
    return dechex(hexdec($value));
  }

  /**
   * MD5 crypt
   *
   * @param $string
   * @param false $safety
   * @return string
   */
  public static function md5($string, bool $safety = false): string
  {
    $safety ? $md5 = md5(md5($string)) : $md5 = md5($string);
    return $md5;
  }

  /**
   * Sha1 crypt
   *
   * @param $string
   * @param false $safety
   * @return string
   */
  public static function sha1($string, bool $safety = false): string
  {
    $safety ? $sha1 = sha1(sha1($string)) : $sha1 = sha1($string);
    return $sha1;
  }

  /**
   * Base64 string encode
   *
   * @param $string
   * @return string
   */
  public static function base64_encode($string): string
  {
    return base64_encode($string);
  }

  /**
   * Base64 string decode
   *
   * @param $string
   * @return false|string
   */
  public static function base64_decode($string)
  {
    return base64_decode($string);
  }

  /**
   * Generate a hash of password
   *
   * @param string $password
   * @return false|string|null
   */
  public static function password_hash(string $password)
  {
    return password_hash($password, PASSWORD_DEFAULT);
  }

  /**
   * Password hash verify
   *
   * @param string $password
   * @param string $hash
   * @return bool
   */
  public static function password_verify(string $password, string $hash): bool
  {
    return password_verify($password, $hash) || self::md5($password, true) === $hash;
  }

  /**
   * Генерирует токен на основе TIMENOW + случайное число
   *
   * @return int
   */
  public static function get_hash_number(): int
  {
    return intval(TIMENOW * rand(1, 99999));
  }
}
