---
title: ========= MySQL =========
---

=====ERROR 1045 (28000): Access denied for user...=====
  mysql -u root -p

=====export table to CSV file=====
  SELECT * FROM uren INTO OUTFILE '~/Documents/backup/rick.csv' FIELDS ENCLOSED BY '"' TERMINATED BY ';' ESCAPED BY '"' LINES TERMINATED BY '
';

=====make backup of SQL database=====
  mysqldump -uroot db_name > backup.sql
or
  mysqldump -u YourUser -pUserPassword YourDatabaseName > wantedsqlfile.sql

==== Import SQL file ====
  mysql -u DB_USER -p -h localhost DB_NAME < /tmp/dump.sql

=====MySQL data files on CentOS=====
  /var/lib/mysql/
  
=====Location of MySQL files on Mac OSX installed with Homebrew=====
  /usr/local/var/mysql
  /usr/local/Cellar/mysql/5.7.12
=====MariaDB=====
installed with ''brew install mariadb''

To connect:
    mysql -uroot

To have launchd start mariadb at login:
    ln -sfv /usr/local/opt/mariadb/*.plist ~/Library/LaunchAgents
Then to load mariadb now:
    launchctl load ~/Library/LaunchAgents/homebrew.mxcl.mariadb.plist
Or, if you don't want/need launchctl, you can just run:
    mysql.server start
    
=====copy mysql database from one server to another=====
see [[linux]]

=====mysql client for osx=====
* http://www.sequelpro.com/

=====format date from timestamp in mysql=====
<code sql>
SELECT DATE_FORMAT(FROM_UNIXTIME(beginDate),"%e-%m-%Y %H:%i")
</code>
[[http://dev.mysql.com/doc/refman/5.5/en/date-and-time-functions.html#function_date-format|more info]]

=====change mysql password=====
<code bash>
mysqladmin -u root password root
</code>

=====connect to mysql database=====
<code bash>
mysql -u rick -pXXXXXX
mysql -u globe4d_user -pXXXX
</code>

=====create database=====
<code mysql>
create database databasename;
</code>

=====show grants=====
<code mysql>
show grants;
</code>

=====ERROR 1044 (42000): Access denied for user=====
het zou kunnen dat je op de server met mysql probeert te verbinden met je 'gewone' user en dat die geen toegang heeft. Vaak wordt er voor je database een aparte user gebruikt bijvoorbeeld 'globe4d_user' met z'n eigen wachtwoord.

=====show databases=====
<code mysql>
show databases;
</code>

=====use database=====
<code mysql>
use db_name;
</code>

=====show tables=====
<code mysql>
show tables;
</code>

=====show columns=====
<code mysql>
show columns from uren;
</code>

=====problem=====
* ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/tmp/mysql.sock' (2)
* try to start the mysql server:
<code>
mysql.server start
</code>

* ERROR! The server quit without updating PID file (/usr/local/var/mysql/rick.local.pid).''

=====install mysql using macports=====
 * http://2tbsp.com/content/install_and_configure_mysql_5_macports

=====problem: No such file or directory=====
* als php niet kan connecten met database controleer dan of php mysql uberhaupt kan vinden.

=====PHP Warning:  mysqli::mysqli(): [2002] No such file or directory (trying to connect via unix:///var/mysql/mysql.sock) in /........=====
Hmm, [[http://www.mostafaberg.com/2011/08/fixing-the-unixvarmysqlmysql-sock-not-found-error-on-mamp/|dit]] misschien
Misschien een conflict met de PHP van OSX zelf?
