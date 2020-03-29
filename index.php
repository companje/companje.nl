9
<?php
if ($_SERVER['HTTP_X_GITHUB_EVENT'] == 'push') {
  shell_exec('git pull  2>&1');
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

