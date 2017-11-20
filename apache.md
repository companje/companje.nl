---
title: ========= Apache =========
---

=====create Virtual Hosts on Ubuntu=====
* https://www.digitalocean.com/community/tutorials/how-to-set-up-apache-virtual-hosts-on-ubuntu-14-04-lts
```
sudo a2ensite gerard.companje.nl.conf
```

=====good tutorial to get Apache & PHP (& mySQL) working on OSX=====
* https://coolestguidesontheplanet.com/get-apache-mysql-php-phpmyadmin-working-osx-10-10-yosemite/

=====El Capitan Apache error message AH00526: Syntax error on line 20 of /private/etc/apache2/extra/httpd-mpm.conf: Invalid command 'LockFile'....=====
http://apple.stackexchange.com/questions/211015/el-capitan-apache-error-message-ah00526
```
cd /etc/apache2/extra
sudo mv httpd-mpm.conf httpd-mpm.conf.elcapitan
sudo mv httpd-mpm.conf~orig httpd-mpm.conf
sudo apachectl restart
apachectl configtest
```

=====tips=====
http://coolestguidesontheplanet.com/set-virtual-hosts-apache-mac-osx-10-10-yosemite/#apacheuser

=====fix permissions on OSX=====
  cd ~/Sites/yoursite
  sudo chown -R _www .
  sudo chmod -R g+w .
  
<del>=====group settings=====
  sudo dseditgroup -o edit -a rick -t user _www    # become a member of the _www group
  sudo chgrp -R _www .    # set group of current folder and subfolders to _www
</del>

=====Apache guide on OSX=====
* http://coolestguidesontheplanet.com/get-apache-mysql-php-phpmyadmin-working-osx-10-10-yosemite/

=====debug your virtual host configuration=====
  /usr/sbin/httpd -S
  
=====follow apache log=====
<code>tail -f /private/var/log/apache2/error_log</code>

=====Symbolic link not allowed or link target not accessible=====
Solution: https://romaimperator.com/?p=9
Rights need to be set for every folder (also parent folders):
<code>
chmod a+x /Users/rick/Documents/Doodle3D/3dprintserver
chmod a+x /Users/rick/Documents/Doodle3D 
chmod a+x /Users/rick/Documents 
</code>

=====http status info=====
<code>httpd -S</code>
on ubuntu: 
```
apachectl -S
```

=====create virtual host on mac=====
* add hostname to /etc/hosts (pointing to 127.0.0.1)
 * check location of httpd config:
<code>
/usr/sbin/httpd -V |grep SERVER_CONFIG
</code>
* edit httpd.conf (probably at: ''/private/etc/apache2/httpd.conf''): uncomment the include for Virtual Host
* edit httpd-vhosts.conf (probably at: ''/private/etc/apache2/extra/httpd-vhosts.conf'': add a virtual host like:
<code>
<VirtualHost *:80>
    DocumentRoot "/Users/rick/Sites/lepetitgarage"
    ServerName lepetitgarage.nl
</VirtualHost>
</code>
* restart apace
<code>
sudo apachectl stop && sudo apachectl restart
</code>

=====autostart apache on mac=====
* http://brettterpstra.com/fixing-virtual-hosts-and-web-sharing-in-mountain-lion/

=====httpd per virtual host=====
''/usr/local/directadmin/data/users/''

=====meerdere logfiles monitoren=====
* http://www.fr3nd.net/projects/apache-top/
* ''<Location /server-status>'' aanzetten.
* alleen access van localhost en external ip toestaan

=====httpd info=====
/usr/sbin/httpd -V

=====find which httpd.conf=====
<code>
/usr/sbin/httpd -V |grep SERVER_CONFIG
</code>

=====autostart wamp server on windows=====
Run services.msc
Set wampapache and wampmysqld to Startup type 'Automatic'

=====htaccess=====
zie ook [[htaccess]]

=====htaccess ignored on osx=====
make sure that 'AllowOverride All' is set in `/private/etc/apache2/httpd.conf` and/or `/etc/apache2/users/USERNAME.conf`

=====apache opnieuw opstarten=====
<code bash>
sudo /etc/init.d/httpd restart
</code>

=====op mac=====
<code bash>
sudo apachectl stop
sudo apachectl start
</code>

=====default site op onze server=====
<code>
/var/www/html
</code>

=====Allow access to the webserver through the network=====
allow access from other computers than localhost
turn off 'deny from all' in httpd.conf
<code apache>
#Deny from all
</code>

=====virtual hosts=====
Virtual hosts kun je toevoegen in:
<code>
apache/conf/extra/httpd-vhosts.conf
</code>
but be sure to turn on the include in httpd.conf
<code apache>
#Virtual hosts
Include conf/extra/httpd-vhosts.conf
</code>

=====Virtualhost: Forbidden, You don't have permission to access / on this server=====
The problem is that the extra/httpd-vhosts.conf is missing the directive to allow access to the directory.
Allow access by adding a <directory> section inside the <vhost> section.
<code>
<directory /vhost_document_root>
allow from all
</directory>
</code>

=====Virtualhost: Forbidden, You don't have permission to access / on this server=====
This can also be caused by a wrong DocumentRoot and directory settings for the DocumentRoot. Check the ''/private/etc/apache2/httpd.conf'' file  and search for DocumentRoot. Make sure the paths are set to the right location.
  DocumentRoot "/Users/rick/Sites/"
  <Directory "/Users/rick/Sites/">

=====error_log=====
location of the error_log file:
<code>
tail -f /var/log/httpd/error_log
</code>

/var/log/apache2/error.log