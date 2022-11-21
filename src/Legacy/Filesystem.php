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
 * Class Filesystem
 * @package TorrentPier\Legacy
 */
class Filesystem
{
  /**
   * Creates a file and writes data to it
   *
   * @param $str
   * @param $file
   * @param int $max_size
   * @param bool $lock
   * @param false $replace_content
   * @return false|int
   */
  public static function file_write($str, $file, $max_size = LOG_MAX_SIZE, $lock = true, $replace_content = false)
  {
    $bytes_written = false;

    if ($max_size && file_exists($file) && filesize($file) >= $max_size) {
      $old_name = $file;
      $ext = '';
      if (preg_match('#^(.+)(\.[^\\\/]+)$#', $file, $matches)) {
        [$old_name, $ext] = $matches;
      }
      $new_name = $old_name . '_[old]_' . date('Y-m-d_H-i-s_') . getmypid() . $ext;
      clearstatcache();
      if (!file_exists($new_name)) {
        rename($file, $new_name);
      }
    }
    if (self::bb_mkdir(dirname($file))) {
      if ($fp = fopen($file, 'ab+')) {
        if ($lock) {
          flock($fp, LOCK_EX);
        }
        if ($replace_content) {
          ftruncate($fp, 0);
          fseek($fp, 0, SEEK_SET);
        }
        $bytes_written = fwrite($fp, $str);
        fclose($fp);
      }
    }
    return $bytes_written;
  }

  /**
   * Make directory
   *
   * @param $path
   * @param int $mode
   * @return bool
   */
  public static function bb_mkdir($path, $mode = 0777): bool
  {
    $old_um = umask(0);
    $dir = self::mkdir_rec($path, $mode);
    umask($old_um);
    return $dir;
  }

  /**
   * @param $path
   * @param $mode
   * @return bool
   */
  public static function mkdir_rec($path, $mode)
  {
    if (is_dir($path)) {
      return ($path !== '.' && $path !== '..') ? is_writable($path) : false;
    }

    return self::mkdir_rec(dirname($path), $mode) ? mkdir($path, $mode) : false;
  }

  /**
   * Clean a file name
   *
   * @param $fname
   * @return array|string|string[]
   */
  public static function clean_filename($fname)
  {
    static $s = ['\\', '/', ':', '*', '?', '"', '<', '>', '|', ' '];
    return str_replace($s, '_', str_compact($fname));
  }
}
