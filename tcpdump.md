---
title: tcpdump
---

# tutorial
  http://openmaniak.com/tcpdump.php

# list all traffic
  sudo tcpdump

# list all interfaces
  sudo tcpdump -D

# log traffic on interface eth0
  sudo tcpdump -i eth0
  
# list traffic on certain port
  sudo tcpdump port 443
  
# show ip-addresses instead of DNS names
  sudo tcpdump -n

# traffic from src ip on port
  sudo tcpdump port 443 and src 217.149.135.12
