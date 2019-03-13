---
title: Batch files
---


#  passing arguments using set  
29 maart 2009
<code>
@echo off

rem ------------------------------------
set width=1024
set height=512
set ext=jpg
set src=source
set dst=%ext%%width%
set iview=c:\Progra~1\IrfanView\i_view32.exe

rem ------------------------------------
set a=%a% %cd%\%src%\*.*
set a=%a% /resample
set a=%a% /resize=(%width%,%height%)
set a=%a% /jpgq=100
set a=%a% /convert=%cd%\%dst%\*.%ext%

rem ------------------------------------
mkdir %dst%
%iview% %a%
dir /b %dst% > files.txt
</code>
