<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Legacy;

use thomaswelton\GravatarLib\Gravatar;

/**
 * Class Avatar
 * @package TorrentPier\Legacy
 */
class Avatar
{
  /**
   * Get avatar path
   *
   * @param $id
   * @param $ext_id
   * @param null $base_path
   * @param int $first_div
   * @param int $sec_div
   * @return string
   */
  public static function getAvatarPath($id, $ext_id, $base_path = null, $first_div = 10000, $sec_div = 100): string
  {
    global $bb_cfg;
    $base_path = $base_path ?? $bb_cfg['avatars']['upload_path'];
    return get_path_from_id($id, $ext_id, $base_path, $first_div, $sec_div);
  }

  /**
   * Remove current avatar
   *
   * @param $user_id
   * @param $avatar_ext_id
   * @return bool
   */
  public static function deleteAvatar($user_id, $avatar_ext_id): bool
  {
    $avatar_file = $avatar_ext_id ? self::getAvatarPath($user_id, $avatar_ext_id) : '';
    return ($avatar_file && file_exists($avatar_file)) ? @unlink($avatar_file) : false;
  }

  /**
   * Get avatar
   *
   * @param $is_group
   * @param $user_id
   * @param $ext_id
   * @param bool $allow_avatar
   * @param string $height
   * @param string $width
   * @return string
   * @throws \Exception
   */
  public static function getAvatar($is_group, $user_id, $ext_id, $allow_avatar = true, $height = '', $width = ''): string
  {
    global $bb_cfg;

    $height = $height ? 'height="' . $height . '"' : '';
    $width = $width ? 'width="' . $width . '"' : '';

    $user_avatar = '<img src="' . self::avatarMethod($user_id, $is_group) . '" alt="' . $user_id . '" ' . $height . ' ' . $width . ' />';

    if ($user_id == BOT_UID && $bb_cfg['avatars']['bot_avatar']) {
      $user_avatar = '<img src="' . make_url($bb_cfg['avatars']['display_path'] . $bb_cfg['avatars']['bot_avatar']) . '" alt="' . $user_id . '" ' . $height . ' ' . $width . ' />';
    } elseif ($allow_avatar && $ext_id) {
      if (file_exists(self::getAvatarPath($user_id, $ext_id))) {
        $user_avatar = '<img src="' . make_url(self::getAvatarPath($user_id, $ext_id, $bb_cfg['avatars']['display_path'])) . '" alt="' . $user_id . '" ' . $height . ' ' . $width . ' />';
      }
    }

    return $user_avatar;
  }

  /**
   * Avatar method
   *
   * @param $user_id
   * @param $is_group
   * @return string
   * @throws \Exception
   */
  private static function avatarMethod($user_id, $is_group): string
  {
    global $bb_cfg;

    // Default avatar (Group & User)
    $avatar = make_url($bb_cfg['avatars']['display_path'] . $bb_cfg['avatars']['no_avatar']);

    // Avatar provider (User only)
    if ($bb_cfg['avatars']['avatar_provider']['enabled'] && $is_group == false) {
      // Get user email
      $sql = "SELECT user_email FROM " . BB_USERS . " WHERE user_id = $user_id";
      if ($row = DB()->fetch_row($sql)) {
        // Gravatar service
        $gravatar = new Gravatar();
        $gravatar->setDefaultImage($bb_cfg['avatars']['avatar_provider']['default_avatar']);
        $gravatar->setAvatarSize($bb_cfg['avatars']['max_height']);
        $gravatar->setMaxRating($bb_cfg['avatars']['avatar_provider']['rating']);

        $avatar = $gravatar->buildGravatarURL($row['user_email']);
      }
    }

    return $avatar;
  }
}
