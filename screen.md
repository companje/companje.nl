---
title: screen
---

The screen program allows you to use multiple windows (virtual VT100 terminals) in Unix.
https://kb.iu.edu/d/acuy

# "Do whatever is needed to get a screen session."
```
screen -D ....
```

# keys
Kill = Ctrl+A k
Detach = Ctrl+A d

# Resume a detached screen session
  screen -r
  
# man page
https://www.gnu.org/software/screen/manual/screen.html

# connect to hostmodule
  screen /dev/tty.usbserial
  
# reset
```
reset
```
