---
title: OpenPanel
---
  * A typical location for all log files is `/var/log` and subdirectories. 
  * `sudo /etc/init.d/apache2 start`
  * `ps -A | grep 'httpd'`
  * `pgrep apache`
  * `aptitude show apache2`
  * `/usr/sbin/apachectl status`
  * `sudo lsof -i :443`
  * `sudo lsof -i :80`
  * `sudo lsof -i:3306` (mysql)
  * `sudo lsof -i:4089`
  * `cat /etc/apache2/ports.conf`
  * `netstat -plan | grep :443`
  * `sudo apache2ctl configtest        #     Syntax OK`
  * `ps -e | grep apache`
  * `sudo iptables -L`
  * ??? tail -f /var
  * `tail -f /var/log/apache2/error.log`
  * # The Apache error log may have more information.   #   `cd /var/log/apache2`
  * `cat /var/log/apache2/error.log`
  * `sudo nano /etc/apache2/sites-available/default-ssl`
  * `/etc/apache2/apache2.conf`
  * `sudo apt-get purge openpanel.*`
  * `sudo -i`
  * `ls -la / | grep var`
  * `ls -la /var/ | grep lib`
  * `ls -la /var/lib/ | grep mysql`
  * `ps aux | grep openpanel`
  * `find . | xargs grep 'itemGridViewContents' -sl`
  * A commenter mentioned that you can install md5sum using HomeBrew by runningbrew install coreutils.
  * `sudo chown -R www-data:www-data wp-content`
  * OR even `sudo chown -R www-data:www-data public_html`
  * `/etc/apache2/conf.d/openpanel.conf`
  * enable apache modules: `sudo a2enmod rewrite` # for mod_rewrite.so
  * root@vps:/var/openpanel/modules/Apache2.module/apache2module.app/Contents/Configuration Defaults# `cat openpanel.module.apache2.conf.xml`

```
<openpanel.module.domain>
  <config>
    <varpath>/var/openpanel/conf/staging/Apache2/</varpath>
    <htservice:vhosts_dir>/etc/apache2/openpanel.d/</htservice:vhosts_dir>
    <htservice:name>apache2</htservice:name>
    <htservice:confdir>/etc/apache2</htservice:confdir>
    <htservice:conffile>apache2.conf</htservice:conffile>
    <phpini>/etc/php5/apache2/php.ini</phpini>
  </config>
</openpanel.module.domain>
```

  telnet localhost 80
  HEAD / HTTP/1.0
  <extra carriage return>

===== OpenPanel GUI render error with ItemGrid=====
I found a workaround for this issue by adding the following html/css to /var/openpanel/http/index.html. It's an ugly hack but it works for me.
<code css>
<style>
.itemGridViewContents[style] {
position: initial !important;
}
</style>
```

===== OpenPanel install your own SSL certificate for the admin panel =====
  * buy a certificate (in my case I ordered one through TransIP (Comodo Positive SSL 12,50 p/y. Request it, store the passphrase on a safe place, confirm the email, go back to transip to download the certificate).
  * upload two files to your home dir: `certificate.crt` and `certificate.key`
  * convert the encrypted key file to RSA format (right?): `openssl rsa  -in certificate.key`  (use the passphrase)
  * make backup of `/etc/ssl/private/ssl-openpanel.key`
  * replace the contents of `/etc/ssl/private/ssl-openpanel.key` by the new RSA private key just created.
  * make backup of `/etc/ssl/certs/ssl-openpanel.pem`
  * replace the contents of `/etc/ssl/certs/ssl-openpanel.pem` with the contents of `~/certificate.crt`
  * backup `/etc/openpanel/certificate.pem`
  * remove `/etc/openpanel/certificate.pem`
  * remove `certificate.crt` and `certificate.key` from your home folder
  * reboot

==== OpenPanel set SSL certificate for default HTTPS site (‘It works! page’ in /var/www) ====
  * cd /etc  * /apache2/conf.d
  * `nano openpanel.conf`
  * set SSLCertificateFile and SSLCertificateKeyFile to resp. /etc/ssl/certs/certificate-companje-nl.pem and /etc/ssl/private/ssl-cert-companje-nl-rsa.key (Where RSA.key file is created with 'openssl rss -in encrypted-certificate.key > ssl-cert-companje-nl-rsa.key’. First download your certificates (in my case from TransIP after requesting it from Comodo.
  * sudo /etc/init.d/apache2 restart

==== configure openpanel email aliasses via openpanel-cli====
http://documentation.openpanel.com/index.php/OpenPanel-CLI_Administration_Guide
```
openpanel-cli
configure domain cult-lab.nl
configure email cult-lab.nl
show address
configure alias info@cult-lab.nl
show dest
delete dest 2
create dest address=EMAIL@DOMAIN.COM
exit
exit
exit
exit
```
