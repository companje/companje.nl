---
title: STM32 / Nucleo
---
==steps I took to get stm32nucleo mb1136 C-03 running in Arduion IDE on OSX 10.10 Yosemite==
* no soldering/desoldering needed in my case.
* install Arduino 1.6.5 (don't use 1.6.6 or 1.6.7 because of [[https://github.com/rogerclarkmelbourne/Arduino_STM32/issues/147|errors]])
* tools -> board -> board manager... -> install 'Arduino Due'
* download [[https://github.com/rogerclarkmelbourne/Arduino_STM32/archive/master.zip|Arduino_STM32]]
* unpack to ~/Documents/Arduino/hardware/Arduino_STM32
* Install libusb from http://rudix.org/packages/libusb.html (in case of upload errors)
* restart Arduino IDE
* select tools -> board -> STM Nucleo F103RB (STLink)
* select tools -> port -> usbserial or usbmodem1234
* load Blink example
* upload Blink example
* to communicate with Serial Monitor in the Arduino IDE I had to use Serial1 (instead of just Serial). ([[http://stm32duino.com/viewtopic.php?f=3&t=512&start=20|more info]]) 

==pins==
(::stm-nucleo-pins.jpg?direct&700|)

==resources==
* http://www.st.com/web/catalog/tools/FM116/SC959/SS1532/LN1847/PF259875?icmp=nucleo-ipf_pron_pr-nucleo_feb2014&sc=nucleoF103RB-pr
* https://developer.mbed.org/platforms/ST-Nucleo-F103RB/
* http://www.stm32duino.com/
* https://www.youtube.com/watch?v=-zwGnytGT8M
* https://github.com/rogerclarkmelbourne/Arduino_STM32
* https://github.com/rogerclarkmelbourne/Arduino_STM32/wiki/Installation
* [[http://www.stm32duino.com/viewtopic.php?t=248|problems with nucleo with arduino ide]]
* http://www.stm32duino.com/viewforum.php?f=29
* http://www.rogerclark.net/
* http://www.st.com/web/en/resource/technical/document/user_manual/DM00105823.pdf

==read the docs==
* [[https://github.com/rogerclarkmelbourne/Arduino_STM32/blob/master/STM32F1/variants/nucleo_f103rb/infos_pdf/Nucleo_F103RB_hardware_preparation.pdf|Nicleo F103RB hardware preparation PDF]]
  * "Desolder the 0-Ohm-resistors on SB55 and SB54 (bottom right) to cut the trace. Solder a little bridge on: SB16(MCO) (top left) and SB50 (bottom middle)"

==dyld: Library not loaded: /usr/local/lib/libusb-1.0.0.dylib==
"I got past the libusb issue with an OSX package here: http://rudix.org/packages/libusb.html"

==SPI_2==
it is possible to use 2 SPI ports:
    Using the first SPI port (SPI_1)
    SS    <-->  PA4 <-->  BOARD_SPI1_NSS_PIN
    SCK   <-->  PA5 <-->  BOARD_SPI1_SCK_PIN
    MISO  <-->  PA6 <-->  BOARD_SPI1_MISO_PIN
    MOSI  <-->  PA7 <-->  BOARD_SPI1_MOSI_PIN

    Using the second SPI port (SPI_2)
    SS    <-->  PB12 <-->  BOARD_SPI2_NSS_PIN
    SCK   <-->  PB13 <-->  BOARD_SPI2_SCK_PIN
    MISO  <-->  PB14 <-->  BOARD_SPI2_MISO_PIN
    MOSI  <-->  PB15 <-->  BOARD_SPI2_MOSI_PIN
    
