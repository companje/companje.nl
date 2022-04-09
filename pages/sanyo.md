---
title: Sanyo MBC-550/555
---

<img src="https://user-images.githubusercontent.com/156066/160270847-03ebfc54-547e-4a9a-813f-6114f2f6213b.jpg" alt="Sanyo-MBC-555-Rick-Companje" width="400" align="right">

## sanyo mbc55x.cpp class in Mame
* https://github.com/mamedev/mame/blob/master/src/mame/drivers/mbc55x.cpp

## segments and offsets
"The 8086 has 20-bit addressing, but only 16-bit registers. To generate 20-bit addresses, it combines a segment with an offset. "

## qemu
not for Sanyo but emulator for x86 in general:
On Mac:
```
brew install qemu
qemu-system-x86_64 -fda boot-basic.img
```

## libdsk
nog onderzoeken: http://www.seasip.info/Unix/LibDsk/

## debug
```
-l 100 1 0 1  # load onto address 100h from 2nd drive (1) the first sector (0) and only one sector
-n A:tmp.com
-rcx
100
-w
writing 0200h bytes (512 bytes)
```

## interrupt controller info
* https://en.wikibooks.org/wiki/X86_Assembly/Programmable_Interrupt_Controller

## Sanyo MBC550 - John Elliott 27 January 2016
* https://www.seasip.info/VintagePC/sanyo.html#serhw

## int 14h Serial, int 16h keyboard etc
* https://www.plantation-productions.com/Webster/www.artofasm.com/DOS/ch13/CH13-3.html#HEADING3-1

## ms-dos int services
* http://spike.scu.edu.au/~barry/interrupts.html#ah57

## debug search
```bat
debug basic.exe
s 1c67:ffff ffff e4 18   # searches for the bytes e4 and 18
```

## Time Bandit BANDIT.EXE crack
this is a first step. Skipping disk access at start.
```bat
debug BANDIT.EXE
-g 8ee8
-g =8ef3
```

## samdisk
https://simonowen.com/samdisk/
(samdisk-388-osx works on Macbook Air M1)
```bash
./samdisk IMAGE.td0  /Volumes/FLASHFLOPPY/IMAGE.dsk
```

## GOTEK
Goed nieuws! Gotek met FlashFloppy firmware werkt super op de Sanyo MBC-555. https://gotek.nl/
```ini
# in FF.CFG 
interface = shugart
host = pc-dos
pin02 = auto
pin34 = auto
nav-mode = indexed   # voor 0001-msdos211.img etc
indexed-prefix = ""
```

<img src="/pages/img/Screenshot%202021-05-21%20at%2000.09.05-sanyo-mbc555-gotek.jpg">

