<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2023 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

if (!defined('BB_ROOT')) {
  die(basename(__FILE__));
}

\TorrentPier\Legacy\Logging::bb_log(date('H:i:s - ') . getmypid() . ' --x- SELECT jobs' . LOG_LF, CRON_LOG_DIR . '/cron_check');

// Get cron jobs
$cron_jobs = DB()->fetch_rowset("
	SELECT * FROM " . BB_CRON . "
	WHERE cron_active = 1
		AND next_run <= NOW()
	ORDER BY run_order
");

// Run cron jobs
if ($cron_jobs) {
  \TorrentPier\Legacy\Logging::bb_log(date('H:i:s - ') . getmypid() . ' --x- RUN jobs' . LOG_LF, CRON_LOG_DIR . '/cron_check');

  foreach ($cron_jobs as $job) {
    if ($job['disable_board']) {
      TorrentPier\Helpers\CronHelper::disableBoard();
      break;
    }
  }

  require(CRON_DIR . 'cron_run.php');

  // Update cron_last_check
  bb_update_config(['cron_last_check' => TIMENOW + 10]);
} else {
  \TorrentPier\Legacy\Logging::bb_log(date('H:i:s - ') . getmypid() . ' --x- no active jobs found ----------------------------------------------' . LOG_LF, CRON_LOG_DIR . '/cron_check');
}
