---
title: Haproxy
---

# logs
  sudo tail -f /var/log/haproxy*.log

# example error in log
  217.149.135.12:61196 [18/Mar/2015:14:22:43.421] https~ nodeapp/nodeapp2 23/0/0/-1/23 -1 0 - - SD-- 24/24/24/2/0 0/0 "GET /socket.io/?key=54eafcd19008aa8312369eb4NJifImPah56lwJcxPqek&EIO=3&transport=polling&t=1426684963873-4 HTTP/1.1"
  
# log analysis
* http://www.goaccess.io/
* https://github.com/gforcada/haproxy_log_analysis
* http://www.haproxy.org/download/1.4/doc/configuration.txt
