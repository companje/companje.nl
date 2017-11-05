---
title: Marktplaats
---
* https://github.com/companje/Marktplaats
* https://www.marktplaats.nl/mymp/zoekopdrachten/index.html (persoonlijke lijst met queries HTML)
* https://www.marktplaats.nl/mymp/zoekopdrachten/lijst.json (persoonlijke lijst met queries JSON, met parser errors.)
* voor de persoonlijke lijst moet je ingelogd zijn. Maar de URL's in de lijst kun je ook openen als je niet ingelogd bent. Hiermee je kun je makkelijk een overzicht van al je queries in 1x maken.
* Als je alle URLs in een tekstbestand zet door de JSON file met deze regexp te parsen `(http).*(.html)` dan kun je vervolgens met `wget -i urls.txt` alle html bestanden downloaden. Dat duurt maar een minuutje of twee voor ruim 700 queries.
* Dat kun je natuurlijk vanuit een php of ander server- of client-sided scriptje doen en het resultaat tonen op 1 overzichtelijke pagina.
* Of je maakt een lokaal script die afbeeldingen download met als bestandsnaam de naam van de query. Zodat je in je Finder of Explorer er makkelijk langs kunt lopen. Je kunt misschien wel bepaalde woorden wegfilteren zodat je geen last hebt van ongewenste advertenties.
