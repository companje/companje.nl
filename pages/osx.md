---
title: Mac OSX
---

# Cmd+F1 mirror/extend
Op een Mac kun je de sneltoets Command (⌘) + F1 gebruiken om te schakelen tussen beeldschermduplicatie (mirroring) en uitgebreid bureaublad (extending) als je een extern beeldscherm hebt aangesloten.

# alias
```bash
alias subl="open . -a \"Sublime Text\""
alias github="open . -a \"Github Desktop\""
alias code="open . -a \"Visual Studio Code\""
```

# displacer (cli program for arranging screens)
* https://github.com/jakehilborn/displayplacer

# m-cli - Swiss Army Knife for macOS
* https://github.com/rgcr/m-cli

# close open port
```bash
sudo lsof -i :8080
kill 99548 # replace by processID
```

# stop spotlight indexer
```bash
sudo mdutil -i off
```

# hfsexplorer
Schijven met Mac OS Extended filesystem lezen op Windows: hfsexplorer

# enable zoom with Control + Mouse/trackpad scroll
<img width="703" alt="Screenshot 2022-12-27 at 21 18 05" src="https://user-images.githubusercontent.com/156066/209718245-f47ebbeb-dda2-4127-a6e9-1847af9c6011.png">

# Apple Notes
by doubleclicking on the modification date of the note you can see the creationdate
<img width="692" alt="Screenshot 2022-09-30 at 09 47 49" src="https://user-images.githubusercontent.com/156066/193219266-fadf9bde-024b-44dc-83c9-15da6bbf9d27.png">

# see which applications have which network ports open
https://apple.stackexchange.com/questions/117644/how-can-i-list-my-open-network-ports-with-netstat
```bash
netstat -Watnlv | grep LISTEN | awk '{"ps -o comm= -p " $9 | getline procname;colred="\033[01;31m";colclr="\033[0m"; print colred "proto: " colclr $1 colred " | addr.port: " colclr $4 colred " | pid: " colclr $9 colred " | name: " colclr procname;  }' | column -t -s "|"
```

# kill Excel
```bash
killall -9 "Microsoft Excel" 2>/dev/null
```

# recursively remove .DS_Store files
```bash
find . -name '.DS_Store' -type f -delete
```

# networkQuality
```bash
networkQuality
```

# How to see hidden files in macOS
open the Finder and press Command + Shift + .

# Add Quick Actions to Finder
use Automator to create a new Quick Action. It is automatically added to the contextmenu in Finder.

# launchctl as alternative for ps
```bash
launchctl list | grep couchdb
```

# Etcher
You can burn ISO's with Etcher: <https://etcher.io>

# custom resolution
"To access all supported resolutions for your external display, press and hold the Option key on your keyboard and then click the “Scaled” option again."

# Prepare Mac for standalone exhibition
http://figure53.com/docs/qlab/v3/general/preparing-your-mac/


# SD Memory Card Formatter for Mac
<https://www.sdcard.org/downloads/formatter_4/eula_mac/index.html>


# MediaKit reports not enough space on device for requested operation
This worked for me:
(watch out: it formats the disk! make sure to select the right disk)
```bash
diskutil list
diskutil unmountDisk force disk2
sudo dd if=/dev/zero of=/dev/rdisk2 bs=1024 count=1024
diskutil partitionDisk disk2 GPT JHFS+ "LABEL" 0g
```
[source](https://mycyberuniverse.com/web/how-fix-mediakit-reports-not-enough-space-on-device.html)

# disk usage visualisation
* grandperspective (works well)
* disk inventory x (not tried yet)

# my updated .bash_profile
```bash
export PATH=~/bin:$PATH
alias d="ls -lGa"
alias ls="ls -G"
alias untar="tar xvzf "
alias lsr="ls -ltr"  #recent files ordered ascending

export CLICOLOR=1
export LSCOLORS=ExFxCxDxBxegedabagacad

. /usr/local/etc/profile.d/z.sh
  
git_branch () { git branch 2> /dev/null | sed -e '/^[^*]/d' -e 's/* \(.*\)/\1/'; }
LOCATION='[01;34m\]`pwd | sed "s#\(/[^/]\{1,\}/[^/]\{1,\}/[^/]\{1,\}/\).*\(/[^/]\{1,\}/[^/]\{1,\}\)/\{0,1\}#\1...\2#g"`'
BRANCH=' [00;33m\]$(git_branch)\[[00m\]
\$ '
PS1=$LOCATION$BRANCH
PS2='\[[01;36m\]>'
```

# z as alternative for cd
```bash
brew install z
# add this to ~/.bash_profile: . /usr/local/etc/profile.d/z.sh
```
https://github.com/rupa/z


# Make automatic screenshots
1) in Automator create an app called and save it in /Applications/AutoScreenshot.app. The app contains the following shell script:
```bash
vardate=$(date +%Y\-%m\-%d); 
vartime=$(date +%H.%M.%S);
FOLDER=/Users/rick/Screenshots/Screenshot-auto/$vardate
mkdir -p $FOLDER
screencapture -t jpg -x "$FOLDER/Screenshot-auto $vardate at $vartime.jpg"
```

