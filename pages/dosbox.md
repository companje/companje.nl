---
title: DOSBOX
---

## scale window
https://superuser.com/questions/1425244/increase-dosbox-windowed-size
run "DOSBox 0.74 Options.bat"
the script starts notepad with configuration file: here change
```
windowresolution=1600x800
output=ddraw
```

## preferences file on windows
C:\Users\USERNAME\AppData\Local\DOSBox

## edit autoexec.bat
see autoexec section in `subl ~/Library/Preferences/DOSBox\ 0.74-3-3\ Preferences`

## mount
```bat
intro mount
mount c ~/c
```

## imgmount
```bat
imgmount b ~/c/disk1.img -t floppy
imgmount -u b   # unmount
```
  
