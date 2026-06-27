<?php
//ini_set('display_errors', 'On');
//ini_set( "short_open_tag", 1 );

require __DIR__ . '/vendor/autoload.php';

$page = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

switch ($page) {
  case "": include "home.php"; break;
  case "notes": include "notes.php"; break;
  default: include "page.php";
}

?>