2) create the file `~/Library/LaunchAgents/nl.companje.screenshots.plist` with the following contents:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
  <key>Label</key>
  <string>nl.companje.screenshots</string>
  
  <key>ProgramArguments</key>
  <array>
  <string>/usr/bin/open</string>
  <string>-W</string>
  <string>/Applications/AutoScreenshot.app</string>
  </array>

  <key>StartInterval</key>
  <integer>10</integer>

  <key>StandardOutPath</key>
  <string>/Users/rickcompanje/Screenshots/log.log</string>

  <key>StandardErrorPath</key>
  <string>/Users/rickcompanje/Screenshots/log.log</string>

  <key>Debug</key>
  <true/>
</dict>
</plist>
```
3) Install and run the launchd process
```bash
# launchctl unload nl.companje.screenshots.plist
cd ~/Library/LaunchAgents/
launchctl load nl.companje.screenshots.plist
launchctl start nl.companje.screenshots
# tail /var/log/system.log
```


# Launchd
* <https://developer.apple.com/library/content/documentation/MacOSX/Conceptual/BPSystemStartup/Chapters/CreatingLaunchdJobs.html#//apple_ref/doc/uid/10000172i-SW7-BCIEDDBJ>
* <http://www.launchd.info>

# Run 3 minutes past midnight (or later when computer was a sleep at that time)
```xml
   <key>StartCalendarInterval</key>
    <dict>
        <key>Hour</key>
        <integer>00</integer>
        <key>Minute</key>
        <integer>03</integer>
    </dict>
```

# Use Automator for making Symbolic Links
1 Create an Application
2 add task `Run Shell Script` with 'pass input as arguments'
```
ln -s "$1" /Users/rick/Documents/Websites/
```
(::screen_shot_2017-04-13_at_16.55.11.png?450|)

# iTerm2 keyboard shortcuts and tips
https://www.iterm2.com/documentation-one-page.html

# convert multiple PNG's to JPG
You can do it with the Preview App as described here: http://osxdaily.com/2013/01/16/batch-image-conversion-mac-os-x-preview/

# Kill Google Photo Uploader
(or anything containing the word 'Photos')
  kill -9 $(pgrep Photos)

# SiteSucker
recursive download van een website. http://www.sitesucker.us

# mv command
by default it seems to replace a file.
  mv -n src dst  # Do not overwrite an existing file.
[[https://developer.apple.com/library/mac/documentation/Darwin/Reference/ManPages/man1/mv.1.html|man]]

# to enter unicode character on osx
  - System preferences-> input menu
  - check the box next to "Unicode hex".
  - add Unicode hex as a language
  - Switch to unicode input in the menu bar.
  - Hold Alt followed by a 4 digit hexadecimal unicode value to get the character.
  
# chmod in Sites folder
  chmod -R 755 folder_with_wrong_permissions
  
# .bash_profile
```bash
export PATH=/opt/local/bin:/opt/local/sbin:~/bin:$PATH

export ANDROID_HOME=~/Documents/android-sdk-macosx
export PATH=$PATH:$ANDROID_HOME/tools:$ANDROID_HOME/platform-tools:$ANDROID_HOME/build-tools/20.0.0

export LUA_CPATH=/opt/local/share/luarocks/lib/lua/5.2/?.so
export LUA_PATH=/opt/local/share/luarocks/share/lua/5.2/?.lua

alias d="ls -lGa"
alias ls="ls -G"
alias um="cd ~/Sites/Ultimaker"
alias untar="tar xvzf "

