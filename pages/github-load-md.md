---
title: companje.nl load md from github
---

zie ook:
* http://prismjs.com/download.html
* rate limit: https://developer.github.com/v3/#increasing-the-unauthenticated-rate-limit-for-oauth-applications


snapshot (nieuwste versie staat op server)
```html

<!DOCTYPE html>
<html>
<head>
	<link href="prism.css" rel="stylesheet" />
	<style>
		h1,h2,p,li { font-family: Abel, "Sans Serif", "Arial" }
		img { max-width: 640px; }
	</style>
</head>
<body>
<?php
ini_set('display_errors', 'On');

require('Parsedown.php');

$url = $_SERVER["REDIRECT_URL"];

$url = substr($url,1);

if (preg_match("/^[A-Za-z0-9-]+$/", $url)) {

	echo "<a href=\"https://github.com/companje/companje.github.io/edit/master/$url.md\">edit</a>";

	$md = file_get_contents("https://raw.githubusercontent.com/companje/companje.github.io/master/$url.md");
	$Parsedown = new Parsedown();
	echo $Parsedown->text($md);

} else {
    echo "404 $url";
    // https://api.github.com/repos/companje/companje.github.io/contents/
}


?>

	<script src="prism.js"></script>
</body>
</html>
```
