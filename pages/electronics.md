---
title: Electronics
---

# Weerstand in Serie en Parallel
```java
void setup() {
  int weerstanden[] = { 470, 1200, 33, 1800};
  println("parallel: " + weerstandParallel(weerstanden) + " ohm");
  println("serie: " + weerstandSerie(weerstanden) + " ohm");
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
