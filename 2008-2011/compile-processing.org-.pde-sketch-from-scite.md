---
title: Compile Processing.org .pde sketch from Scite
---
I wrote a batchfile to be able to use Scite as external editor for Processing without having to Alt+TAB all the time to run it from the Processing IDE. Just press F5 in Scite and the script creates the needed class files and runs it.

You need (projects:processing-for-scite.zip|this ZIP-file) and put it into your projects sketch folder. Currently you have to copy it into every sketchfolder you want to run from Scite but feel free to change the code to make it work from a central location.

The batchfile generates a temporary .java file as a wrapper for the   pde files. All your pde files in the sketch directory will be included in .java file within a class that extends PApplet.

View source: [[projects:Batch file for running Processing pde files from Scite]].

Add this to your SciTEUser.properties:
<code properties>
### Processing PDE
command.go.*.pde=$(FileDir)\processing
un.bat $(FileName)
command.help.*.pde="http://processing.org/reference/$(CurrentWord)_.html"
command.help.subsystem.*.pde=2
</code>

*F5 will run the batch-file using the name the current PDE file as the classname. So be sure to select your sketch's main pde when pressing F5.
*F1 invokes help for the current selected word and shows the right page in the reference at the Processing website.

(tag>Processing Tech)


~~DISCUSSION~~
