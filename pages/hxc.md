#HxC Floppy Emulator : Floppy image file converter

```
HxC Floppy Emulator : Floppy image file converter v2.16.3.2
Copyright (C) 2006-2024 Jean-Francois DEL NERO
This program comes with ABSOLUTELY NO WARRANTY
This is free software, and you are welcome to redistribute it
under certain conditions;

libhxcfe version : 2.16.3.2

Options:
  -help 			: This help
  -license			: Print the license
  -verbose			: Verbose mode
  -script:[filename]		: Script file to execute
  -modulelist			: List modules in the libhxcfe [FORMAT]
  -rawlist			: Disk layout list [DISKLAYOUT]
  -interfacelist		: Floppy interfaces mode list [INTERFACE_MODE]
  -finput:[filename]		: Input file image
  -foutput:[filename]		: Output file image
  -conv:[FORMAT] 		: Convert the input file
  -reffile:[filename] 		: Sector to sector copy mode. To be used with -conv : specify the format reference image
  -uselayout:[DISKLAYOUT]	: Use the Layout [DISKLAYOUT]
  -usb:[DRIVE] 			: start the usb floppy emulator
  -infos			: Print informations about the input file
  -ifmode:[INTERFACE_MODE]	: Select the floppy interface mode
  -singlestep			: Force the single step mode
  -doublestep			: Force the double step mode
  -list				: List the content of the floppy image
  -getfile:[FILE]		: Get a file from the floppy image
  -putfile:[FILE]		: Put a file to the floppy image

Usage examples :

Single output conversion -> Convert a disk image to HFE (use "-modulelist" to see the supported "-conv" file image formats) :

  hxcfe -finput:"path/input.img" -conv:HXC_HFE -foutput:"path/output.hfe"

Multiples-output conversion -> Convert a disk image to HFE and BMP :

  hxcfe -finput:"path/input.img" -conv:HXC_HFE -foutput:"path/output.hfe" -conv:BMP_DISK_IMAGE -foutput:"path/output.bmp"

Disk format/layout conversion -> Convert a raw disk image to HFE using a specific layout (use "-rawlist" to see the available layouts/formats) :

  hxcfe -finput:"path/input.img" -uselayout:PUMA_ROBOT_DD_640KB -conv:HXC_HFE -foutput:"path/output.hfe"

Disk format/layout generation -> Generate an empty HFE using a specific layout without any image (use "-rawlist" to see the available layouts/formats) :

  hxcfe -uselayout:PUMA_ROBOT_DD_640KB -conv:HXC_HFE -foutput:"path/output.hfe"

Sector by sector copy mode using a reference image : use "reference.hfe" as layout and copy and update each of its sectors from the "input.hfe" image to the "output.hfe".

  hxcfe  -finput:"path/input.hfe" -conv:HXC_HFE -foutput:"path/output.hfe" -reffile:"path/reference.hfe"
```
