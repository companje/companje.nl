---
title: Wordpress
---

# "The Link You Followed Has Expired"
* ‘The link you followed has expired’ error usually occurs when you are trying to upload a WordPress theme or a plugin that is bigger than the file size limits"
* check current max size at Media > Add New Media File. (in my case 8MB)
* https://www.wpbeginner.com/wp-tutorials/how-to-fix-the-link-you-followed-has-expired-error-in-wordpress/

# WordPress development environment on Mac with Brew, Nginx, PHP 7, PHP-FPM, MariaDB, phpMyAdmin and more
<https://gist.github.com/remcotolsma/a04ed7165f04a307c02808e45c0596f3>
Dit zou nog wel eens kunnen werken. Nog uitproberen.

#  WP-CLI 
WP-CLI is a set of command-line tools for managing WordPress installations. You can update plugins, configure multisite installs and much more, without using a web browser.
http://wp-cli.org/

Quickstart: https://make.wordpress.org/cli/handbook/quick-start/

```
Wordpress CLI 
$ source  /Applications/XAMPP/ds-plugins/ds-cli/platform/mac/boot.sh
$ wp core install --url=www.doodle3d.dev --title=Doodle3D --admin_user=doodle3d --admin_email=www-doodle3d-dev@companje.nl
$ wp --info
PHP binary:	/Applications/XAMPP/xamppfiles/bin/php-5.5.24
PHP version:	5.5.24
php.ini used:	/Applications/XAMPP/xamppfiles/etc/php.ini
WP-CLI root dir:	/Applications/XAMPP/ds-plugins/ds-cli/vendor/wp-cli/wp-cli
WP-CLI packages dir:
WP-CLI global config:
WP-CLI project config:
WP-CLI version:	1.0.0


$ source /Users/rick/Documents/Doodle3D/wordpress/wp-completion.bash  #from: https://raw.githubusercontent.com/wp-cli/wp-cli/master/utils/wp-completion.bash

$ wp plugin install akismet --activate

$ wp plugin install woocommerce
$ wp plugin activate woocommerce

$ wp plugin install enfold
Segmentation fault: 11
$ wp plugin update --all
Segmentation fault: 11
```

# database (woocommerce) reports=
<code php>
<?php
header("Content-type: text/plain");

$db = mysql_connect('SERVER', 'USER', 'PASS');
mysql_select_db('DATABASE', $db);

echo("VAT Numbers
-------------------------------------
");
$sql = "SELECT post_id,meta_value FROM wp_postmeta WHERE meta_key='VAT Number'";
//$sql = "SELECT post_id,meta_value FROM wp_postmeta WHERE meta_key='_billing_country'";

$result = mysql_query($sql);

if (!$result) {
    $message  = 'Invalid query: ' . mysql_error() . "
";
    $message .= 'Whole query: ' . $sql;
    die($message);
}

while ($row = mysql_fetch_assoc($result)) {
    echo $row['post_id'] . " " . $row['meta_value'] . "
";
}
```

# "connection refused" in WooCommerce
when testing locally without HTTPS you can disable SSL otherwise all pages with personal info are served through HTTPS.
* Disable `Force secure checkout` in `WooCommerce > Settings > Checkout`

# site url
  define('WP_HOME','http://site');
  define('WP_SITEURL','http://site');
  
# fix permissions on OSX
  cd ~/Sites/yoursite
  sudo chown -R _www .
  sudo chmod -R g+w .
  
# Sync DB's
not tested: https://github.com/wp-sync-db/wp-sync-db

# Query Monitor debug plugin
https://wordpress.org/plugins/query-monitor/

# wordpress on localhost lamp doesn't let me install plugins
add this to wp-config.php. But don't use this on production servers.
  define('FS_METHOD','direct');

#  Themes 
* http://www.kriesi.at/
* [[http://themeforest.net/item/dante-responsive-multipurpose-wordpress-theme/6175269|Dante]]
* http://lifesplash.nl/
* http://wpshower.com/demo/?preview_theme=sight
* http://wpshower.com/demo/?preview_theme=imbalance-2
* http://www.adonit.net/jot/pro/ (not wordpress I think)

#  Facebook Page Photo Gallery plugin not showing thumbnails 
From https://wordpress.org/support/topic/thumbnails-didnt-load-any-more: Since WordPress 4.0 the thumbnails didn't load any more. So began searching for the loading of thumbnails in your code and tried commented out line 7 and 8 of single-album.php and line 3 and 4 of single-album-ajax.php and added
  $picture = $photo->picture;

#  Set file permissions 
https://wordpress.org/support/topic/auto-upgradeupdate-on-mac-os-x
On Mac OS X (Leopard), the Apache HTTP Server runs under the user account, _www which belongs to the group _www. To allow WordPress to configure wp-config.php during installation, update files during upgrades, and update the .htaccess file for pretty permalinks, give the server write permission on the files.

One way to do this is to change the owner of the wordpress directory and its contents to _www. Keep the group as staff, a group to which your user account belongs and give write permissions to the group.

  $ cd /<wherever>/Sites/<thesite>
  $ sudo chown -R _www wordpress
  $ sudo chmod -R g+w wordpress
This way, the WordPress directories have a permission level of 775 and files have a permission level of 664. No file nor directory is world-writeable.
