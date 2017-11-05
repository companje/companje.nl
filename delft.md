---
title: Installatieproces Ubuntu Live CD Delft
---

- Open een browser venster en activeer de driver voor de draadloze internetverbinding indien nodig. Ubuntu zal hier automatisch om vragen.
- Download de sourcecode vanaf http://.....
- Plaats het bestand op het bureaublad
- Pak het bestand uit.
- Open een terminal venster via het Applications->Accessories linksboven in de menubalk van Ubuntu.
- Ga naar de map waarin de bestanden zijn uitgepakt door het volgende te typen '''cd Desktop/datamining'''
- update apt-get: '''sudo apt-get update'''
- installeer glut: '''sudo apt-get install freeglut3-dev build-essential'''
- installeer libpng: '''sudo apt-get install libpng-dev'''
- type in '''make'''

Indien nodig:
- als je wilt checken of je videokaart goed werkt: ''glxinfo | grep '^direct rendering:' ''
- om universal sources the activeren: '''sudo nano /etc/apt/sources.list''' en goed lezen.
- daarna een '''sudo apt-get update'''
- daarna '''sudo apt-get install mesa-utils'''
