---
title: Sanyo MBC-550/555
---
* http://www.seasip.info/VintagePC/sanyo.html
* Capacitor C9 on the board may need to be dealt with if disk access is slow or erratic (it was installed backwards at the factory)."
* http://www.vintage-computer.com/vcforum/showthread.php?24281-Teac-FD-54A
* "The PC floppy cable (assuming that you don't have any drives with a READY/ line on pin 34) can be a bit problematical to fabricate. On the other hand, if you can find a Teac FD235HF with the appropriate jumpers or a FD235F (which does have a READY/ line), you're in business, sort of." [[http://www.vintage-computer.com/vcforum/archive/index.php/t-23641.html?s=382f5103d732b9ff22b19f0dcba42069|source]]
* see [[disk]]

* index sensor measures optically the hole in the floppy disk. It marks the start of the current track. Read 'index sensor adjustment' in Sams Computer Facts about the Sanyo. I measure 208ms (milliseconds) for one turn of the disk. Around 5 turns a second and 300 turns per minute. Which is right according to 'spindle speed adjustment' part.
* Weird thing: when I remove the index sensor this has no effect on the readings on TP9 and TP10. There's 10us between the pulses.
* 'Precompensation adjustment': Connect input of a scope to TP1 on System Board. Set scope sweep to 1uSec, voltage to 2V and trigger to positive slope. Adjust Precompensation Control (RV1) for 2uSec from the rising edge of the first pulse to the rising edge of the second pulse. RESULT: 2uSec 500kHz.

==Test pins on mainboard==
* TP1: Precompensation adjustment test. Should measure 2 uSec / 500 kHz. Adjust RV1 to fix.
* TP2: ??

==Drive Track Program==
The following Basic program can be used to select Driva A or B, select side 0 or 1 and step the Drive Head to a specific track. To stop the program, press the BREAK key.
<code gwbasic>
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

==wordstar manual==
http://www.textfiles.com/bitsavers/pdf/microPro/Wordstar_3.3/Wordstar_3.3_Reference_Manual_1983.pdf

==debug.com==
* fill memory with 0's: `e 0 ffff 0`
* `rcx` sets cx register. This register is used in debug.com to store the amount of bytes to write to the loaded (or newly created file).
```

==create a program with debug.com==
A> debug test.com
- e 100
  B8 {space} 00 {space} 4C {space} CD {space} 21 {enter}
-u
0AAC:0100 B8004C    MOV    AX,4C00
0AAC:0103 CD21      INT    21
-w
-q
```

==asm.com==
* http://www.datapackrat.com/86dos/index.shtml

==technical info==
* http://www.seasip.info/VintagePC/sanyo.html
