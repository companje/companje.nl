---
title: Thermal Printer
---
<code cpp>

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
  Serial.println("Hilde Peters
");

  File f = SD.open("hilde.txt");
  if (f) {
    while (f.available()) {
      char c = f.read();
      Serial.write(c);
      if (c=='
') delay(625);
    }
    f.close();
    Serial.print("










");
  }  
}

void loop() {
}
```
