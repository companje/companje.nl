====== Thermal Printer ======
<code cpp>

#include <SD.h>

void setup() {
  Serial.begin(19200);
  pinMode(10, OUTPUT);
  
  if (!SD.begin(10)) {
    Serial.println("Card failed, or not present");
    return;
  }
  
  Serial.print("\x1b\x21\x32");
  Serial.println("HEBBEN&HOUDEN");
  Serial.print("\x1b\x21\x02");
  Serial.println("Hilde Peters\n");

  File f = SD.open("hilde.txt");
  if (f) {
    while (f.available()) {
      char c = f.read();
      Serial.write(c);
      if (c=='\n') delay(625);
    }
    f.close();
    Serial.print("\n\n\n\n\n\n\n\n\n\n\n");
  }  
}

void loop() {
}
</code>