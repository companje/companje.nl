---
title: MSYS
---
* https://msys2.github.io/
* http://sourceforge.net/p/msys2/wiki/MSYS2%20installation/

Update the system packages with
  pacman --needed -Sy bash pacman pacman-mirrors msys2-runtime
Close MSYS2, run it again from Start menu and update the rest with
  pacman -Su
MSYS2 shell with pacman's output about system upgrade 
Now Pacman is fully committed to the windows cause :)

# my msys2 home has moved from a linux like one to a windows like one. How to restore it?
Changing the Windows environment variable HOME solves the problem.
* http://superuser.com/questions/730409/my-msys2-home-has-moved-from-a-linux-like-one-to-a-windows-like-one-how-to-rest
