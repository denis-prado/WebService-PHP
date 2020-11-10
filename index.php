<?php

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/vendor/adodb/adodb-php/adodb.inc.php';

use SRC\Controllers\Controller;

$controller = new Controller;
$response = $controller->getRespose();

print_r($response);
