---
title: Ultimaker
---

See [[3DPrinting]]

# stillere fan
"dit is de fan: http://www.ebay.com/itm/Lufter-5V-0-38W-25x25x6mm-3-7m-h-23dBA-Sunon-MC25060V2-A99-/231085070846?hash=item35cdbe59fe
(en dit is de bijbehorende thread: https://ultimaker.com/en/community/6442-feature-request-allow-to-turn-off-fan-behind-extruder)"

# cura-build
* `git clone https://github.com/Ultimaker/cura-build`
* set `PYTHONPATH=/usr/local/lib/python3/dist-packages`
* https://github.com/trigrab/cura-build/commit/354befb7933626bb34ca2972e9fb1ea71743ba8c
* https://github.com/Ultimaker/cura-build/issues/9

# cura-build on osx 10.11
* https://github.com/Ultimaker/cura-build/issues/30


# doodle3d startcode/endcode for Doodle3D Ultimaker 2/2go
```
;Generated with Doodle3D (ultimaker2)
M10000
G4 P200
M10000
M10001 X10 Y25 SHeating nozzle...
M109 S{printingTemp} ;set target temperature 
{if heatedBed}M190 S{printingBedTemp} ;set target bed temperature
M10000
G4 P200 
M10000
M10001 X3 Y25 SDoodle3D Printing...
M10004 X1 Y56 W128 H8
M10003 X1 Y56 W10 H8
G21 ;metric values
G90 ;absolute positioning
M107 ;start with the fan off
G28 ; home to endstops
G1 Z15 F9000 ;move the platform down 15mm
G92 E0 ;zero the extruded length
G1 F200 E10 ;extrude 10mm of feed stock
G92 E0 ;zero the extruded length again
G1 F9000
M117 Printing Doodle...   ;display message (20 characters to clear whole screen)
```

```
M107 ;fan off
G91 ;relative positioning
G1 E-1 F300 ;retract the filament a bit before lifting the nozzle, to release some of the pressure
G1 Z+5.5 E-5 X-20 Y-20 F9000 ;move Z up a bit and retract filament even more

M10000
;G4 P5
M10000
M10001 X10 Y25 SDoodle3D finished!

G28 ;home the printer
M84 ;disable axes / steppers
G90 ;absolute positioning
M104 S{preheatTemp}
{if heatedBed}M140 S{preheatBedTemp}
M117 Done                 ;display message (20 characters to clear whole screen)
```


# help
* https://ultimaker.com/nl/support/view/11703-extrusion-problems
* https://ultimaker.com/nl/support/view/223-atomic-method

# boutjes en moertjes
* http://rvspaleis.nl

# build marlin firmware
* http://daid.mine.nu/~daid/marlin_build/
* http://wiki.ultimaker.com/Marlin_firmware_for_the_Ultimaker

# Cura
* http://daid.github.com/Cura/

* firmware backuppen:
```bash
avrdude -c stk500v1 -b57600 -p atmega1280 -P /dev/tty.usbserial-A600f8w2 -D -Uflash:r:firmware.hex:i
```
of
```bash
avrdude -c stk500v2 -b115200 -p atmega2560 -P /dev/tty.usbmodem411 -D -Uflash:r:firmware.hex:i
```

* writing firmware
```bash
avrdude -c stk500v2 -b115200 -p atmega2560 -P /dev/tty.usbmodem411 -D -Uflash:w:firmware.hex:i
```
```bash
avrdude -c stk500v2 -b115200 -p atmega2560 -P /dev/tty.usbmodem621 -D -Uflash:w:cfg_4fd875a44fb67.hex.txt:i
```

```bash
avrdude -c stk500v1 -b57600 -p atmega1280 -P /dev/tty.usbserial-A9005d8c -D -Uflash:w:firmware.hex:i
```

# PLA
* www.faberdashery.co.uk