## Diversen
* wellicht deze bestellen: http://www.deviceside.com/fc5025.html (nee niet, mailcontact gehad. Werkt alleen voor 1.2MB diskdrives, en dan ook nog eens readonly.)
* 
* http://www.seasip.info/VintagePC/sanyo.html
* 
* Capacitor C9 on the board may need to be dealt with if disk access is slow or erratic (it was installed backwards at the factory)."
* http://www.vintage-computer.com/vcforum/showthread.php?24281-Teac-FD-54A
* "The PC floppy cable (assuming that you don't have any drives with a READY/ line on pin 34) can be a bit problematical to fabricate. On the other hand, if you can find a Teac FD235HF with the appropriate jumpers or a FD235F (which does have a READY/ line), you're in business, sort of." [[http://www.vintage-computer.com/vcforum/archive/index.php/t-23641.html?s=382f5103d732b9ff22b19f0dcba42069|source]]
* see [[disk]]

* index sensor measures optically the hole in the floppy disk. It marks the start of the current track. Read 'index sensor adjustment' in Sams Computer Facts about the Sanyo. I measure 208ms (milliseconds) for one turn of the disk. Around 5 turns a second and 300 turns per minute. Which is right according to 'spindle speed adjustment' part.
* Weird thing: when I remove the index sensor this has no effect on the readings on TP9 and TP10. There's 10us between the pulses.
* 'Precompensation adjustment': Connect input of a scope to TP1 on System Board. Set scope sweep to 1uSec, voltage to 2V and trigger to positive slope. Adjust Precompensation Control (RV1) for 2uSec from the rising edge of the first pulse to the rising edge of the second pulse. RESULT: 2uSec 500kHz.

## DISKCOPY
Als een diskette bad sectors heeft kun je geen DISKCOPY gebruiken. Je kunt in veel gevallen wel met COPY de bestanden overzetten. Eventueel kun je met DEBUG de sectors 1 voor laden en wegschrijven (l 0 0 5 1 -> w 0 0 0 5 1). 

## Info about segments:offsets
* https://thestarman.pcministry.com/asm/debug/Segments.html

## Test pins on mainboard
* TP1: Precompensation adjustment test. Should measure 2 uSec / 500 kHz. Adjust RV1 to fix.
* TP2: ??

## Drive Track Program
The following Basic program can be used to select Driva A or B, select side 0 or 1 and step the Drive Head to a specific track. To stop the program, press the BREAK key.
```gwbasic
10 INPUT "ENTER DRIVE (A OR B)"; D$
20 INPUT "ENTER SIDE (0 OR 1)"; S
30 IF S=0 THEN Y=0 ELSE Y=4
40 IF D$="A" THEN Z=0 ELSE Z=1
50 TS=0: OUT 28, Y+Z: OUT 8,8
60 FOR D=1 TO 500: NEXT D
70 INPUT "ENTER TRACK NUMBER"; T
80 IF T>40 THEN 70
90 IF T>TS THEN TR=T-TS ELSE TR=TS-T
100 IF T>TS THEN C=72 ELSE C=104
110 FOR X=1 TO TR
120 OUT 8,C
130 FOR D=1 TO 5: NEXT D
140 NEXT X: TS=T
150 PRINT "PRESS ANY KEY TO STOP"
160 A$=INKEY$: OUT 8,228: IF A$="" THEN 160 ELSE 70
```

## EDLIN
https://www.computerhope.com/edlin.htm
```
1l  # list from line one
1   # edit line 1
5i  # insert line(s) before line 5
q   # exit without saving
e   # save and exit
```

```
edlin autoexec.bat
i
  line of text
  ^Z
e
```

## wordstar manual
http://www.textfiles.com/bitsavers/pdf/microPro/Wordstar_3.3/Wordstar_3.3_Reference_Manual_1983.pdf

## debug.com
* fill memory with 0's: `e 0 ffff 0`
* `rcx` sets cx register. This register is used in debug.com to store the amount of bytes to write to the loaded (or newly created file).
* `l 0 0 5 1` load sector 5 of drive 0 at currentSeg:0000

## create a program with debug.com (ms-dos 1.25 without assemble command)
```
A> debug test.com
- e 100
  B8 {space} 00 {space} 4C {space} CD {space} 21 {enter}
-u
0AAC:0100 B8004C    MOV    AX,4C00
0AAC:0103 CD21      INT    21
-w
-q
```

```
-rcx
6
-n filename
-w
```
more info about Debug: https://thestarman.pcministry.com/asm/debug/debug.htm

## asm.com
* asm.com seems not to work well on Sanyo: http://www.datapackrat.com/86dos/index.shtml
* TinyASM works better. See: https://github.com/nanochess/tinyasm/

## technical info
* http://www.seasip.info/VintagePC/sanyo.html

## Game I/O
* https://github.com/phillipmacon/m.a.m.e/blob/master/src/devices/bus/a2gameio/gameio.cpp
```
 Apple II Game I/O Connector
    This 16-pin DIP socket is described in the Apple II Reference
    Manual (January 1978) as "a means of connecting paddle controls,
    lights and switches to the APPLE II for use in controlling video
    games, etc." The connector provides for four analog "paddle"
    input signals (0-150KΩ resistance) which are converted to
    digital pulses by a NE558 quad timer on the main board. The
    connector also provides several digital switch inputs and
    "annunciator" outputs, all LS/TTL compatible. Apple joysticks
    provide active high switches (though at least one third-party
    product treats them as active low) and Apple main boards have no
    pullups on these inputs, which thus read 0 if disconnected.
    While pins 9 and 16 are unconnected on the Apple II, they provide
    additional digital output and input pins respectively on the Sanyo
    MBC-550/555 (which uses 74LS123 monostables instead of a NE558).
    The Apple IIgs also recognizes a switch input 3, though this is
    placed on pin 9 of the internal connector rather than 16.
    ...
                                ____________
                   +5V   1 |*           | 16  (SW3)
                   SW0   2 |            | 15  AN0
                   SW1   3 |            | 14  AN1
                   SW2   4 |            | 13  AN2
                  /STB   5 |  GAME I/O  | 12  AN3
                  PDL0   6 |            | 11  PDL3
                  PDL2   7 |            | 10  PDL1
                   GND   8 |            |  9  (AN4/SW3)
                            ------------
```
