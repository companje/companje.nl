---
title: Omdat het [[Luciferspel]] één van de allereerste spelletjes was die ik schreef voor mijn destijds al 10 jaar oude [[Sanyo MBC 555]] computer in [[1994]], heb ik een soort van nep-emulator gemaakt zodat je het luciferspel kunt spelen precies zo als ie toen ook werkte.
---

<code>BASIC [MS-DOS] Ver 1.32 41384 Bytes free </code>

Ik was in 2007 van plan een echte emulator voor mijn Sanyo MBC 555 te gaan schrijven, maar aangezien dat een behoorlijk pittige klus is denk ik dat ik daar van af zie. In dit PDF bestand staan een aantal van mijn bevindingen van toen ik met een hex-editor de executable aan het onderzoeken was.

Opvallend is dat deze Basic versie met aantal vreemde extra commando’s is uitgerust. De meest opvallende zijn hieronder vet gedrukt. GCURSOR en SYMBOL zijn het meest bijzonder. GCURSOR doet een Crosshair verschijnen, SYMBOL kon gebruikt worden om tekst in een groot lettertype af te beelden.

<blockquote>FUNCTION, ALL, AND, AS, AUTO, ABS, ASC, ATN, ATTR$, BASE, BEEP, CALL, CHAIN, CLEAR, CLOSE, CLS, COMMON, CONT, CREATE, CIRCLE, CONSOLE, CDBL, CINT, COS, CSNG, CSRLIN, CVD, CVI, CVS, COLOR, COM, CHR$, CVKAN$, DATA, DEFINT, DEFSNG, DEFDBL, DEFSTR, DEF, DELETE, DIM, DSKF, DATE$, ELSE, END, EQV, ERASE, ERROR, EDIT, EOF, ERL, ERR, EXP, FIELD, FILES, FOR, FIX, FRE, FN, GET, GO, GOTO, GOSUB, GCURSOR, HCOPY, HEX$, INKEY$, INPUT$, IF, IMP, INIT, INPUT, INP, INSTR, INT, KEY, KILL, KLEN, KCODE, KAN$, LET, LFILES, LINE, LIST, LLIST, LOAD, LOCATE, LPRINT, LSET, LEN, LOC, LOF, LOG, LPOS, LEFT$, MERGE, MOD, MID$, MKD$, MKI$, MKS$, NAME, NEXT, NEW, NOT, ON, OPEN, OPTION, OR, OUT, OFF, OCT$, POKE, PRINT, PUT, PAINT, PLAY, PEEK, POS, POINT, PRESET, PSET, PACK$, RANDOMIZE, READ, REM, RENUM, RESET, RESTORE, RESUME, RETURN, RSET, RUN, RND, RIGHT$, SAVE, SET, SPC, STEP, STOP, SUB, SWAP, SYSTEM, SETKEY, SYMBOL, SCREEN, SOUND, SGN, SIN, SQR, SEG, STICK, STRIG, SPACE$, STR$, STRING$, TAB, THEN, TINPUT, TO, TROFF, TRON, TAN, TIME$, USING, USR, UNPACK$, VAL, VARPTR , VIEW, WAIT, WEND, WHILE, WIDTH, WRITE, WINDOW, XOR</blockquote>

== Sourcecode ==

* Het bijgeleverde [[Sanyo Demo|DEMO.BAS]]
* [[Luciferspel]]
* [[Sanyo Basic Diverse experimenten|Diverse experimenten]]

== Directory listing (1999) ==

