<?php
//ini_set('display_errors', 'On');
//ini_set( "short_open_tag", 1 );


if (isset($_SERVER['HTTP_X_GITHUB_EVENT']) && $_SERVER['HTTP_X_GITHUB_EVENT'] == 'push') {
  shell_exec('git pull');
  //file_put_contents("test.txt", "test2");
  //die();
}

require __DIR__ . '/vendor/autoload.php';

//$page = trim($_SERVER['REQUEST_URI'], '/');
$page = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

switch ($page) {
  case "": include "home.php"; break;
  case "notes": include "notes.php"; break;
  default: include "page.php";
}

?>

