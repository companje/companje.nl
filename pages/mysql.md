---
title: MySQL
---

# install on osx
```bash
brew install mysql
brew services start mysql
mysql -u root # test
# now use Sequal Ace
```

# install on linux
```bash
sudo apt-get install mysql-server
```

# An error occurred when reading the file ... Autodetect - Unicode (UTF-8)
solution "select Western (Mac OS Roman) as the encoding format for the file to import without issue."
source: https://dba.stackexchange.com/questions/111549/cannot-locally-import-utf-8-encoded-sql-database-on-mac

# sudo mysql.server start
```bash
sudo mysql.server start

mysql -uroot -p
#Enter password: (your local mysql password)
```

# OSX gui client
Sequal Ace (AppStore) vs Sequal Pro

# install (without password)
```bash
brew install mysql
brew services start mysql
mysql -uroot
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'newrootpassword';
```

# problem with secure-file-priv 
1. Look for "my.cnf" file which is located at /usr/local/etc
2. add secure-file-priv = "" into my.cnf
3. restart mysql using /usr/local/opt/mysql/support-files/mysql.server restart

# ERROR 1698 (28000): Access denied for user 'rick'@'localhost'
mysql> GRANT ALL ON *.* to rick@localhost IDENTIFIED BY 'password';


# set mysql for root from '' to something on OSX with XAMPP
```bash
/Applications/XAMPP/bin/mysqladmin --user=root password "something"
```

# ERROR 1045 (28000): Access denied for user...
```bash
mysql -u root -p
```

# export table to CSV file
```sql
SELECT * FROM uren INTO OUTFILE '~/Documents/backup/rick.csv' FIELDS ENCLOSED BY '"' TERMINATED BY ';' ESCAPED BY '"' LINES TERMINATED BY '
';
```

# make backup of SQL database
```bash
mysqldump -uroot db_name > backup.sql
```
or
```bash
mysqldump -u YourUser -pUserPassword YourDatabaseName > wantedsqlfile.sql
```

# Import SQL file 
```bash
mysql -u DB_USER -p -h localhost DB_NAME < /tmp/dump.sql
```

# MySQL data files on CentOS
```
/var/lib/mysql/
```

# Location of MySQL files on Mac OSX installed with Homebrew
```
/usr/local/var/mysql
/usr/local/Cellar/mysql/5.7.12
```

# MariaDB

installed with `brew install mariadb`

To connect:
```bash
  mysql -uroot
```

To have launchd start mariadb at login:
```bash
  ln -sfv /usr/local/opt/mariadb/*.plist ~/Library/LaunchAgents
```
Then to load mariadb now:
```bash
launchctl load ~/Library/LaunchAgents/homebrew.mxcl.mariadb.plist
```
Or, if you don't want/need launchctl, you can just run:
```bash
mysql.server start
```    

# copy mysql database from one server to another
see [[linux]]

# mysql client for osx
* http://www.sequelpro.com/

# format date from timestamp in mysql
```sql
SELECT DATE_FORMAT(FROM_UNIXTIME(beginDate),"%e-%m-%Y %H:%i")
```
[[http://dev.mysql.com/doc/refman/5.5/en/date-and-time-functions.html#function_date-format|more info]]

# change mysql password
```bash
mysqladmin -u root password root
```

# connect to mysql database
```bash
mysql -u rick -pXXXXXX
mysql -u globe4d_user -pXXXX
```

# create database
```sql
create database databasename;
```

# show grants
```sql
show grants;
```

# ERROR 1044 (42000): Access denied for user
het zou kunnen dat je op de server met mysql probeert te verbinden met je 'gewone' user en dat die geen toegang heeft. Vaak wordt er voor je database een aparte user gebruikt bijvoorbeeld 'globe4d_user' met z'n eigen wachtwoord.

# show databases
```sql
show databases;
```

# use database
```sql
use db_name;
```

# show tables
```sql
show tables;
```

# show columns
```sql
show columns from uren;
```

# problem
* ERROR 2002 (HY000): Can't connect to local MySQL server through socket '/tmp/mysql.sock' (2)
* try to start the mysql server:
```bash
mysql.server start
```
* ERROR! The server quit without updating PID file (/usr/local/var/mysql/rick.local.pid).''

# install mysql using macports
* http://2tbsp.com/content/install_and_configure_mysql_5_macports

# problem: No such file or directory
* als php niet kan connecten met database controleer dan of php mysql uberhaupt kan vinden.

# PHP Warning:  mysqli::mysqli(): [2002] No such file or directory (trying to connect via unix:///var/mysql/mysql.sock) in
Hmm, [[http://www.mostafaberg.com/2011/08/fixing-the-unixvarmysqlmysql-sock-not-found-error-on-mamp/|dit]] misschien
Misschien een conflict met de PHP van OSX zelf?
