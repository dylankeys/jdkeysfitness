<?php

require_once(__DIR__ . '/config.php');

$db = new mysqli($CFG->dbhost, $CFG->dbuser, $CFG->dbpass, $CFG->dbname);