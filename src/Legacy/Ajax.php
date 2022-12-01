<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Legacy;

use TorrentPier\Helpers\IsHelper;

/**
 * Class Ajax
 * @package TorrentPier\Legacy
 */
class Ajax
{
  private $request = [];
  private $response = [];

  private $valid_actions = [
    // ACTION NAME         AJAX_AUTH
    'edit_user_profile' => ['admin'],
    'change_user_rank' => ['admin'],
    'change_user_opt' => ['admin'],
    'manage_user' => ['admin'],
    'manage_admin' => ['admin'],
    'sitemap' => ['admin'],

    'mod_action' => ['mod'],
    'topic_tpl' => ['mod'],
    'group_membership' => ['mod'],
    'post_mod_comment' => ['mod'],

    'avatar' => ['user'],
    'gen_passkey' => ['user'],
    'change_torrent' => ['user'],
    'change_tor_status' => ['user'],
    'manage_group' => ['user'],
    'new_pm' => ['user'],
    'reputation' => ['user'],
    'callseed' => ['user'],

    'view_post' => ['guest'],
    'view_torrent' => ['guest'],
    'user_register' => ['guest'],
    'posts' => ['guest'],
    'index_data' => ['guest'],
    'users_today' => ['guest'],
  ];

  public $action;

  /**
   * Constructor
   */
  public function __construct()
  {
    ob_start([&$this, 'ob_handler']);
    header('Content-Type: text/plain');
  }

  /**
   * Perform action
   */
  public function exec()
  {
    global $lang;

    // Exit if we already have errors
    if (!empty($this->response['error_code'])) {
      $this->send();
    }

    // Check that requested action is valid
    $action = $this->action;

    if (!$action || !\is_string($action)) {
      $this->ajax_die('no action specified');
    } elseif (!$action_params =& $this->valid_actions[$action]) {
      $this->ajax_die('invalid action: ' . $action);
    }

    // Auth check
    switch ($action_params[AJAX_AUTH]) {
      // GUEST
      case 'guest':
        break;

      // USER
      case 'user':
        if (IS_GUEST) {
          $this->ajax_die($lang['NEED_TO_LOGIN_FIRST']);
        }
        break;

      // MOD
      case 'mod':
        if (!IS_AM) {
          $this->ajax_die($lang['ONLY_FOR_MOD']);
        }
        $this->check_admin_session();
        break;

      // ADMIN
      case 'admin':
        if (!IS_ADMIN) {
          $this->ajax_die($lang['ONLY_FOR_ADMIN']);
        }
        $this->check_admin_session();
        break;

      // SUPER_ADMIN
      case 'super_admin':
        if (!IS_SUPER_ADMIN) {
          $this->ajax_die($lang['ONLY_FOR_SUPER_ADMIN']);
        }
        $this->check_admin_session();
        break;

      default:
        bb_simple_die("Invalid auth type for $action");
    }

    // Run action
    $this->$action();

    // Send output
    $this->send();
  }

  /**
   * Exit on error
   *
   * @param $error_msg
   * @param int $error_code
   */
  public function ajax_die($error_msg, $error_code = E_AJAX_GENERAL_ERROR)
  {
    $this->response['error_code'] = $error_code;
    $this->response['error_msg'] = $error_msg;

    $this->send();
  }

  /**
   * Initialization
   *
   * @return bool
   */
  public function init(): bool
  {
    if (!IsHelper::is_ajax()) {
      return false;
    }

    $this->request = $_POST;
    $this->action =& $this->request['action'];

    return true;
  }

  /**
   * Send data
   */
  private function send()
  {
    $this->response['action'] = $this->action;

    if (Dev::sql_dbg_enabled()) {
      $this->response['sql_log'] = Dev::get_sql_log();
    }

    // sending output will be handled by $this->ob_handler()
    exit;
  }

  /**
   * OB Handler
   *
   * @param $contents
   * @return string
   */
  public function ob_handler($contents)
  {
    if (DBG_USER) {
      if ($contents) {
        $this->response['raw_output'] = $contents;
      }
    }

    $response_js = json_encode($this->response);

    if (GZIP_OUTPUT_ALLOWED && !\defined('NO_GZIP')) {
      if (UA_GZIP_SUPPORTED && \strlen($response_js) > 2000) {
        header('Content-Encoding: gzip');
        $response_js = gzencode($response_js, 1);
      }
    }

    return $response_js;
  }

