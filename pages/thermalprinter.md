---
title: Thermal Printer (bijv Epson TM-T88ii)
---

# reference guide
http://www.i-o.cz/user/upload/TM88%20III/l7O-TM-T88II_TechnicalRefGuide.pdf

# ESC/POS
```c
normalSize = {0x1B, 0x21, 0x03}
bold = {0x1B, 0x21, 0x08}
boldMedium = {0x1B, 0x21, 0x20}
boldLarge = {0x1B, 0x21, 0x10}
```

# Hebben & Houden
```cpp
#include <SD.h>

void setup() {
  Serial.begin(19200);
  pinMode(10, OUTPUT);
  
  if (!SD.begin(10)) {
    Serial.println("Card failed, or not present");
    return;
  }
  
  Serial.print("!2");
  Serial.println("HEBBEN&HOUDEN");
  Serial.print("!");
  Serial.println("Hilde Peters");

  File f = SD.open("hilde.txt");
  if (f) {
    while (f.available()) {
      char c = f.read();
      Serial.write(c);
      if (c==' ... ') delay(625);
    }
    f.close();
    Serial.print(" ... ");
  }  
}

void loop() {
}
```
