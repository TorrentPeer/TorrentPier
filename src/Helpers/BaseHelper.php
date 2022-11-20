<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Helpers;

use TorrentPier\Legacy\Crypt;

/**
 * Class BaseHelper
 * @package TorrentPier\Helpers
 */
class BaseHelper
{
  /**
   * Verify ID
   *
   * @param $id
   * @param $length
   * @return bool
   */
  public static function verify_id($id, $length): bool
  {
    return (is_string($id) && preg_match('#^[a-zA-Z0-9]{' . $length . '}$#', $id));
  }

  /**
   *
   * @param string $str
   * @return string
   */
  public static function str_compact($str)
  {
    return preg_replace('#\s+#u', ' ', trim($str));
  }

  /**
   * @param $var
   * @param $fn
   * @param false $one_dimensional
   * @param false $array_only
   */
  public static function array_deep(&$var, $fn, $one_dimensional = false, $array_only = false)
  {
    if (is_array($var)) {
      foreach ($var as $k => $v) {
        if (is_array($v)) {
          if ($one_dimensional) {
            unset($var[$k]);
          } elseif ($array_only) {
            $var[$k] = $fn($v);
          } else {
            self::array_deep($var[$k], $fn);
          }
        } elseif (!$array_only) {
          $var[$k] = $fn($v);
        }
      }
    } elseif (!$array_only) {
      $var = $fn($var);
    }
  }

  /**
   * Скрывает путь BB_PATH
   *
   * @param $path
   * @return string
   */
  public static function hide_bb_path($path): string
  {
    return ltrim(str_replace(BB_PATH, '', $path), '/\\');
  }

  /**
   * System utilities (Sys info)
   *
   * @param $param
   * @return int|string|void
   * @throws \Exception
   */
  public static function sys($param)
  {
    switch ($param) {
      case 'la':
        return function_exists('sys_getloadavg') ? implode(' ', sys_getloadavg()) : 0;
        break;
      case 'mem':
        return memory_get_usage();
        break;
      case 'mem_peak':
        return memory_get_peak_usage();
        break;
      default:
        bb_simple_die("Invalid param: $param");
    }
    return;
  }

  /**
   * Creates a string of random letters and numbers of the given length
   *
   * @param int $len
   * @return false|string
   */
  public static function make_rand_str($len = 10)
  {
    $str = '';
    while (strlen($str) < $len) {
      $str .= str_shuffle(preg_replace('#[^0-9a-zA-Z]#', '', password_hash(uniqid(mt_rand(), true), PASSWORD_BCRYPT)));
    }
    return substr($str, 0, $len);
  }

  /**
   * Шифрует сгенерированный токен
   *
   * @return string
   */
  public static function get_hash_token(): string
  {
    return Crypt::md5(self::get_hash_number(), true);
  }

  /**
   * Генерирует токен на основе TIMENOW + случайное число
   *
   * @return float|int
   */
  public static function get_hash_number()
  {
    return TIMENOW * rand(1, 99999);
  }
}
