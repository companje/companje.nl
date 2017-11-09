---
title: SD cards
---

# Easily Format a SD Card in OSX to FAT32
(using diskutil from the terminal)
https://www.michaelcrump.net/the-magical-command-to-get-sdcard-formatted-for-fat32/
```bash
diskutil list
sudo diskutil eraseDisk FAT32 RASPBIAN MBRFormat /dev/diskX
```

# Etcher
Etcher is a graphical SD card writing tool that works on Mac OS, Linux and Windows, and is the easiest option for most users. Etcher also supports writing images directly from the zip file, without any unzipping required. To write your image with Etcher:
* https://etcher.io/
* 