if [ -f /opt/local/etc/profile.d/bash_completion.sh ]; then
   . /opt/local/etc/profile.d/bash_completion.sh
fi
```

# list open ports
```bash
lsof -i -P | grep -i "listen"
```

# my .bash_profile
```bash
export PATH=/opt/local/bin:/opt/local/sbin:~/bin:$PATH
alias d="ls -lGa"
alias ls="ls -G"
alias um="cd ~/Sites/Ultimaker"
alias untar="tar xvzf "
```

# SDL on OSX
* http://www.libsdl.org/download-1.2.php

# own /usr/local
  chown -R rick /usr/local

# convert DDS file to JPG on Mac OSX
* http://www.xnview.com/en/

# busybox (shell & httpd)
http://wiki.openwrt.org/doc/howto/http.httpd

# dumb AP
http://wiki.openwrt.org/doc/recipes/dumbap

# diskutil on command line
```bash
diskutil list
```

# file info
```bash
file
otool
gobjdump
...
```

# Folder/File sync tools
* DeltaWalker
* Singlemizer (not tested yet)

# keyboard repeat rate / speed
* <https://support.apple.com/kb/PH25373?locale=en_US&viewlocale=en_US>

# unpack a .pkg file
* https://www.macupdate.com/app/mac/16357/unpkg

# batch resize images
```bash
sips -Z 640 *.jpg
```

# serial communication with screen
```bash
screen /dev/[device name] 115200
```
To quit screen, press CTRL-a, followed by CTRL-k, followed by y.

# follow system log
```bash
tail -f /private/var/log/system.log
```

# macports / python
To make python 2.7 the default (i.e. the version you get when you run 'python'), please run:
```bash
sudo port select --set python python27
```

# mount ssh folders
* http://osxfuse.github.io/

# PVR QuickLook plugin
* http://www.limbic.com/quickpvr.html

# delete 'bands' files in Time Capsule .sparsebundle folder
Removing your Time Capsule .sparsebundle can take multiple days (or weeks) if you use Finder or Terminal.
The fastest way is by connecting to the TimeCapsule through Windows running in Parallels/VMWare etc and delete it using Windows Explorer (\ipadress of time capsule). It took 'only' 80 minutes to remove 1.5TB (1.8 million files of 8MB)

# thumbsup
een handige tool om snel afbeeldingen te bewerken

# alternative to spotlight
* [[http://www.alfredapp.com/|Alfred]]

# get ip address
```bash
ifconfig getifaddr en1
```
or
```bash
ifconfig en1 | grep "inet " | cut -d " " -f2
```

# convert png to ico (imagemagick)
```bash
convert file.png file.ico
```

# osx keyboard shortcuts
* http://support.apple.com/kb/HT1343

# cls
```
clear
```

# show contents of clipboard
```bash
pbpaste
pbpaste | head -n 5
```

# copy text to clipboard
```bash
ls | pbcopy
```

# Convert tabs to spaces for the lines in the Clipboard
```bash
pbpaste | expand | pbcopy
```

# sorting etc
```bash
ls | sort
ls | rev
ls | uniq
ls | uniq -d
ls | uniq -u
```

# eject cd
```bash
diskutil eject disk1
```
or
```bash
drutil tray eject
```

# dig
```bash
dig companje.nl A
```

# disable dashboard
```bash
defaults write com.apple.dashboard mcx-disabled -boolean YES
killall Dock
```

# get name of current wifi network
```bash
/System/Library/PrivateFrameworks/Apple80211.framework/Versions/A/Resources/airport -I|grep " SSID: "|cut -c 18-
```
or
```bash
networksetup -getairportnetwork en1
networksetup -getairportnetwork en1 | cut -c 24-
```

# airdrop info
* [[http://www.yourdailymac.net/2011/09/how-to-enable-os-x-lion-airdrop-on-every-mac/|enable OS X Lion AirDrop on every Mac]]

# restart Finder
```bash
killall Finder
```

# list all network hardware ports
```bash
networksetup -listallhardwareports
```

# get info about current wifi point
```bash
/System/Library/PrivateFrameworks/Apple80211.framework/Versions/Current/Resources/airport --getinfo
```

# scan for wifi points
```bash
/System/Library/PrivateFrameworks/Apple80211.framework/Versions/Current/Resources/airport -s
```

# symbolic links
```bash
ln -s fullpath-source-www /Users/rick/Sites/8
```

# show display settings
```
alt+F2
```

# QuickLook / Quick Preview / Syntax Highlighting / Color Code
Download [[http://qlcolorcode.googlecode.com/files/QLColorCode-2.0.2.tgz|QLColorCode]]. Create folder ''~/Library/QuickLook'' and copy QLColorCode.qlgenerator to that folder.

# SetFile
```bash
SetFile
```

# dmg create command line
```bash
hdiutil create ....
```
* http://stackoverflow.com/questions/96882/how-do-i-create-a-nice-looking-dmg-for-mac-os-x-using-command-line-tools

# usb device info
```bash
system_profiler SPUSBDataType
```

# network utilities
* From the Applications folder, open the Utilities folder, and then open the Network Utility application. 
* In the Network Utility window, click the Ping tab. 

# broadcast ping
```bash
ping -i 5 -c 2 192.168.1.255
```

# using arp to find mac address
```bash
ping IPADDRESS
arp -a
```

# fping range
```bash
sudo fping -s -g 192.168.0.1 192.168.0.9 -r 1
fping -ag 192.168.1.0/24
```

# websharing is removed from Mountain Lion Preferences but still usable
```bash
sudo apachectl start
```

# Repeatable crash of Time Capsule wifi
* http://forums.macrumors.com/showthread.php?t=1324678

# Mac OSX Driver for Sweex Graphics Tablet USB TA006
* The UC-Logic driver for Mac OSX works for me: http://www.uc-logic.com/

# serial terminal app
CoolTerm - http://www.macupdate.com/app/mac/31352/coolterm/

# stop cups
```bash
sudo launchctl stop org.cups.cupsd
```

# 40 lion tips
* http://mac.appstorm.net/roundups/utilities-roundups/40-super-secret-os-x-lion-features-and-shortcuts/

# write disk image to sd card
* [[http://www.thelinuxdaily.com/2010/01/writing-images-to-disk-on-mac-osx-with-dd/|read article on the linuxdaily]]
```bash
diskutil list
diskutil unmountDisk /dev/disk3
dd if=debian6-19-04-2012.img of=/dev/disk3 bs=1m
```
(...additional "bs" parameter to "1m"? This parameter is used to set both the input and output block size for the copy)

# jhbuild bootstrap --ignore-system
geen idee wat het doet maar het doet iets.

# network dump
only loopback traffic: ''tcpdump -i lo0 -vv''  
\
en0 ethernet, en1 wifi

# tftp
- er is standaard een tftp client geinstalleerd
- een prima tftp server is [[http://ww2.unime.it/flr/tftpserver/|TftpServer]]

# Some dynamic linker tools that will come in handy
```bash
otool -L
install_name_tool
libtool
```
[[http://qin.laya.com/tech_coding_help/dylib_linking.html]]
otool is an alternative for linux' 'ldd'

# List files in library
```bash
ar -t libctest.a
```

# Tijdelijk een header search path toevoegen via CFLAGS
<del>```
export CFLAGS="-i /usr/local/include/libusb-1.0"
```</del>

# disable OSX Lion remember open documents
see [[http://www.macworld.com/article/1161330/four_lion_terminal_hacks.html|this]] or [[http://reviews.cnet.com/8301-13727_7-20083707-263/managing-mac-os-x-lions-application-resume-feature/|this]]

for Preview, Quicktime and XCode:
```bash
defaults write com.apple.Preview NSQuitAlwaysKeepsWindows -bool false
defaults write com.apple.QuickTimePlayerX NSQuitAlwaysKeepsWindows -bool false
defaults write com.apple.dt.Xcode NSQuitAlwaysKeepsWindows -bool false
```

# /etc/paths
this file contains the search paths

# goto home dir
instead of ''cd ~'' you can just type ''cd''

# move mouse with code
```cpp
CGPoint pt;
pt.x = x;
pt.y = y;
CGEventRef mouseDownEv = CGEventCreateMouseEvent (NULL,kCGEventMouseMoved,pt,kCGMouseButtonLeft);
CGEventPost(kCGHIDEventTap, mouseDownEv);
CFRelease(mouseDownEv);
```

# spoof your MAC address
```bash
sudo ifconfig en1 ether aa:bb:cc:dd:ee:ff
```
to check the result type: 
```bash
ifconfig en1 | grep ether
```
for Lion you might need to change ether into Wi-Fi.
When you reboot your computer the original address is restored.

# escape character in tenet on osx
```bash
Escape character is '^]'
```
which means Ctrl+]

# hosts file
```bash
sudo nano -w /etc/hosts
sudo dscacheutil -cachedump -entries Host
dscacheutil -flushcache
```

# stty
http://stackoverflow.com/questions/3918032/bash-serial-i-o-and-arduino
can you run stty -a < /dev/tty.usbserial-A800eIUj while you have the serial port working on the Arduino IDE? That would give you the settings to use.
```bash
stty -f /dev/tty.PL2303-00001004 19200
```

# otool
```bash
otool -L libopenFrameworks.dylib
install_name_tool
```

# automatisch opstarten
nog uitzoeken: launchd en lingon

# launch application by keyboard shortcut
[[http://hints.macworld.com/article.php?story=20090903085255430|link]]

# swap alt+windows key on external keyboard
"If your keyboard has Alt and Windows mixed up relative to that space bar, you can remap them in System Preferences. Go to System Preferences > Keyboard & Mouse and click Modifier Keys... Use the following dialog to map your keys into place. Often this involves nothing more than switching the Command and Option keys"

# searching in files recursively
```bash
grep -ir "texttofind" *
```

# using the find command on the command line
```bash
find . | grep -i ".cpp"
```

# use grep to search output of a program
```bash
git diff | grep 'piano'
```

# compile a simple glut program written in c on mac
```bash
g++ -Wall -O3 -g -framework OpenGL -framework GLUT bezmesh.c -o bezmesh
```

Don't forget to include OpenGL and GLUT like this:
```cpp
#ifdef __APPLE__
#include <OpenGL/OpenGL.h>
#include <GLUT/glut.h>
#else
#include <GL/glut.h>
#endif
```

# Enable colors in the terminal's ls command
or type ''ls -G'' or put the following in your ''~/.profile'' file for a permanent solution.
```bash
bashexport CLICOLOR=1
```

# Show hidden files/folders
```bash
defaults write com.apple.finder AppleShowAllFiles TRUE
```
(and reload Finder)

# Change screenshots folder (and other settings)
* http://www.tekrevue.com/tip/how-to-customize-screenshot-options-in-mac-os-x/
```bash
defaults write com.apple.screencapture location /Users/rick/Desktop/screenshots
killall SystemUIServer
```

# SizeUp
[[http://www.irradiatedsoftware.com|SizeUp]] allows you to quickly position a window to fill exactly half the screen (splitscreen), a quarter of the screen (quadrant), full screen, or centered via the menu bar or configurable system-wide shortcuts (hotkeys).  Similar to "tiled windows" functionality available on other operating systems.

# Visor
Install [[http://visor.binaryage.com/|Visor]] to have a system-wide terminal on a hot-key.

# Rotate an image
```bash
sips input.jpg -r 90 --out output.jpg
```

# convert an image
```bash
sips -s format jpeg 4.gif --out 4.jpg
```

# chmod recursive write access
```bash
chmod -R 777 data
```

# ~/.profile
on osx 10.9 I had to rename .profile to . bash_profile
```bash
alias dir="ls -lGa"
```

```bash
alias tocaf="afconvert -f caff -d LEI16"
```

```bash
alias cdd="cd ~/Desktop"
```

# ffmpeg for converting movie to iPad
see [[:ffmpeg]]  
\

# combine pdf's on mac osx (with pdftk) 
```bash
sudo port install pdftk
pdftk 1.pdf 2.pdf 3.pdf cat output 123.pdf
```

# homebrew (brew) 
An alternative for macports

# Wireshark
Wireshark is a tool to analyze network traffic

# hexdump
installed by default.
```bash
hexdump filename
```

# gmail berichten sturen via command line in osx
See this page: [[http://www.amirwatad.com/blog/archives/2009/03/21/send-email-from-the-command-line-using-gmail-account/|link]]
I managed to send an email but it's not working completely yet.

# terminal / iTerm2
* Cmd+[ ] move between panels
* Cmd+D new panel
* Cmd+W close panel
* ^A naar begin regel

# Pleasant3D.app
Handige app om GCODES mee te bekijken in 3D

# Synergy2
```bash
synergys --config synergy.conf
```

synergy.conf:
```bash
section: screens
        rick.local:
        User-PC:
end
section: links
rick.local:
        right = User-PC
User-PC:
        left = rick.local
end
```
