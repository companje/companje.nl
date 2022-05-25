<?php
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
              <h1 class="card-title">Notes</h1>

              <h2>Latest commits</h2>
<?php
$num_commits = 20;
$context = stream_context_create(['http' => ['method' => 'GET','header' => ['User-Agent: PHP']]]);
$url = "https://api.github.com/repos/companje/companje.nl/commits?per_page=$num_commits";
$json = json_decode(file_get_contents($url, false, $context));

foreach($json as $row) {
    $link = $row->html_url;
    $msg = $row->commit->message . date(' (d-m)', strtotime($row->commit->author->date));
    // echo date('d-m-Y: ', strtotime($row->commit->author->date));
    ?><span class="badge"><a href="<?=$link?>"><?=$msg?></a></span>
    <?php
}
?>
              <h2>All pages</h2>
                <?php
                foreach (glob("pages/*.md") as $p) {
                  $pg = preg_replace("/pages\/(.*).md/",'$1',$p)
                  ?><span class="badge"><a href="<?=$pg?>"><?=$pg?></a></span>
                <?php } ?>

            </div>

          </div>

        </div>
      </div>
  </div>

</main>

<?php include "footer.php" ?>
