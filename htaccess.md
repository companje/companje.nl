---
title: .htaccess
---

==Basic Auth==
<code apache>
AuthName "Login"
AuthGroupFile /dev/null
AuthUserFile /domains/....../.htpasswd
AuthType Basic
require valid-user
</code>

== redirect ==
<code apache>
RewriteEngine On

##############################
# redirect /doku.php?id=xxx  #
###############################
RewriteCond %{QUERY_STRING} ^id=(.*)$
RewriteRule ^doku\.php$ http://wiki.companje.nl/%1? [R=302,L]
##############################

##############################
# redirect if file not found #
##############################
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ http://wiki\.companje\.nl/$1 [L,R=301]
#############################
</code>

===== Examples in Dutch =====
http://andrescholten.nl/voorbeelden-veel-gebruikte-htaccess-regels/

===== internal server error while using 'Header add' =====
enable Apache's headers module:
  sudo a2enmod headers && sudo service apache2 restart

=====Access-Control-Allow-Origin=====
http://enable-cors.org/server_apache.html
or put this header in your php script:
<code php>
header('Access-Control-Allow-Origin: *');  
</code>


=====show php errors=====
<code apache>
# don't supress php errors
php_flag display_startup_errors on
php_flag display_errors on
php_flag html_errors on
</code>

=====disable cache=====
<code apache>
Header append Cache-Control "no-cache"
</code>

=====mod_rewrite cheat sheet=====
* http://www.cheatography.com/davechild/cheat-sheets/mod-rewrite/

=====download as attachment=====
<code apache>
<FilesMatch "\.(mov|mp4)$">
  Header set Content-Disposition attachment
</FilesMatch>
</code>

=====htaccess auth=====
<code apache>
AuthType Basic
AuthUserFile FOLDER/.htpasswd
<limit GET POST>
  require valid-user
</limit>
</code>

=====create SHA encrypted password for htpasswd=====
<code bash>
htpasswd -s FOLDER/.htpasswd USERNAME
</code>

=====redirect subdomain to other url=====
<code apache>
RewriteCond %{HTTP_HOST} ^subdomain\.domain\.com
RewriteRule (.*) YOUR_URL [R=301,L]
</code>

with subfolder in query: (place this before the code above)

<code apache>
RewriteCond %{HTTP_HOST} ^(www\.)?subdomain\.domain\.com
RewriteCond %{REQUEST_URI} ^/lijst
Rewriterule ^(.*)$ http://otherdomain.com [L]
</code>

=====htaccess ignored?=====
set 'AllowOverride' to 'All' in httpd.conf within <Directory>...

=====examples in dutch=====
* http://andrescholten.net/voorbeelden-veel-gebruikte-htaccess-regels/

=====redirect to other domain with keeping folder=====
<code Apache>
RewriteCond %{HTTP_HOST} =doodle3d.com
RewriteRule (.*) http://www.doodle3d.com/$1
</code>

=====redirect subdomain=====
<code Apache>
RewriteEngine on
RewriteBase /
RewriteCond %{HTTP_HOST} ^kc\.doodle3d\.nl$ [NC]
RewriteRule (.*) http://kunstcentraal.doodle3d.nl [R]
</code>

=====serve .csv as text mime type=====
<code Apache>
AddType text/plain .csv
</code>

=====Enable directory listing=====
<code Apache>
Options +Indexes
</code>

=====Disable directory listing=====
<code Apache>
Options -Indexes
</code>

=====Redirect if URL equals HTTP_HOST=====
<code Apache>
RewriteRule ^$ /nl [R=301,L]
</code>

=====Unprotect a subdir of a htaccess password-protected directory=====
create a .htaccess in the subfolder with this in it:
<code Apache>
Satisfy any
</code>

=====change max upload in PHP/ upload_max_filesize=====
edit your .htaccess:
<code Apache>
php_value upload_max_filesize 20M
php_value post_max_size 20M
php_value max_execution_time 200
php_value max_input_time 200
</code>