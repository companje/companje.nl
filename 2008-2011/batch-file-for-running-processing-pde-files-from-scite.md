---
title: Batch file for running Processing pde files from Scite.
---

<code batch>
@echo off

::Use Scite to run Processing.org PDE sketches
::By Rick Companje
::www.companje.nl
::14-1-2008

::you need to include the processing core classes
::and lib folder to a subdirectoryin your sketchfolder
::called processing. See the short tutorial at www.companje.nl

set className=%1
set javafile=%className%.java
set CLASSPATH=

echo Scite for Processing: %className%

IF "%className%" == "" GOTO ERROR_CLASSNAME

echo // %javafile% > %javafile%
echo import processing.core.*; import java.applet.*; import java.awt.*; import java.awt.image.*; import java.awt.event.*; import java.io.*; import java.net.*; import java.text.*; import java.util.*; import java.util.zip.*; import javax.sound.midi.*; import javax.sound.midi.spi.*; import javax.sound.sampled.*; import javax.sound.sampled.spi.*; import java.util.regex.*; import javax.xml.parsers.*; import javax.xml.transform.*; import javax.xml.transform.dom.*; import javax.xml.transform.sax.*; import javax.xml.transform.stream.*; import org.xml.sax.*; import org.xml.sax.ext.*; import org.xml.sax.helpers.*; public class %className% extends PApplet { >> %javafile%

type *.pde >> %javafile%

echo static public void main(String args[]) { PApplet.main(new String[] {"%className%" }); )>> %javafile%

javac %javafile% -classpath processing\lib