---
title: CentOS
---

==Opvragen CentOS versie==
```bash
cat /etc/*elease*
```

==warning: setlocale: LC_CTYPE: cannot change locale (UTF-8)==
```bash
export LC_ALL="en_US.UTF-8"
```

==warning: setlocale: LC_CTYPE: cannot change locale (UTF-8)==
This can be caused by your SSH client. You can disable the clients request for setting the language by:
```
sudo nano /etc/ssh_config
```
Comment the following line:
```
SendEnv LANG LC_*
```

==remove Apache from CentOS==
  sudo yum erase httpd httpd-tools apr apr-util
  
