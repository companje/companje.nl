---
title: Electronics
---

# Weerstanden
| resistance  | first | second | third |
| ------------- | ------------- | ------------- | ------------- |
|100 Ω |<span style="color:brown">Bruin</span>|Zwart|<span style="color:brown">Bruin</span>|
|220 Ω |<span style="color:red">Rood</a>|<span style="color:red">Rood</a>|<span style="color:brown">Bruin</span>|
|330 Ω |<span style="color:orange">Oranje</span>|<span style="color:orange">Oranje</span>|<span style="color:brown">Bruin</span>|
|470 Ω |<span style="color:yellow">Geel</span>|<span style="color:violet">Violet</span>|<span style="color:brown">Bruin</span>|
|1 kΩ | <span style="color:brown">Bruin</span>|Zwart|<span style="color:red">Rood</a>|
|2.2 kΩ | <span style="color:red">Rood</a>|<span style="color:red">Rood</a>|<span style="color:red">Rood</a>|
|3.3 kΩ | <span style="color:orange">Oranje</span>|<span style="color:orange">Oranje</span>|<span style="color:red">Rood</a>|
|4.7 kΩ | <span style="color:yellow">Geel</span>|<span style="color:violet">Violet</span>|<span style="color:red">Rood</a>|
|10 kΩ |<span style="color:brown">Bruin</span>|Zwart|<span style="color:orange">Oranje</span>|
|22 kΩ |<span style="color:red">Rood</a>|<span style="color:red">Rood</a>|<span style="color:orange">Oranje</span>|
|47 kΩ |<span style="color:yellow">Geel</span>|<span style="color:violet">Violet</span>|<span style="color:orange">Oranje</span>|
|100 kΩ | <span style="color:brown">Bruin</span>|Zwart|<span style="color:yellow">Geel</span>|
|220 kΩ | <span style="color:red">Rood</a>|<span style="color:red">Rood</a>|<span style="color:yellow">Geel</span>|
|470 kΩ | <span style="color:yellow">Geel</span>|<span style="color:violet">Violet</span>|<span style="color:yellow">Geel</span>|
|1 MΩ | <span style="color:brown">Bruin</span>|Zwart|<span style="color:green">Groen</span>|


  
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
