<?php
define("BASE_URL", 'http://localhost/ShopEcommerce');



$documentRoot = $_SERVER['DOCUMENT_ROOT'];
$projectName = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'))[0];

define("ROOT_DIR", $documentRoot . '/' . $projectName . '/');

$visiblePage = 7;
$perPage = 10;