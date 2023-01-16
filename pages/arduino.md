---
title: Arduino / AVR
---

# Keypad op 
- [NodeMCU-8266 - ESP-12S](https://www.tinytronics.nl/shop/en/development-boards/microcontroller-boards/with-wi-fi/ai-thinker-nodemcu-8266-esp-12s)
- [Keypad 3x4 Matrix - Membrane](https://www.tinytronics.nl/shop/en/switches/manual-switches/keypads/keypad-3x4-matrix-membrane)

<img style="width:100%" src="https://user-images.githubusercontent.com/156066/212722340-ae45dd83-2825-4b39-9ed1-b9f464d6dfbc.jpg">

```arduino
#include <Arduino.h>
#include <Keypad.h>

char keys[4][3] = { { '1', '2', '3' }, { '4', '5', '6' }, { '7', '8', '9' }, { '*', '0', '#' } };

byte rows[4] = { D1,D6,D5,D3 }; // pad pins 2,7,6,4
byte cols[3] = { D2,D0,D4 };    // pad pins 3,1,5

Keypad keypad = Keypad(makeKeymap(keys), rows, cols, 4, 3);

void setup() {
  Serial.begin(9600);
}

void loop() {
  char key = keypad.getKey();
  if (key) Serial.println(key);
}
```

# print to thermal printer
see <a href='/thermalprinter'>thermalprinter</a>

# about Timers
* https://sites.google.com/site/qeewiki/books/avr-guide/timers-on-the-atmega328

# i2c two Arduino's
* https://www.arduino.cc/en/Tutorial/MasterWriter

# TVOut etc.
* https://www.hackster.io/janost/avr-videoblaster-8026fd?offset=5&ref=similar&ref_id=7651
* http://www.circuitdb.com/?p=190
* http://forum.arduino.cc/index.php?topic=325378.0
* https://github.com/rossumur/Zorkduino
* http://martin.hinner.info/vga/pal.html (PAL info)

# MIDI input
* http://www.notesandvolts.com/2012/01/fun-with-arduino-midi-input-basics.html

# Pinout diagram
(::esqpm.png?direct&450|)

# Serial Bus Contention: no more than one transmitting device
https://learn.sparkfun.com/tutorials/serial-communication

# serial readLine
```c
char buf[100];

bool readLine(char *buf) {
  static int index = 0;
  while (Serial.available()) {
    byte ch = Serial.read();
    buf[index] = ch;
    if (ch=='
') {
      buf[index] = 0;
      index = 0;
      return true;
    } else {
      index++;
    }
  }
  return false;
}

void update() {
  if (readLine(buf)) {
    Serial.print(buf);
    Serial.print(" ");
    Serial.println(strlen(buf));
  }
}
```

# MACRO for printing multiple arguments with sprintf
```c
#define ECHO(s,x...) { char buf[100]; sprintf(buf,s,x); Serial.println(buf);}
```

# Shift registers
* https://www.arduino.cc/en/Tutorial/ShiftOut

# Ethernet Shield v2
* http://www.arduino.org/products/arduino-ethernet-shield-2

# Arduino game consoles
* https://www.kickstarter.com/projects/903888394/arduboy-card-sized-gaming
* http://gamebuino.com/shop/en/home/1-gamebuino-console.html
* https://www.indiegogo.com/projects/gamebuino-an-arduino-handheld-console

# driver for DCcEle DCcduino UNO
* http://www.wch.cn/downloads.php?name=pro&proid=178

# use avr-gcc without Arduino IDE
  export PATH=$PATH:/Applications/Arduino.app/Contents/Resources/Java/hardware/tools/avr/bin

# Pins
http://www.arduinopassion.com/wp-content/uploads/2013/02/Arduino-uno-Pinout.png

# Arduino socket server forwarding all data received on Serial port to all connected clients
* https://gist.github.com/companje/cf8db122687ae99ef244

# Mini USB adapter layout
* http://arduino.cc/en/Main/MiniUSB

# Arduino Wake-on-LAN
* My code: https://gist.github.com/companje/485d213eef733dffc8c0
* http://www.finalclap.com/tuto/arduino-wake-on-lan-repeater-80/
* http://www.megunolink.com/downloads/libraries/wake-on-lan/
* http://playground.arduino.cc/Main/ArduinoWaker

# Arduino button matrix without resistors or multiplexer
* [[https://gist.github.com/companje/f769248e4a22ad156f62|My Arduino code]]
* [[https://www.youtube.com/watch?v=Wd22Gm6D2hI|Dan Nixon's youtube explanation]]

# sprintf
```sprintf(charBuf, "%d,%d,%d", 5,3,2);```

# float to char[]
```dtostrf(floatVar, minStringWidthIncDecimalPoint, numVarsAfterDecimal, charBuf);```

# arduino mini USB
http://www.arduino.cc/en/Main/MiniUSB

# SDI12
sdi12: protocol om over 1 draad half-duplex communicatie te hebben. Er is een Arduino library voor.

# seeeduino atmega328 via avrdude
```bash
avrdude -cstk500v1 -b57600 -p m328p -P /dev/tty.usbserial-A600JGJZ -D -Uflash:w:Globe4D-demo-firmware.hex:i
```

# detect arduino bootloader
```python
if self.sendMessage([0x10, 0xc8, 0x64, 0x19, 0x20, 0x00, 0x53, 0x03, 0xac, 0x53, 0x00, 0x00]) != [0x10, 0x00]:
```

# rotary encoders
* My Encoder class with lookup table: https://gist.github.com/3049050 (based on [[http://www.circuitsathome.com/mcu/reading-rotary-encoder-on-arduino|CircuitsAtHome]]'s code)

* http://www.circuitsathome.com/mcu/reading-rotary-encoder-on-arduino
* http://hifiduino.wordpress.com/2010/10/20/rotaryencoder-hw-sw-no-debounce/
* my version based on the info above: https://gist.github.com/3049050

# terminal mode
```bash
avrdude -c usbasp -p atmega1280 -t
>> dump flash 0 512
>> dump eeprom 0 16
>> write eeprom 0 1 2 3 4
>> erase
>> d hfuse
>> d lfuse
```
more: http://www.nongnu.org/avrdude/user-manual/avrdude_9.html
use -u to enable writing of fuses

# reset fuses?
not sure
```bash
sudo avrdude -p atmega1280 -c usbasp -P usb -v  -U lfuse:w:0xef:m -U hfuse:w:0xc9:m
```

# bootloader with avrdude
```bash
/Applications/Arduino.app/Contents/Resources/Java/hardware/tools/avr/bin/avrdude -C/Applications/Arduino.app/Contents/Resources/Java/hardware/tools/avr/etc/avrdude.conf -v -v -v -v -patmega1280 -cusbasp -Pusb -Uflash:w:/Applications/Arduino.app/Contents/Resources/Java/hardware/arduino/bootloaders/atmega/ATmegaBOOT_168_atmega1280.hex:i -Ulock:w:0x0F:m 
```

# AVR documentatie
* http://nongnu.org/avr-libc/

# coder's toolbox for storing strings in variables
* http://coderstoolbox.net/string/

# WiFly
zie [[wifly]]

# watchdog arduino
Ik heb pas zelf een [[http://en.wikipedia.org/wiki/Watchdog_timer|watchdog timer]] gebouwd voor Arduino met de TimerOne library zie ik nu. Maar het kan ook met standaard avr functies. Zie [[http://lifeboat.co.nz/arduino/sensor_water_working_v1.pde|dit voorbeeld]].

```c
#include <avr/wdt.h>
...
wdt_enable(WDTO_8S); //start watchdog set for max 8 seconds
...
wdt_disable(); //turn off watchdog timer - if sketch gets this far it hasn't hung
```

# Seeeduino
 * Seeeduino 2.21 bevat een Atmega328 en is compatible met Arduino.
 * http://www.seeedstudio.com/wiki/Seeeduino_V2.2
* de baudrate voor avrdude voor dit type is 57600

# locatie van arduino hex file
Op mijn mac was dit de locatie van de hex file:
```
cd /private/var/folders/n9/n9UpGCFsHQW0bdm5gfLoRE+++TI/-Tmp-/console4814829202509611846.tmp
```

Dit heb ik uitgezocht met lsof | grep -i 'tmp' (list open files).

# avrdude
op windows (afkomstig uit Ultimaker marlin firmware upload.bat):
```batch
@echo off
set /p COMPORT=Which COM port to use for uploading (ex: COM5)? 
mode %COMPORT%: DTR=on > NUL
mode %COMPORT%: DTR=off > NUL
avrdude -c stk500v2 -b115200 -p atmega2560 -P %COMPORT% -D -Uflash:w:firmware.hex:i
pause
```

# avrdude commando
```bash
./avrdude -c arduino -b115200 -p ATMEGA328P -P /dev/tty.usbmodem411 -C avrdude.conf -D -Uflash:w:firmware.hex:i
```
  
# avrdude: parallel port access not available
```
avrdude: parallel port access not available in this configuration
avrdude: error at avrdude.conf:531: programmer type not specified
```
in het geval van deze melding kun je alle 'type = par' in de avrdude.conf  vervangen door 'type = serbb'. Ook al gebruik je de profielen helemaal niet. Zie [[http://www.arduino.cc/cgi-bin/yabb2/YaBB.pl?num=1288202459|deze]] forum post.
