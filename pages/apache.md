---
title: Apache 
---

# SSLCertificateFile: file '/etc/letsencrypt/live/.....' does not exist or is empty
Solution:
```bash
sudo chmod 755 /etc/letsencrypt/archive
sudo chmod 755 /etc/letsencrypt/live/
apachectl configtest
```
Syntax OK

# let's encrypt
```bash
sudo certbot --apache
```
* ubuntu 20.04  https://www.digitalocean.com/community/tutorials/how-to-secure-apache-with-let-s-encrypt-on-ubuntu-20-04 
* ubuntu 18.04 https://www.digitalocean.com/community/tutorials/how-to-secure-apache-with-let-s-encrypt-on-ubuntu-18-04 

# 'Error: mysite.com does not exist' when using a2ensite

cause:
```sudo a2ensite mysite.com```

solution:
add `.conf` to the filename!

a2ensite is simply a perl script that only works with filenames ending .conf

# /etc/apache2/sites-enabled
in this folder (`/etc/apache2/sites-enabled`) small .conf files per site are placed.

# find apache2.conf
```bash
/usr/sbin/apache2 -V | grep SERVER_CONFIG_FILE
```

# create Virtual Hosts on Ubuntu
<https://www.digitalocean.com/community/tutorials/how-to-set-up-apache-virtual-hosts-on-ubuntu-14-04-lts>
```bash
sudo a2ensite gerard.companje.nl.conf
```

# good tutorial to get Apache & PHP (& mySQL) working on OSX
* <https://coolestguidesontheplanet.com/get-apache-mysql-php-phpmyadmin-working-osx-10-10-yosemite/>

# Invalid command 'LockFile'
El Capitan Apache error message AH00526: Syntax error on line 20 of /private/etc/apache2/extra/httpd-mpm.conf: Invalid command 'LockFile'....
http://apple.stackexchange.com/questions/211015/el-capitan-apache-error-message-ah00526
```bash
cd /etc/apache2/extra
sudo mv httpd-mpm.conf httpd-mpm.conf.elcapitan
sudo mv httpd-mpm.conf~orig httpd-mpm.conf
sudo apachectl restart
apachectl configtest
```

# tips
<http://coolestguidesontheplanet.com/set-virtual-hosts-apache-mac-osx-10-10-yosemite/#apacheuser>

# fix permissions on OSX
```bash
cd ~/Sites/yoursite
sudo chown -R _www .
sudo chmod -R g+w .
```

<del># group settings
  sudo dseditgroup -o edit -a rick -t user _www    # become a member of the _www group
  sudo chgrp -R _www .    # set group of current folder and subfolders to _www
</del>

# Apache guide on OSX
* <http://coolestguidesontheplanet.com/get-apache-mysql-php-phpmyadmin-working-osx-10-10-yosemite/>

# debug your virtual host configuration
  /usr/sbin/httpd -S
  
# follow apache log
```tail -f /private/var/log/apache2/error_log```

# Symbolic link not allowed or link target not accessible
Solution: <https://romaimperator.com/?p=9>
Rights need to be set for every folder (also parent folders):

```bash
chmod a+x /Users/rick/Documents/Doodle3D/3dprintserver
chmod a+x /Users/rick/Documents/Doodle3D 
chmod a+x /Users/rick/Documents 
```

# http status info
```bash
httpd -S
```

on ubuntu: 
```bash
apachectl -S
```

# create virtual host on mac
* add hostname to /etc/hosts (pointing to 127.0.0.1)
* check location of httpd config:
```bash
/usr/sbin/httpd -V | grep SERVER_CONFIG
```

* edit httpd.conf (probably at: ''/private/etc/apache2/httpd.conf''): uncomment the include for Virtual Host
* edit httpd-vhosts.conf (probably at: ''/private/etc/apache2/extra/httpd-vhosts.conf'': add a virtual host like:

```xml
<VirtualHost *:80>
    DocumentRoot "/Users/rick/Sites/lepetitgarage"
    ServerName lepetitgarage.nl
</VirtualHost>
```

# restart apache
`sudo apachectl stop && sudo apachectl restart`

# autostart apache on mac
* http://brettterpstra.com/fixing-virtual-hosts-and-web-sharing-in-mountain-lion/

# httpd per virtual host
`/usr/local/directadmin/data/users/`

# meerdere logfiles monitoren
* http://www.fr3nd.net/projects/apache-top/
* ''<Location /server-status>'' aanzetten.
* alleen access van localhost en external ip toestaan

# httpd info
/usr/sbin/httpd -V

# find which httpd.conf
```
/usr/sbin/httpd -V |grep SERVER_CONFIG
```

# autostart wamp server on windows
Run services.msc
Set wampapache and wampmysqld to Startup type 'Automatic'

# htaccess
zie ook [[htaccess]]

# htaccess ignored on osx
make sure that 'AllowOverride All' is set in `/private/etc/apache2/httpd.conf` and/or `/etc/apache2/users/USERNAME.conf`

# apache opnieuw opstarten
```bash
sudo /etc/init.d/httpd restart
```

# op mac
```bash
sudo apachectl stop
sudo apachectl start
```

# default site op onze server
```conf
/var/www/html
```

# Allow access to the webserver through the network
allow access from other computers than localhost
turn off 'deny from all' in httpd.conf

# Virtual hosts
Virtual hosts kun je toevoegen in:
```conf
apache/conf/extra/httpd-vhosts.conf
```

but be sure to turn on the include in httpd.conf
```conf
Include conf/extra/httpd-vhosts.conf
```

# Virtualhost: Forbidden, You don't have permission to access / on this server
The problem is that the extra/httpd-vhosts.conf is missing the directive to allow access to the directory.
Allow access by adding a <directory> section inside the <vhost> section.
```xml
<directory /vhost_document_root>
allow from all
</directory>
```

# Virtualhost: Forbidden, You don't have permission to access / on this server
This can also be caused by a wrong DocumentRoot and directory settings for the DocumentRoot. Check the ''/private/etc/apache2/httpd.conf'' file  and search for DocumentRoot. Make sure the paths are set to the right location.
  DocumentRoot "/Users/rick/Sites/"
  <Directory "/Users/rick/Sites/">

# error_log
location of the error_log file:
```bash
tail -f /var/log/httpd/error_log
#/var/log/apache2/error.log
```

