---
title: Heroku
---

# add private repo
Om private github repositories te kunnen clonen vanaf Heroku moet de `JSPM_GITHUB_TOKEN` geset zijn met een github app token, hetzelfde als lokaal met `jspm registry config github`.  Dus `heroku config:set JSPM_GITHUB_TOKEN <TOKEN>`. Met `jspm registry export github` kan je de lokale github key zien.
