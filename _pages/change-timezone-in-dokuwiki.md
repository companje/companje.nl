---
title: Change Timezone in DokuWiki
---
To change the timezone in DokuWiki add the following to you conf/local.php (or create local.protected.php when you're using the config plugin and you probably do):

In php4:

<code php>
putenv("TZ=Europe/Amsterdam");
</code>

More info here: [[http://wiki.splitbrain.org/wiki:tips:timezone|wiki.splitbrain.org]]

(tag>)


~~DISCUSSION~~
