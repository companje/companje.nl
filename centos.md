---
title: CentOS
---

==Opvragen CentOS versie==
<code bash>
cat /etc/*elease*
</code>

==warning: setlocale: LC_CTYPE: cannot change locale (UTF-8)==
<code bash>
export LC_ALL="en_US.UTF-8"
</code>

==warning: setlocale: LC_CTYPE: cannot change locale (UTF-8)==
This can be caused by your SSH client. You can disable the clients request for setting the language by:
<code>
sudo nano /etc/ssh_config
</code>
Comment the following line:
<code>
SendEnv LANG LC_*
</code>

==remove Apache from CentOS==
  sudo yum erase httpd httpd-tools apr apr-util
  
