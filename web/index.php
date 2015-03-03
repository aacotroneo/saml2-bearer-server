<?php

require_once __DIR__.'/../vendor/autoload.php';
/** show all errors! */
ini_set('display_errors', 1);
error_reporting(E_ALL);

$config = include_once(__DIR__.'/../inst/config.php');
$app = new Aac\OAuth2\App($config);
