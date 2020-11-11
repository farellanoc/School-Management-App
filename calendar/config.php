<?php
require('vendor/autoload.php');

use Dcblogdev\PdoWrapper\Database;

$options = [
    'host' => "localhost",
    'database' => "p6ddbb",
    'username' => "root",
    'password' => ""
];
$db = new Database($options);

$dir = "./";
