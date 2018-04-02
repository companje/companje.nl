---
title: Vectrex
---

==about the 6809 processor==
* https://en.wikipedia.org/wiki/Motorola_6809
* http://techheap.packetizer.com/processors/6809/6809Instructions.html
* http://techheap.packetizer.com/processors/6809/6809.html

==cmoc compiler for 6809==
* http://sarrazip.com/dev/cmoc.html
* http://perso.b2b2c.ca/sarrazip/dev/cmoc-vectrex.html

==hello world==
<code c>
#include <vectrex/bios.h>

int main() { 
  while(1) { 
    waitretrace(); 
    printstr_c( 0x10, -0x50, "HELLO WORLD!" ); 
  } return 0; 
}
```

```bash
cmoc --vectrex hello_world.c
```

==emulators==
* http://www.vectrex.fr/ParaJVE/
* https://github.com/jhawthorn/vecx

==bios==
* http://www.playvectrex.com/designit/chrissalo/bios.htm

==vector generators==
* http://www.zektor.com/zvg/index.html
