# connect with telnet
```
telnet 192.168.0.100 4352
```

# power status opvragen
```
%1POWR ?
```
* 0 = Standby
* 1 = Power on
* 2 = Cooling down
* 3 = Warming up

# resolutie opvragen
```
%2IRES
```
returns: `%2IRES=1920 x 1200`

# on/off using netcat
```
printf '%s' '%1POWR 0\r' | nc -w 1 192.168.0.100 4352
printf '%s' '%1POWR 1\r' | nc -w 1 192.168.0.100 4352
```
# send from Arduino with Ethernet2 shield
```
#include <SPI.h>
#include <Ethernet2.h>

byte macaddres[] = { 0x90, 0xA2, 0xDA, 0x10, 0x03, 0xCB };
IPAddress arduino_ip(192, 168, 0, 222);
IPAddress projector_ip(192, 168, 0, 100);

const uint16_t projector_port = 4352;

EthernetClient client;

void setup() {
  Ethernet.begin(macaddres, arduino_ip);

  delay(1000);

  if (client.connect(projector_ip, projector_port)) {
    client.print("%1POWR 1\r");
    client.stop();
  }
}

void loop() {
}
```
# document containing info
* https://www.optoma.nl/uploads/manuals/W415-M-nl.pdf
