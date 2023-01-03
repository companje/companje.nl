---
title: .htaccess
---

# .htaccess: Invalid command 'ExpiresActive', perhaps misspelled or defined by a module not included in the server configuration
```bash
ln -s /etc/apache2/mods-available/expires.load /etc/apache2/mods-enabled/
# or
sudo a2enmod expires
```

# Directory index
```apache
DirectoryIndex first.html
```

# CORS
```apache
<Files "*.json">
Header add Access-Control-Allow-Origin "*"
</Files>
```

# redirect to index.php in paged/ folder
```apache
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /paged/
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /paged/index.php [L]
</IfModule>
```
then to extract /:page use:
```php
preg_match('/^\/(.*)/',$_SERVER["REDIRECT_URL"], $matches, PREG_OFFSET_CAPTURE);
$page = explode("/",$matches[1][0])[1];
```

# list files directoryIndex
```apache
Options +Indexes
```

# force download
```apache
<FilesMatch "\.d3sketch$">
        ForceType application/octet-stream
        Header set Content-Disposition attachment
</FilesMatch>
```

# (pre-compressed) gzip 
```apache
AddEncoding gzip .gz
```

# Access-Control-Allow-Origin * with X-Requested-With
```apache
Header set Access-Control-Allow-Origin "*"
Header set Access-Control-Allow-Headers "Origin, X-Requested-With, Content-Type, Accept"
```

# Basic Auth
```apache
AuthType Basic
AuthName "Restricted Files"
AuthUserFile /FOLDER/.htpasswd
<limit GET POST>
  require valid-user
</limit>
```

#  redirect 
```apache
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
```

#  Examples in Dutch 
http://andrescholten.nl/voorbeelden-veel-gebruikte-htaccess-regels/

#  internal server error while using 'Header add' 
enable Apache's headers module:
  sudo a2enmod headers && sudo service apache2 restart

# Access-Control-Allow-Origin
http://enable-cors.org/server_apache.html
or put this header in your php script:
```php
header('Access-Control-Allow-Origin: *');  
```


# show php errors
```apache
# don't supress php errors
php_flag display_startup_errors on
php_flag display_errors on
php_flag html_errors on
```

# disable cache
```apache
Header append Cache-Control "no-cache"
```

# mod_rewrite cheat sheet
* http://www.cheatography.com/davechild/cheat-sheets/mod-rewrite/

# download as attachment
```apache
<FilesMatch "\.(mov|mp4)$">
  Header set Content-Disposition attachment
</FilesMatch>
```

# create SHA encrypted password for htpasswd
```bash
htpasswd -s FOLDER/.htpasswd USERNAME
```

# redirect subdomain to other url
```apache
RewriteCond %{HTTP_HOST} ^subdomain\.domain\.com
RewriteRule (.*) YOUR_URL [R=301,L]
```

with subfolder in query: (place this before the code above)

```apache
RewriteCond %{HTTP_HOST} ^(www\.)?subdomain\.domain\.com
RewriteCond %{REQUEST_URI} ^/lijst
Rewriterule ^(.*)$ http://otherdomain.com [L]
```

# htaccess ignored?
set 'AllowOverride' to 'All' in httpd.conf within <Directory>...

# examples in dutch
* http://andrescholten.net/voorbeelden-veel-gebruikte-htaccess-regels/

# redirect to other domain with keeping folder
```apache
RewriteCond %{HTTP_HOST} =doodle3d.com
RewriteRule (.*) http://www.doodle3d.com/$1
```

# redirect subdomain
```apache
RewriteEngine on
RewriteBase /
RewriteCond %{HTTP_HOST} ^kc\.doodle3d\.nl$ [NC]
RewriteRule (.*) http://kunstcentraal.doodle3d.nl [R]
```

# serve .csv as text mime type
```apache
AddType text/plain .csv
```

# Enable directory listing
```apache
Options +Indexes
```

# Disable directory listing
```apache
Options -Indexes
```

# Redirect if URL equals HTTP_HOST
```apache
RewriteRule ^$ /nl [R=301,L]
```

# Unprotect a subdir of a htaccess password-protected directory
create a .htaccess in the subfolder with this in it:
```apache
Satisfy any
```

# change max upload in PHP/ upload_max_filesize
edit your .htaccess:
```apache
php_value upload_max_filesize 20M
php_value post_max_size 20M
php_value max_execution_time 200
php_value max_input_time 200
```
