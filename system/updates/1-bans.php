<?php

error_reporting(-1);

// Only allow it to run in CLI mode
if (php_sapi_name() !== 'cli') {
  echo("Run in CLI mode!");
  die();
}

$dbPath = realpath(dirname(__FILE__) . "/../includes/db.class.php");
require_once($dbPath);

$query = <<<END
  CREATE TABLE `bans` (
    `ip` varchar(15) NOT NULL DEFAULT '',
    `time` int(10) unsigned DEFAULT '0',
    PRIMARY KEY (`ip`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
END;

$db = new DBLayer();

if ($db->query($query)) {
  echo("Created bans table.\n");
}
else {
  echo("Failed creating bans table.\n");
}
