<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2023 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Legacy\AttachMod;

/**
 * Class Filetypes
 * @package TorrentPier\Legacy\AttachMod
 */
class Filetypes
{
  /**
   * Read Long Int (4 Bytes) from File
   *
   * @param $fp
   * @return int
   */
  private static function read_longint($fp)
  {
    $data = fread($fp, 4);

    $value = ord($data[0]) + (ord($data[1]) << 8) + (ord($data[2]) << 16) + (ord($data[3]) << 24);
    if ($value >= 4294967294) {
      $value -= 4294967296;
    }

    return $value;
  }

  /**
   * Read Word (2 Bytes) from File - Note: It's an Intel Word
   *
   * @param $fp
   * @return float|int
   */
  private static function read_word($fp)
  {
    $data = fread($fp, 2);

    return ord($data[1]) * 256 + ord($data[0]);
  }

  /**
   * Read Byte
   *
   * @param $fp
   * @return int
   */
  private static function read_byte($fp)
  {
    $data = fread($fp, 1);

    return ord($data);
  }

  /**
   * Get Image Dimensions
   *
   * @param $file
   * @return array|false
   */
  public static function image_getdimension($file)
  {
    $size = @getimagesize($file);

    if ($size[0] != 0 || $size[1] != 0) {
      return $size;
    }

    // Try to get the Dimension manually, depending on the mimetype
    $fp = @fopen($file, 'rb');
    if (!$fp) {
      return $size;
    }

    $error = false;

    // BMP - IMAGE

    $tmp_str = fread($fp, 2);
    if ($tmp_str == 'BM') {
      $length = self::read_longint($fp);

      if ($length <= 6) {
        $error = true;
      }

      if (!$error) {
        $i = self::read_longint($fp);
        if ($i != 0) {
          $error = true;
        }
      }

      if (!$error) {
        $i = self::read_longint($fp);

        if ($i != 0x3E && $i != 0x76 && $i != 0x436 && $i != 0x36) {
          $error = true;
        }
      }

      if (!$error) {
        $tmp_str = fread($fp, 4);
        $width = self::read_longint($fp);
        $height = self::read_longint($fp);

        if ($width > 3000 || $height > 3000) {
          $error = true;
        }
      }
    } else {
      $error = true;
    }

    if (!$error) {
      fclose($fp);
      return [
        $width,
        $height,
        6
      ];
    }

    $error = false;
    fclose($fp);

    // GIF - IMAGE

    $fp = @fopen($file, 'rb');

    $tmp_str = fread($fp, 3);

    if ($tmp_str == 'GIF') {
      $tmp_str = fread($fp, 3);
      $width = self::read_word($fp);
      $height = self::read_word($fp);

      $info_byte = fread($fp, 1);
      $info_byte = ord($info_byte);
      if (($info_byte & 0x80) != 0x80 && ($info_byte & 0x80) != 0) {
        $error = true;
      }

      if (!$error) {
        if (($info_byte & 8) != 0) {
          $error = true;
        }
      }
    } else {
      $error = true;
    }

    if (!$error) {
      fclose($fp);
      return [
        $width,
        $height,
        1
      ];
    }

    $error = false;
    fclose($fp);

    // JPG - IMAGE
    $fp = @fopen($file, 'rb');

    $tmp_str = fread($fp, 4);
    $w1 = self::read_word($fp);

    if ((int)$w1 < 16) {
      $error = true;
    }

    if (!$error) {
      $tmp_str = fread($fp, 4);
      if ($tmp_str == 'JFIF') {
        $o_byte = fread($fp, 1);
        if ((int)$o_byte != 0) {
          $error = true;
        }

        if (!$error) {
          $str = fread($fp, 2);
          $b = self::read_byte($fp);

          if ($b != 0 && $b != 1 && $b != 2) {
            $error = true;
          }
        }

        if (!$error) {
          $width = self::read_word($fp);
          $height = self::read_word($fp);

          if ($width <= 0 || $height <= 0) {
            $error = true;
          }
        }
      }
    } else {
      $error = true;
    }

    if (!$error) {
      fclose($fp);
      return [
        $width,
        $height,
        2
      ];
    }

    $error = false;
    fclose($fp);

    // PCX - IMAGE

    $fp = @fopen($file, 'rb');

    $tmp_str = fread($fp, 3);

    if ((ord($tmp_str[0]) == 10) && (ord($tmp_str[1]) == 0 || ord($tmp_str[1]) == 2 || ord($tmp_str[1]) == 3 || ord($tmp_str[1]) == 4 || ord($tmp_str[1]) == 5) && (ord($tmp_str[2]) == 1)) {
      $b = fread($fp, 1);

      if (ord($b) != 1 && ord($b) != 2 && ord($b) != 4 && ord($b) != 8 && ord($b) != 24) {
        $error = true;
      }

      if (!$error) {
        $xmin = self::read_word($fp);
        $ymin = self::read_word($fp);
        $xmax = self::read_word($fp);
        $ymax = self::read_word($fp);
        $tmp_str = fread($fp, 52);

        $b = fread($fp, 1);
        if ($b != 0) {
          $error = true;
        }
      }

      if (!$error) {
        $width = $xmax - $xmin + 1;
        $height = $ymax - $ymin + 1;
      }
    } else {
      $error = true;
    }

    if (!$error) {
      fclose($fp);
      return [
        $width,
        $height,
        7
      ];
    }

    fclose($fp);

    return $size;
  }
}
