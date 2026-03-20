## Plustek OpticSlim M12 scanner info
* https://gkall.hobby.nl/gt6816-07b3-0412.html
* http://www.meier-geinitz.de/sane/gt68xx-backend/
* http://www.sane-project.org/man/sane-gt68xx.5.html
* http://www.meier-geinitz.de/sane/gt68xx-backend/
```
Aanpak op Mac:
  1. brew install sane-backends
  2. Zet cism216.fw op de plek die je gt68xx.conf gebruikt.
  3. Voeg in gt68xx.conf (of controleer) een regel voor 0x07b3
     0x0412 + firmwarepad.
  4. Test met:
      - scanimage -L
      - scanimage --device-name "gt68xx:libusb:..." --format=png
        > test.png
```

##  Canon ImageFORMULA P-208II
werkt ook met sane
