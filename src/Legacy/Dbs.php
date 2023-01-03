<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2023 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

namespace TorrentPier\Legacy;

use Exception;
use function is_object;

/**
 * Class Dbs
 * @package TorrentPier\Legacy
 */
class Dbs
{
  private $drivers_allowed = ['mysql', 'postgresql', 'sqlite'];
  private $driver;

  public $cfg = [];
  public $srv = [];

  public $log_file = 'sql_queries';
  public $log_counter = 0;
  public $num_queries = 0;
  public $sql_inittime = 0;
  public $sql_timetotal = 0;

  /**
   * Dbs constructor
   *
   * @param $cfg
   * @throws Exception
   */
  public function __construct($cfg)
  {
    $this->cfg = $cfg['db'];

    $this->driver = $this->cfg['driver'];

    if (!in_array($this->driver, $this->drivers_allowed)) {
      bb_simple_die("SQL driver ({$this->driver}) not supported");
    }

    foreach ($this->cfg as $srv_name => $srv_cfg) {
      $this->srv[$srv_name] = null;
    }
  }

  /**
   * Получение / инициализация класса сервера $srv_name
   *
   * @return mixed
   * @throws Exception
   */
  public function get_db_obj()
  {
    if (!is_object($this->srv[$this->driver])) {
      $this->srv[$this->driver] = new SqlDb($this->driver, $this->cfg[$this->driver]);
    }

    return $this->srv[$this->driver];
  }
}
