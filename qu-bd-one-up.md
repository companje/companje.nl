---
title: QU-BD One Up
---

See [[3dprinting]]

*[[http://www.fabric8r.com/forums/showthread.php?1316-TIPS-Getting-it-to-print|Important info before using the printer]]
* [[http://www.fabric8r.com/forums/showthread.php?1308-Customizing-Marlin-Firmware-for-the-One-Up-and-Two-Up|Uploading customized firmware]]
* [[http://www.fabric8r.com/forums/showthread.php?1247-Assembly-videos-and-other-resources|Assembly videos and other resources]]

=====firmware info=====
M119:
```FIRMWARE_NAME:Marlin V1; Sprinter/grbl mashup for gen6 FIRMWARE_URL:https://github.com/ErikZalm/Marlin/ PROTOCOL_VERSION:1.0 MACHINE_TYPE:RapidBot3 EXTRUDER_COUNT:1 UUID:00000000-0000-0000-0000-000000000000
```
---------
=====after firmware update=====
https://github.com/QU-BD/Up-Marlin
```
FIRMWARE_NAME: Up-Marlin 1.1 (Marlin derivative) FIRMWARE_URL:https://github.com/QU-BD/Up-Marlin PROTOCOL_VERSION:1.0 MACHINE_TYPE:QUBD One-Up EXTRUDER_COUNT:1 UUID:00000000-0000-0000-0000-000000000000
```
On Boot:
```
Marlin1.0.0
echo: Last Updated: Apr  4 2014 11:47:31 | Author: (none, default config)
Compiled: Apr  echo:SD init fail
```
M501
```echo:Hardcoded Default Settings Loaded
echo:Steps per unit:
echo:  M92 X80.00 Y80.00 Z2560.00 E90.95
echo:Maximum feedrates (mm/s):
echo:  M203 X500.00 Y500.00 Z3.00 E45.00
echo:Maximum Acceleration (mm/s2):
echo:  M201 X9000 Y9000 Z100 E10000
echo:Acceleration: S=acceleration, T=retract acceleration
echo:  M204 S500.00 T3000.00
echo:Advanced variables: S=Min feedrate (mm/s), T=Min travel feedrate (mm/s), B=minimum segment time (ms), X=maximum XY jerk (mm/s),  Z=maximum Z jerk (mm/s),  E=maximum E jerk (mm/s)
echo:  M205 S0.00 T0.00 B20000 X10.00 Z0.40 E5.00
echo:Home offset (mm):
echo:  M206 X0.00 Y0.00 Z0.00
echo:PID settings:
echo:   M301 P22.00 I1.38 D87.57
ok
```
