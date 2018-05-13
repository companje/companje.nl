---
title: Windows
---

# cmder.net
Portable console emulator for Windows

# Reboot and Select proper Boot device or Insert Boot Media in selected Boot device and press a key
I had to change LEGACY to UEFI in the BIOS for my harddisk

# Problems with formatting USB drive
Download HP USB Disk Storage Format Tool

# Windows USB/DVD Download Tool
http://www.microsoft.com/en-us/download/windows-usb-dvd-download-tool

# Cmder
MinGW / Bash voor Windows: Cmder.exe

# automatisch inloggen op Windows
  
  Win+R -> netplwiz# Windows USB/DVD Download Tool
(voor het branden van een Windows ISO file naar USB stick)
* http://www.microsoft.com/en-us/download/windows-usb-dvd-download-tool

# cleanup WinSxS folder=
"KB 2852386 adds the ability to cleanup all the obsolete updates in the WinSxS folder"
http://lifehacker.com/recover-tons-of-wasted-disk-space-with-the-new-windows-1442937625

# disable hibernate in Windows 7
run as admin:
  powercfg -h off
Check Start->Shutdown->... Hibernate should be gone.

# Windows resource checker
  sfc /scannow
  
  # Element not found (when fixing boot problems)
  bcdboot c:\windows /l en-us /s C:\

# kill not responding windows====
  taskkill /f /fi "status eq not responding"

# Windows 8 startup folder
```
shell:startup
```

# See Windows boot history
filter in ''eventvwr'' on event ID 12

# control commands
http://support2.microsoft.com/kb/192806
```
Control panel tool             Command
   -----------------------------------------------------------------
   Accessibility Options          control access.cpl
   Add New Hardware               control sysdm.cpl add new hardware
   Add/Remove Programs            control appwiz.cpl
   Date/Time Properties           control timedate.cpl
   Display Properties             control desk.cpl
   FindFast                       control findfast.cpl
   Fonts Folder                   control fonts
   Internet Properties            control inetcpl.cpl
   Joystick Properties            control joy.cpl
   Keyboard Properties            control main.cpl keyboard
   Microsoft Exchange             control mlcfg32.cpl
      (or Windows Messaging)
   Microsoft Mail Post Office     control wgpocpl.cpl
   Modem Properties               control modem.cpl
   Mouse Properties               control main.cpl
   Multimedia Properties          control mmsys.cpl
   Network Properties             control netcpl.cpl
                                  NOTE: In Windows NT 4.0, Network
                                  properties is Ncpa.cpl, not Netcpl.cpl
   Password Properties            control password.cpl
   PC Card                        control main.cpl pc card (PCMCIA)
   Power Management (Windows 95)  control main.cpl power
   Power Management (Windows 98)  control powercfg.cpl
   Printers Folder                control printers
   Regional Settings              control intl.cpl
   Scanners and Cameras           control sticpl.cpl
   Sound Properties               control mmsys.cpl sounds
   System Properties              control sysdm.cpl
```

# deactivate windows
  slmgr.vbs -upk
  
  # event viewer
  eventvwr
  
  # Shuttle SH61R BIOS update
* http://global.shuttle.com/main/productsDownload?productId=1555

# ignore windows recovery
```
bcdedit /set {current} bootstatuspolicy ignoreallfailures
```

# windows info
```MSinfo32 ```

# Disabling “Has stopped working” dialogs
In '''HKEY_LOCAL_MACHINE\SOFTWARE\Microsoft\Windows\Windows Error Reporting''' create a DWORD 'DontShowUI' with value 1

# start task scheduler
```
control schedtasks
```

# reboot computer
```
shutdown -r -f -t 00
```

# shutdown computer
```
shutdown -s -f -t 00
```

# windows 8 startup folder
```
%AppData%\Microsoft\Windows\Start Menu\Programs\Startup
```
or
```
shell:startup

```# clear diskspace
http://www.howtogeek.com/174705/how-to-reduce-the-size-of-your-winsxs-folder-on-windows-7-or-8/

# always on top
<code c>
void setAlwaysOnTop() {
  HWND hwnd = FindWindowA("GLUT","");
  ::SetWindowPos(hwnd, HWND_TOPMOST, NULL, NULL, NULL, NULL, SWP_NOREPOSITION | SWP_NOSIZE);
}
```

# verberg start menu bij starten windows 8
* http://stackoverflow.com/questions/19047257/show-auto-started-desktop-app-in-fullscreen-on-windows-8

# com ports
* http://tim.cexx.org/?p=912

# convert png to icon
* http://converticon.com/

# How to Customize Keyboard Speed in Windows
* http://www.sevenforums.com/hardware-devices/8712-keyboard-repeat-delay-repeat-rate.html

# which dll's used by running programs
```
tasklist /m
```
or
```
tasklist /m /fi "imagename eq [programname]"
```

# Batch files
Zie [[batch]] files.

# change computer name
Change Your Computer Name in Windows 7 or Vista:
Run:
```
sysdm.cpl
```
go to advanced system settings
open the Computer Name tab
Click change...

# disable detection of serial mouse
* XP: Add the /NoSerialMice option to the end of each entry in the [operating systems] section of Boot.ini: http://support.microsoft.com/kb/131976
* Windows 7? Plug & Play Blocker - http://forum.ssca.org/phpBB3/viewtopic.php?f=5&t=11203
