---
title: dokuwiki
---

#  markdown plugin 
* https://www.dokuwiki.org/plugin:markdowku

# Update plugin
after running an update you might need to 'touch' doku.php to get rid of the 'upgrade now' message. If this doesn't work remove data/cache/messages.txt

# TagCloud script
TagCloud script see [[d3]]

#  PageList script 
<code php>
<?php
class CommandPluginExtension_pagelist extends CommandPluginExtension {
  
  function getCachedData($embedding, $params, $paramHash, $content, &$errorMessage) {
    
////////////////////////////////////////////////
///////// sort by activity, show top 20
////////////////////////////////////////////////

    $files = glob('data/meta/*.changes');
    usort($files, function($a, $b) {
        return filesize($a) < filesize($b); //filesize($a)>1000 ? (filesize($a) < filesize($b)) : $a>$b;
    });

    foreach($files as $file) {
      $name = basename($file,'.changes');
      if ($name=='sidebar') continue;
      if ($name=='start') continue;

      $fontsize = filesize($file)/1000;
      $fontsize = ceil($fontsize*$fontsize+14);
      $fontsize = min($fontsize,25);
      
      $lines[] = "<div style='font-size:".$fontsize."px'><a href='$name'>$name</a></div>";
      // $lines[] = "<a style='float:right' href='$name'><font size='$fontsize'>$name</font>&nbsp;</a>";

      if ($i++>20) break;
    }

    $lines[] = "<br><br>";

////////////////////////////////////////////////
///////// sort by name, show all
////////////////////////////////////////////////

    $files = glob('data/meta/*.changes');
    foreach($files as $file) {
      $name = basename($file,'.changes');
      if ($name=='sidebar') continue;
      if ($name=='start') continue;
      $lines[] = "<div><a href='$name'>$name</a></div>";
    }

    return join($lines);

  }
}

?>
```
