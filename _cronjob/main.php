<?php
error_reporting(E_ALL);
require_once 'inc/init.php';
$url = 'https://xdviet.com';
get('auto');
file_put_contents(__DIR__.'/cronjob.txt', date('Y-m-d H:i:s'). PHP_EOL, FILE_APPEND);
echo 'OK';
