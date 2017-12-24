---
title: Micro3D printer
---
* http://micro3dfans.com/viewtopic.php?f=11&t=215&sid=4794fde495311e3792fd8e2dc65986b0&start=10
* https://printm3d.com/portal/forum/index#/discussion/27/open-g-code/p1
* https://printm3d.com/portal/forum/index#/discussion/27/open-g-code/p2
* http://wiki.micro3dfans.com/index.php?title=M3D_Software_-_The_official_Slicer
* https://printm3d.com/portal/forum/index#/discussion/893/where-is-the-open-source-software-support/p1
* https://printm3d.com/portal/forum/index#/discussion/893/where-is-the-open-source-software-support/p2
* https://github.com/repetier/Repetier-Firmware/blob/master/repetier%20communication%20protocol.txt
* https://github.com/repetier/Repetier-Host/blob/master/src/RepetierHost/model/GCode.cs
* communicates over Virtual COM/Serial with 115200 bps.
* collected info by using JetBrains dotPeek.
* sending `S` (0x53) returns 768 bytes of data stored in the Atmega EEPROM:
```
ç··x>·PE!Ô··ffÊ>‚ˇb?ˆ·°æÿ8\>é"îæ™¥Nø"···Bn·················································································································································································································································································································································································································································································································································································································································································±Ó√··)\7A····································Ù·ûWôC·GR15051501100055·
```
The seemingly raw data contains some interesting settings. You can see your serial number at the end. The list below contains the start position of the variable in the EEPROM followed by it's meaning (they are mostly 4 byte integers).
```
0: FirmwareVersion
4: FirmwareCRC
8: LastRecordedZValue
12: BacklashX
16: BacklashY
20: BedCompensationBackRight
24: BedCompensationBackLeft
28: BedCompensationFrontLeft
32: BedCompensationFrontRight
36: SpoolRecordID
40: FilimentTypeID
41: FilimentTemperature
42: FilamentAmount
46: BacklashExpansionXPlus
50: BacklashExpansionYLPlus
54: BacklashExpansionYRPlus
58: BacklashExpansionYRMinus
62: BacklashExpansionZ
66: BacklashExpansionE
70: ZCalibrationBLO
74: ZCalibrationBRO
78: ZCalibrationFRO
82: ZCalibrationFLO
86: ZCalibrationZO
689: HeaterCalibrationMode
690: XMotorCurrent
692: YMotorCurrent
694: ZMotorCurrent
696: HardwareStatus
698: HeaterTemperatureMeasurement_B
704: HoursCounterSpooler
726: XAxisStepsPerMM
730: YAxisStepsPerMM
734: ZAxisStepsPerMM
738: EAxisStepsPerMM
742: SavedZState
744: ExtruderCurrent
746: HeaterResistance_M
751: SerialNumber
```

* send 'M115
' (or other M codes) returns 'B004'.
* looking at the sourcecode 'B001' and 'B5' are also possible return values.
* looking at the source the Micro3D seems to have 2 modes: Bootloader-mode and Firmware-mode.
* GetCRCFromChip: sending 'CA' returns 4 bytes (0x45 0x50 0x1A 0x3E) which is some kind of CRC check of the chip. These 4 bytes are parsed to an Integer: 1162877502
* EraseChip: sending 'E' erases the EEPROM.
* SetAddress: sending 'A' followed by two bytes sets some kind of address.
* according to the sourcecode the printer uses the following microcontroller: ATxmega32C4
* WriteFirmwareToFlash: When a firmware update is done by the M3D software multiple packets are send in the following form: 'B' + byte1 + byte2 + payload.
* to check if the controller is in 'Bootloader' mode the software sends an 'M115
'. If the result is 'B' followed by 3 numeric characters then it's in Bootloader mode otherwise it's in 'Firmware/Application' mode.
* sending 'Q' (0x51) leaves the 'Bootloader' mode and goes into 'Firmware/Application' mode. However, sometimes it seems to Disconnect/Connect the serial port and then comes back in 'Bootloader' mode.
* when you receive 'wait' messages you know that you're in 'Application' mode. Sending a gcode command like 'M115
' returns 'e1' which is an error message meaning "Process parser returned not supported protocol.". I guess this is because the microcontroller expects a binary format (in the form of Repetier binary gcode?)
* btw: the software contains some hardcoded serial numbers (BK=black, GR=green, OR=orange, SL=silver). Possibly followed by some date/time stamp: YYMMDD0hhmm: BK15033001100, BK15040201050, BK15040301050, BK15040602050, BK15040801050, BK15040802100, GR15032702100, GR15033101100, GR15040601100, GR15040701100, OR15032701100, SL15032601050.
* Based on the experiments of evanchsa on the M3D forum I guess '0x82 0x10 0x00 0x00 0x73 0x00 0x06 0x46' is the binary version of M115 since it indeed returns the following when I send it to my Micro3D.
```
ok REPETIER_PROTOCOL:2 FIRMWARE_NAME:Micro3D FIRMWARE_VERSION:2015042701 MACHINE_TYPE:The_Micro X-SERIAL_NUMBER:GR150515011000550
```
* You can see mine is green and seems to be installed/shipped at May 15th 2015. The firmware version on the machine dates from 2015-04-27 which is weird since it should have been updated two days ago by the M3D software. Anyway, we're getting somewhere.
