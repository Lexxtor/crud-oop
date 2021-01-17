<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../app/App.php';
require __DIR__ . '/../app/DB.php';
require __DIR__ . '/../app/Model.php';
require __DIR__ . '/../app/ModelNew.php';
$config = require __DIR__ . '/../config.php';

App::getInstance($config)->run();

