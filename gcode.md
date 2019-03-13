---
title: GCODE
---

* ''M25'': stoppen SD-kaart print
* ''M109'' Sxxxx temperatuur instellen en wachten tot ie warm is (+ nog een paar seconden)
* ''M105'' get temperature
* ''M104'': Set Extruder Temperature: M104 S190
* ''G91'': Set to Relative Positioning
* ''G90'': Set to Absolute Positioning
* ''G01 F10000 X50 Y50'': move fast
* ''M106'': fan on
* ''M107'': fan off
* ''M84'': disable axes
* ''M110'': Set Current Line Number‎
* ''M20'': list files on SD-card
* ''G28'': Move to Origin
* ''M115'': Get Firmware Version and Capabilities

# Control LED brightness
```gcode
M42 S0      ;off
M42 S128    ;half brightness
M42 S255    ;on
```

# Make BEEP sound
```gcode
M300
M300 S300 P1000    ;S=freq Hz, P=duration
```

# Play tune
```gcode
M300 S100 P100
M300 S200 P100
M300 S300 P100
M300 S400 P100
M300 S500 P100
M300 S600 P100
M300 S700 P100
M300 S800 P100
```

# Put text on screen
```gcode
M10000
G4 P200
M10000
M10001 X10 Y25 SHeating nozzle...
M10000
;...
G4 P200 
M10000
M10001 X3 Y25 SDoodle3D Printing...
M10004 X1 Y56 W128 H8
M10003 X1 Y56 W10 H8
```

# current start/end gcode for Ultimaker Original
startcode:
```gcode
M104 S200
M109 S200
G21 ;metric values
G90 ;absolute positioning
M82 ;set extruder to absolute mode
M107 ;start with the fan off
G28 X0 Y0 ;move X/Y to min endstops
G28 Z0 ;move Z to min endstops
G1 Z15.0 F9000 ;move the platform down 15mm
G92 E0 ;zero the extruded length
G1 F200 E6 ;extrude 6 mm of feed stock
G92 E0 ;zero the extruded length again
G1 F9000
;Put printing message on LCD screen
M117 Printing...
```

endcode:
```gcode
G1 F1500 E390.2043
M107
M104 S0 ;extruder heater off
G91 ;relative positioning
G1 E-1 F300  ;retract the filament a bit before lifting the nozzle, to release some of the pressure
G1 Z+0.5 E-5 X-20 Y-20 F9000 ;move Z up a bit and retract filament even more
G28 X0 Y0 ;move X/Y to min endstops, so the head is out of the way
M84 ;steppers off
G90 ;absolute positioning
M104 S0
```

# documentation
* http://reprap.org/wiki/G-code

# binary gcode by repetier
* https://github.com/repetier/Repetier-Firmware/blob/master/repetier%20communication%20protocol.txt

# tools
* http://sourceforge.net/projects/pycam/
* http://tmpvar.com/project/gcode-simulator/
* http://wiki.linuxcnc.org/cgi-bin/wiki.pl?Simple_LinuxCNC_G-Code_Generators
* http://gcode.ws
* http://gcodesim.dietzm.de/
