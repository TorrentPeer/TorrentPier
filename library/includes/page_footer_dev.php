<?php
/**
 * TorrentPier – Bull-powered BitTorrent tracker engine
 *
 * @copyright Copyright (c) 2005-2022 TorrentPier (https://torrentpier.site)
 * @link      https://github.com/TorrentPeer/TorrentPier for the canonical source repository
 * @license   https://github.com/TorrentPeer/TorrentPier/blob/main/LICENSE MIT License
 */

if (!defined('BB_ROOT')) {
  die(basename(__FILE__));
}
?>

<style type="text/css">
  .sqlLog {
    clear: both;
    font-family: Courier, monospace;
    font-size: 12px;
    white-space: nowrap;
    background: #F5F5F5;
    border: 1px solid #BBC0C8;
    overflow: auto;
    width: 98%;
    margin: 0 auto;
    padding: 2px 4px;
  }

  .sqlLogTitle {
    font-weight: bold;
    color: #444444;
    font-size: 11px;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    padding-bottom: 2px;
  }

  .sqlLogRow {
    background-color: #F5F5F5;
    padding-bottom: 1px;
    border: solid #F5F5F5;
    border-width: 0 0 1px 0;
    cursor: pointer;
  }

  .sqlLogWrapped {
    white-space: normal;
    overflow: visible;
  }

  .sqlExplain {
    color: #B50000;
    font-size: 13px;
    cursor: default;
  }

  .sqlHover {
    border-color: #8B0000;
  }

  .sqlHighlight {
    background: #FFE4E1;
  }
</style>

<?php

if (!empty($_COOKIE['explain'])) {
  foreach ($DBS->srv as $srv_name => $db_obj) {
    if (!empty($db_obj->do_explain)) {
      $db_obj->explain('display');
    }
  }
}

$sql_log = !empty($_COOKIE['sql_log']) ? \TorrentPier\Legacy\Dev::get_sql_log() : '';

if ($sql_log) {
  echo '<div class="sqlLog" id="sqlLog">' . ($sql_log ?: '') . '</div><!-- / sqlLog --><br clear="all" />';
}
?>

<script type="text/javascript">
  function fixSqlLog() {
    if ($("#sqlLog").height() > 400) {
      $("#sqlLog").height(400);
    }
  }

  $(document).ready(fixSqlLog);
</script>
