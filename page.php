<?php
$url = "pages/$page.md";
$pages = glob("pages/*.md");

if (!in_array($url,$pages)) {
  header("Location: https://github.com/companje/companje.nl/new/master/pages?filename=".$page.".md");
  die();
}

$md = file_get_contents($url);

$parser = new Mni\FrontYAML\Parser();
$document = $parser->parse($md);
$yaml = $document->getYAML();   //ignored for now

$title = isset($yaml['title']) ? $yaml['title'] : $page ;


$html = $document->getContent();

$html = preg_replace('#<h1([^>]*)>(.*)</h1>#m','<h2$1>$2</h2>', $html); //h1 to h2

include "header.php" 
?>

<main class="page-content pt-4 mt-5" aria-label="Content">

  <!-- <div class="wrapper"> -->
  <div class="container">

     <div class="row">
        <div class="col-12">
          
          <div class="card">

            <div class="card-body">

              <!--Title-->
              <h1 class="card-title"><?=$title?></h1>

              <?=$html?>

            </div>

          </div>

        </div>
      </div>
  </div>

</main>

<?php include "footer.php" ?>

