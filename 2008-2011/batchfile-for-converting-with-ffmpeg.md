---
title: Batchfile for converting with ffmpeg
---
Dit batch scriptje post ik eigenlijk voor mezelf omdat ik 'm vaak net kwijt ben als ik 'm zoek. Maar als het je van pas komt voel je vrij om het te gebruiken. Het script roept het conversie programma ffmpeg aan om vervolgens het input filmpje te voorzien van zoveel mogelijk keyframes waardoor je makkelijk snel heen en weer kunt scrubben door een mpeg bestand. We gebruiken het voor [[http://www.globe4d.com/|Globe4D]] omdat je bij uitstek daar kriskras door een videobestand wilt scrollen. Maar ik gebruik het ook voor de fiets installatie van [[http://www.groeninnovaties.nl|Erik Groen]] die nog tot november draait in het [[http://www.gorcumsmuseum.nl|Gorcums Museum]].
\
<code>
@echo off
cd /d %0\..

set size=1024x512
color 1f

cls
echo Globe4D - The interactive four dimensional globe
echo Copyright (c) 2005-2010 www.globe4d.com
echo.
echo This movie converter script uses ffmpeg to add
echo the maximum amount of keyframes to a (mpg) movie.
echo.

:inputfile
if (%1)==() ( 
  set /p in=Enter input file name ^(use TAB to select^): 
) else (
  set in=%~1
)

if not exist "%in%" echo File '%in%' not found && goto :inputfile

set /p size=Enter output resolution (default=%size%): 

set inNoExt=%in:~0,-4%
set out=%inNoExt%-%size%-gop1.mov

set /p out=Enter output filename (default=%out%): 

echo.
ffmpeg -i "%in%" -s %size% -sameq -g 1 -y "%out%"

pause
</code>

Deze doet hetzelfde maar dan voor een image sequence:

<code>
ffmpeg -f image2 -i frame%03d.png -s 1024x512 output.mov
</code>

Nog eentje die ik ook dikwijls nodig heb:

<code>
@echo off
cd /d %0\..
cd
set log=Globe4D.log
title Globe4D - the interactive four dimensional globe
echo Globe4D - The interactive four dimensional globe
echo Copyright (c) 2005-2010 www.globe4d.com
rem -------------------------------------------------
echo Computer started: %DATE% %TIME% >> %log%
:BEGIN
echo Software started: %DATE% %TIME% >> %log%
call Globe4D.exe >> %log%
if "%ERRORLEVEL%"=="0" goto EXPLORER
echo Software stopped: %DATE% %TIME% (err=%ERRORLEVEL%) >> %log%
echo. >> %log%
pause
goto BEGIN

:EXPLORER
start explorer %CD%
</code>

Filmpje van je camera omzetten naar MPEG.
<code>
ffmpeg -i MVI_0131.AVI -r 25 -sameq output.mov
</code>

Een filmpje van je camera 90 graden roteren (met mencoder).
<code>
mencoder -vf rotate=1 -o OUTPUT.AVI -oac copy -ovc lavc MVI_7590.AVI 
</code>

Remove audio from a movie with ffmpeg:
<code>
ffmpeg -i input.mov -an output.mov
</code>

Combine jpg and mp3 audio to mpg (in this case portrait). Be sure to use RGB jpg's instead of CMYK.
<code>
ffmpeg -y -i vogels.jpg -loop_input -i vogels.mp3 -s 320x480 vogels320x480.mpg
of:
ffmpeg -y -b 2500k -r 30 -i yellow-brick-road.jpg -i brand-new-day.mp3 -map 0:0 -map 1:0 -vsync 1 -sameq  -vcodec mpeg4 -s 320x480 result2.mp4
</code>

Split video: see [[http://ubuntuforums.org/showthread.php?t=480343]]
(tag>)


~~DISCUSSION~~
