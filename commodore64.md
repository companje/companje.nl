---
title: Commodore64
---

# draw blocks with light-pen in low-res mode
<code gwbasic>
10 POKE 53280,0: POKE 53281,1
20 PRINT "{Shift+CLR}"
30 PRINT "                        DOODLE3D"

50 GOSUB 200
60 GOSUB 100
70 GOTO 10

100 POKE 780,0
110 POKE 781,Y
120 POKE 782,X
130 SYS 65520
140 PRINT "X"
150 RETURN

200 X=(PEEK(53267)-27)/4
210 Y=(PEEK(53268)-51)/8
220 IF X<1 THEN X=1
230 IF X>39 THEN X=39
240 IF Y<1 THEN Y=1
250 IF(Y>25 THEN Y=25
260 RETURN
```

# modulo
use backslash

# toggle upper/lower case
Shift+CommodoreKey

# clear screen
  print chr$(47)
or
  print "(Shift+CLR)"

# print at location ROW,COL
prints "X" at row=5, col=10
```
5 print chr$(147)
10 poke 780,0
20 poke 781,5
30 poke 782,10
40 sys 65520
```

# read X,Y coord of light-pen
use a bright background with dark border
  10 POKE 53280,0: POKE 53281,1
read values from 53267 and 53268 for X and Y ([[http://www.c64-wiki.com/index.php/Light_pen#Programmer.27s_How-To-section|read more]])
  20 PRINT PEEK(53267)" "PEEK(53268)
  30 GOTO 20

# serial communication in both directions at 1200 baud
(send CAPITALS from the PC because the character-set of the c64 is different)
```
10 OPEN 5,2,2,CHR$(8)
20 GET#5,A$: IF A$<>"" THEN PRINT A$;
30 GET B$: IF B$<>"" THEN PRINT#5,B$;
40 GOTO 20
```
CHR$(6) = 400 baud    (= bin 00000110)
CHR$(8) = 1200 baud  (= bin 00001000)

# tips van johan vandenbran.de
* https://github.com/cc65/cc65
*http://lallafa.de/blog/c64-projects/macvice/
*https://www.google.nl/webhp?sourceid=chrome-instant&ion=1&espv=2&ie=UTF-8#sourceid=chrome-psyapi2&ie=UTF-8&q=vice%20osx
*http://vice-emu.sourceforge.net/vice_14.html
*http://www.ajordison.co.uk/
*http://vandenbran.de/
*coolterm
*http://jderogee.tripod.com/

# background color
  POKE 53281,15
  
# invert colors
  POKE 53280,0: POKE 53281,1

# tips
* http://www.infinite-loop.at/Power64/Documentation/Power64-ReadMe/AA-C64_BASIC.html

# arduino to user port
* [[https://www.youtube.com/watch?v=DLYXUgH9rAI|NL filmpje]]
* [[http://www.hardwarebook.info/C64_RS232_User_Port|user port schema]]
* [[http://www.c64-wiki.com/index.php/User_Port|user port schema 2]]

# read joystick
  PEEK(56320)

# save file to disk
  SAVE "FILENAME",8
or
  DSAVE "FILENAME"
overwrite:
  DSAVE "@:FILENAME"
video: https://www.youtube.com/watch?v=F1rjCxT2w4c

# delete a file from disk
  OPEN 15,8,15
  PRINT#15 "S0:FILENAME"
  CLOSE 15
  
# reference guide
[[http://www.commodorefree.com/magazine/information/Commodore%2064%20Reference%20Guide.pdf|reference guide]]

# list files
  LOAD "$",8
  LIST
of
  F7 (met de Final Cartridge III)
  
# final cardridge iii
http://en.wikipedia.org/wiki/The_Final_Cartridge_III
tip: druk RUN/STOP in tijdens het indrukken van de Reset knop, dan kom je in basic en kun je de functietoetsen gebruiken waarmee je met F5 een disk runt en met F7 een dir listing opvraagt.

# run first program on disk
  load"*",8,1 
indien nodig gevolgd door:
  run
of
  DLOAD

# format disk
  OPEN1,8,15,"N:NEW DISK,00":CLOSE1 
  
# dirmaster3
*[[http://style64.org/dirmaster|dirmaster3]]