<code>
 Map van G:\1999\1999\Programmeerwerk\Sanyo Basic
 10-03-1983  00:00                34 AUTOEXEC.BAT
 28-09-1994  01:41               640 BAL.BAS
 10-12-2004  23:15               768 [[BAL.txt]]
 10-03-1983  21:00            46.976 [[BASIC.EXE]]
 20-08-1994  04:48               640 BEELD.BAS
 10-12-2004  23:18               768 [[BEELD.txt]]
 28-09-1994  00:33               128 BESTAND
 10-12-2004  23:19               256 BESTAND.TXT
 10-03-1983  03:26               384 BLSCHIJF.BAS
 10-12-2004  23:19               512 [[BLSCHIJF.TXT]]
 28-08-1995  01:13                 0 BREEDPRN.BAS
 10-03-1983  00:08                 6 CLS.COM               6 bytes om het scherm te legen.
 28-08-1995  01:13                 0 COMMAND.COM
 06-09-1994  01:34               896 CURSOR.BAS
 30-09-1994  01:30             2.048 CURSOR.CUR
 10-03-1983  01:35             1.792 DIEPBOOT.BAS
 10-03-1983  16:16             1.024 DIR.BAS
 17-09-1994  02:03               128 DRAAI.BAS
 17-09-1994  08:29               896 DRENTHE.BAS
 06-09-1994  00:19               768 DUBBLTEK.BAS
 17-09-1994  00:13               256 EDITOR.BAS
 19-08-1994  02:35             4.864 FENCE.BAS
 10-03-1983  11:01             3.584 FICHES.BAS
 17-09-1994  01:42               512 FIETS.BAS
 27-08-1994  04:50             1.664 GALG.BAS
 19-08-1994  02:31             3.200 GOKKEN.BAS
 19-08-1994  02:33             3.072 HOOGLAAG.BAS
 17-09-1994  00:57               640 INVADERS.BAS
 29-02-1984  09:00            11.521 [[IO.SYS]]
 22-08-1994  02:24               896 KAART.BAS
 10-03-1983  01:49               640 KEYA-Z.BAS
 10-03-1983  01:21             8.064 KOOLMIJN.BAS
 17-09-1994  01:42               384 LABELPRN.BAS
 17-09-1994  00:05               384 LDIAGRAM.BAS
 05-09-1994  01:24               512 LEES.LZN
 10-03-1983  01:08             3.328 LETTERS.BAS
 17-09-1994  00:17             1.152 LUCDOOS.BAS
 10-03-1983  00:50             4.352 LUCIFERS.BAS
 10-03-1983  00:38               256 M.BAS
 27-08-1994  00:30             2.176 MENU.BAS
 29-05-1999  20:40            21.504 mistery tower.xls
 23-11-1983  09:00             8.192 [[MSDOS.SYS]]
 17-09-1994  08:51             1.280 NHOLLAND.BAS
 17-09-1994  01:10               384 PACMAN.BAS
 28-09-1994  00:05             1.792 PAKKEN.BAS
 28-08-1995  01:13                 0 PEEK.BAS
 27-08-1994  01:53             2.176 PRINSTAL.BAS
 27-08-1994  00:36               256 PRINTER.BAS
 28-08-1995  01:13                 0 PRNDEMO.BAS
 27-08-1994  00:56             1.024 REKENEN.BAS
 17-09-1994  04:10               512 REKENING.BAS
 17-09-1994  08:11             1.408 SCHIJF.BAS
 28-09-1994  00:05               256 SCORE.PAK
 17-09-1994  05:08               128 SDIAGRAM.BAS
 28-09-1994  00:56               256 TEKENPRN.BAS
 10-03-1983  00:18               128 TEKST
 23-08-1994  01:10               768 TEKSTEN.BAS
 22-08-1994  05:01               512 TENNIS.BAS
 10-03-1983  01:47               128 TIJD.BAS
 17-09-1994  01:59             4.480 [[TOWER.BAS]]
 10-12-2004  23:17             5.248 [[TOWER.BAS|TOWER.TXT]]
 28-08-1995  01:13                 0 TOWERII.BAS
 28-08-1995  01:13                 0 TOWERSII.BAS
 10-03-1983  00:03             4.992 TXT.BAS
 23-08-1994  01:32             1.024 TYPSNEL.BAS
 10-03-1983  06:15             5.248 VIER.BAS
 17-09-1994  02:06               256 VIERDRAA.BAS
 17-09-1994  01:51               128 VIERKANT.BAS
 06-09-1994  00:34             2.944 WM.BAS
 06-09-1994  00:02               879 WOORDEN.GLG
 10-03-1983  02:53             4.608 WOORDL.BAS
               72 bestand(en)          180.632 bytes
</code>
