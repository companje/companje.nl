---
title: PM2
---

  pm2 list
  pm2 restart YourProject
  pm2 logs
  pm2 logs YourTopic
  pm2 monit
  
  ~/.pm2/logs

===== om je eigen workspace versie te starten =====
  pm2 stop 0 (om de automatische gestarte HostModule service uit de root te stoppen)
  cd workspace-rick
  export DEBUG=HostModule:*
  nodemon
