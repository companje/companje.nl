6
<?php
var_dump(shell_exec('git pull  2>&1'));

require __DIR__ . '/vendor/autoload.php';

$page = trim($_SERVER['REQUEST_URI'], '/');

switch ($page) {
  case "": include "home.php"; break;
  case "notes": include "notes.php"; break;
  default: include "page.php";
}

?>

