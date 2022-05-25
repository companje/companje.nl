<?php
header("Content-type: text/plain");

//ini_set('display_errors', 'On');
//ini_set( "short_open_tag", 1 );

$num_commits = 20;
$context = stream_context_create(['http' => ['method' => 'GET','header' => ['User-Agent: PHP']]]);
$url = "https://api.github.com/repos/companje/companje.nl/commits?per_page=$num_commits";
$json = json_decode(file_get_contents($url, false, $context));

foreach($json as $row) {
    echo date('d-m-Y: ', strtotime($row->commit->author->date));
    echo $row->commit->message;
    echo " ";
    echo "\n";
}

?>

