---
title: Elementary OS
---

# xkill
xkill is great for killing a process by pointing to it's window

# When Scratch Editor hangs because of previously opened files
You can open dconf-editor and go to:
org -> pantheon -> scratch -> settings
and change the value of "opened-files" field. There is list of files that scratch tries to open. And it probably that one of them has too big size. You have just to remove this file from list and relaunch scratch. (source: https://bugs.launchpad.net/scratch/+bug/1202886)

# 'Alt-Tab' alternatives(CMD+pijltjes toets naar beneden geeft een overzicht waarbij je wel met pijltjes toetsen kan wisselen)

# install processing on elementaryOS(not success yet)
* https://github.com/processing/processing/wiki/Supported%20Platforms

# install java on elementaryos```
sudo apt-get update
sudo apt-get install python-software-properties
sudo add-apt-repository ppa:webupd8team/java
sudo apt-get update
sudo apt-get install oracle-java8-installer
```

# install ElementaryOS os MacbookPro* http://askubuntu.com/questions/462360/macbook-air-how-to-install-a-dual-bootable-ubuntu-14-04-lts

# right click on MacBook* https://www.reddit.com/r/elementaryos/comments/2x259t/i_cant_right_click/

# screen dpi  gsettings set org.gnome.desktop.interface scaling-factor 1

# replace 'Files' by nemohow to install nemo and more tips here: https://www.linux.com/learn/tutorials/836297-7-elementary-os-freya-tweaks-to-make-the-platform-even-better

no desktop window:
  gsettings set org.nemo.desktop show-desktop-icons false


# teamviewer[[http://linuxg.net/install-teamviewer-on-ubuntu/|this]] worked for me:
```
sudo apt-get update
sudo apt-get install gdebi
wget http://download.teamviewer.com/download/teamviewer_i386.deb
sudo gdebi teamviewer_i386.deb
```

# terminal colorshttp://mayccoll.github.io/Gogh/

# aliasesedit/create `~/.bash_aliases` and add your alias like this `alias dir="ls -al"`

# keyboard mapping Ctrl/Cmd* System Settings > Keyboard > Options > [x] Control is mapped to Win keys (and the usual Ctrl keys)

# autostart apps`Settings > Applications > Startup`
and or run `gnome-session-properties`
see also folder: `~/.config/autostart`

```
globe4d@Globe4D-Leiden:~/Documents/of0093/apps/Globe4D/Globe4D/bin$ cd ~/.config/autostart
globe4d@Globe4D-Leiden:~/.config/autostart$ ls
custom-command1.desktop  Globe4D.desktop
globe4d@Globe4D-Leiden:~/.config/autostart$ cat Globe4D.desktop 
[Desktop Entry]
Type=Application
Exec=/home/globe4d/Documents/of0093/apps/Globe4D/Globe4D/bin/Globe4D
Hidden=false
NoDisplay=false
X-GNOME-Autostart-enabled=true
X-GNOME-Autostart-Delay=2
Name[en_US]=Globe4D
Name=Globe4D
Comment[en_US]=
Comment=
globe4d@Globe4D-Leiden:~/.config/autostart$ cat custom-command1.desktop 
[Desktop Entry]
Name[en_US]=Globe4D Driver
Comment[en_US]=/home/globe4d/Documents/of0093/apps/Globe4D/Globe4D-Driver/bin/Globe4D-Driver
Exec=/home/globe4d/Documents/of0093/apps/Globe4D/Globe4D-Driver/bin/Globe4D-Driver
Icon=application-default-icon
X-GNOME-Autostart-enabled=true
Type=Application
Name=Globe4D Driver
globe4d@Globe4D-Leiden:~/.config/autostart$ 
```

# startup delay* http://www.webupd8.org/2014/05/how-to-delay-startup-applications-in.html
