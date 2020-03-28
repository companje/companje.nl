<?php
echo "2";
// ini_set('display_errors', 'On');
// die("test");

if (isset($_SERVER['HTTP_X_GITHUB_EVENT'])) {
  // if ( $_POST['payload'] ) {
  //if ($_SERVER['HTTP_X_GITHUB_EVENT'] == 'push') {
  var_dump(shell_exec('git pull'));
  die();
}

require __DIR__ . '/vendor/autoload.php';

$page = trim($_SERVER['REQUEST_URI'], '/');

switch ($page) {
  case "": include "home.php"; break;
  case "notes": include "notes.php"; break;
  default: include "page.php";
}

?>

