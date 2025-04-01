---
title: Electronics
---

# Weerstanden
100 Ω	Bruin - Zwart - Bruin - Goud
220 Ω	Rood - Rood - Bruin - Goud
330 Ω	Oranje - Oranje - Bruin - Goud
470 Ω	Geel - Violet - Bruin - Goud
1 kΩ	Bruin - Zwart - Rood - Goud
2.2 kΩ	Rood - Rood - Rood - Goud
3.3 kΩ	Oranje - Oranje - Rood - Goud
4.7 kΩ	Geel - Violet - Rood - Goud
10 kΩ	Bruin - Zwart - Oranje - Goud
22 kΩ	Rood - Rood - Oranje - Goud
47 kΩ	Geel - Violet - Oranje - Goud
100 kΩ	Bruin - Zwart - Geel - Goud
220 kΩ	Rood - Rood - Geel - Goud
470 kΩ	Geel - Violet - Geel - Goud
1 MΩ	Bruin - Zwart - Groen - Goud
  
# Buy awesome things directly from makers
* http://tindie.com/

# Weerstand in Serie en Parallel
```js
import java.util.*;

void setup() {
  int weerstanden[] = { 82000, 12000 } ; //470, 1200, 33, 1800};
  println("parallel: " + weerstandParallel(weerstanden) + " ohm");
  println("serie: " + weerstandSerie(weerstanden) + " ohm");
  println(spanningsDeler(5, 56000, 100));
  println(weerstand("grijs rood oranje"));
  println(spanningsDeler2(5, 4.5, 8200));
}

int weerstand(String kleuren) {
  List<String> alle_kleuren = Arrays.asList("zwart", "bruin", "rood", "oranje", "geel", "groen", "blauw", "paars", "grijs", "wit");
  String k[]= kleuren.split(" ");
  int r=0;
  r = 10*alle_kleuren.indexOf(k[0]);
  r += 1*alle_kleuren.indexOf(k[1]);
  r *= (int)pow(10,alle_kleuren.indexOf(k[2]));
  return r;
}

int weerstandSerie(int[] rr) {
  int sum = 0;
  for (int r : rr) sum+=r;
  return sum;
}

int weerstandParallel(int[] rr) {
  float sum = 0;
  for (int r : rr) sum+=1./r;
  return round(1/sum);
}

float spanningsDeler(float uIn, float r1, float r2) { //return spanning over R2
  return r2/(r1+r2)*uIn;
}

int spanningsDeler2(float uIn, float uUit, float r2) { //return weerstand van R1
  // TODO: how to solve without brute forcing?
  for (int r1=0; r1<1000000; r1++) {//brute force...
    if (uIn*(r1/(r2+r1))==uUit) return r1;
  }
  return -1;
}
```

# condensatoren
* http://www.justradios.com/uFnFpF.html

*http://reichelt.de
* **R = U / I**
  * ofwel: Weerstand = Spanning / Stroom 
  * ofwel: Ohm = Volt / Ampère (Wet van Ohm)

* **P = I * U**
  * ofwel Vermogen = Stroom * Spanning 
  * ofwel Watt = Ampère * Volt

* Watt = Ampère<sup>2</sup> * Ohm
* Watt = Volt<sup>2</sup> / Ohm
* Ampère = Watt / Volt
* Volt = Watt / Ampère

Meer op: http://www.popschoolmaastricht.nl/college_spanning_stroom.php

# Capacitors
*[[https://www.youtube.com/watch?feature=player_embedded&v=M2tJpEMIkWM|waarom je bij elke chip een klein condensatortje moet plaatsen]]

# Electronic cars
* http://e-volks.com/

# Soldering
* [[https://www.youtube.com/watch?v=7a3dA4r8rxc&feature=youtu.be&t=400|Smart way to solder Kynar wires]]
* [[http://elm-chan.org/docs/wire/wiring_e.html|extreme smd soldering]]