  /**
   * Admin session
   */
  public function check_admin_session()
  {
    global $user;

    if (!$user->data['session_admin']) {
      if (empty($this->request['user_password'])) {
        $this->prompt_for_password();
      } else {
        $login_args = [
          'login_username' => $user->data['username'],
          'login_password' => $_POST['user_password'],
        ];
        if (!$user->login($login_args, true)) {
          $this->ajax_die('Wrong password');
        }
      }
    }
  }

  /**
   * Prompt for password
   */
  public function prompt_for_password()
  {
    $this->response['prompt_password'] = 1;
    $this->send();
  }

  /**
   * Prompt for confirmation
   *
   * @param string $confirm_msg
   */
  public function prompt_for_confirm(string $confirm_msg)
  {
    if (empty($confirm_msg)) {
      $this->ajax_die('false');
    }

    $this->response['prompt_confirm'] = 1;
    $this->response['confirm_msg'] = $confirm_msg;
    $this->send();
  }

  /**
   * Verify mod rights
   *
   * @param int $forum_id
   */
  public function verify_mod_rights($forum_id)
  {
    global $userdata, $lang;

    $is_auth = auth(AUTH_MOD, $forum_id, $userdata);

    if (!$is_auth['auth_mod']) {
      $this->ajax_die($lang['ONLY_FOR_MOD']);
    }
  }

  /**
   * Edit user profile actions
   */
  public function edit_user_profile()
  {
    require AJAX_DIR . '/edit_user_profile.php';
  }

  /**
   * Change rank
   */
  public function change_user_rank()
  {
    require AJAX_DIR . '/change_user_rank.php';
  }

  /**
   * Change opt
   */
  public function change_user_opt()
  {
    require AJAX_DIR . '/change_user_opt.php';
  }

  /**
   * Generate a passkey
   */
  public function gen_passkey()
  {
    require AJAX_DIR . '/gen_passkey.php';
  }

  /**
   * Group membership actions
   */
  public function group_membership()
  {
    require AJAX_DIR . '/group_membership.php';
  }

  /**
   * Manage group actions
   */
  public function manage_group()
  {
    require AJAX_DIR . '/edit_group_profile.php';
  }

  /**
   * Post moderator comment
   */
  public function post_mod_comment()
  {
    require AJAX_DIR . '/post_mod_comment.php';
  }

  /**
   * View post
   */
  public function view_post()
  {
    require AJAX_DIR . '/view_post.php';
  }

  /**
   * Change torrent status actions
   */
  public function change_tor_status()
  {
    require AJAX_DIR . '/change_tor_status.php';
  }

  /**
   * Change torrent actions
   */
  public function change_torrent()
  {
    require AJAX_DIR . '/change_torrent.php';
  }

  /**
   * View torrent
   */
  public function view_torrent()
  {
    require AJAX_DIR . '/view_torrent.php';
  }

  /**
   * User register validate actions
   */
  public function user_register()
  {
    require AJAX_DIR . '/user_register.php';
  }

  /**
   * Moderator actions
   */
  public function mod_action()
  {
    require AJAX_DIR . '/mod_action.php';
  }

  /**
   * Posts actions
   */
  public function posts()
  {
    require AJAX_DIR . '/posts.php';
  }

  /**
   * User managing
   */
  public function manage_user()
  {
    require AJAX_DIR . '/manage_user.php';
  }

  /**
   * Admin managing
   */
  public function manage_admin()
  {
    require AJAX_DIR . '/manage_admin.php';
  }


  public function topic_tpl()
  {
    require AJAX_DIR . '/topic_tpl.php';
  }

  /**
   * Index data
   */
  public function index_data()
  {
    require AJAX_DIR . '/index_data.php';
  }

  /**
   * Avatar actions
   */
  public function avatar()
  {
    require AJAX_DIR . '/avatar.php';
  }

  /**
   * Sitemap actions
   */
  public function sitemap()
  {
    require AJAX_DIR . '/sitemap.php';
  }

  /**
   * User who visited today
   */
  public function users_today()
  {
    require AJAX_DIR . '/users_today.php';
  }

  /**
   * Dynamic PM
   */
  public function new_pm()
  {
    require AJAX_DIR . '/new_pm.php';
  }

  /**
   * Reputation
   */
  public function reputation()
  {
    require AJAX_DIR . '/reputation.php';
  }

  /**
   * Call seeder
   */
  public function callseed()
  {
    require AJAX_DIR . '/callseed.php';
  }
}
