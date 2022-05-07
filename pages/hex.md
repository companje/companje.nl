---
title: Hex editors
---

## hex calculator
<div class="row" style='background:#ddd'>
  <div class="col-3"><label for='dec'>Expression:</label><input name='expr' id='expr' type="text" value="0x0D-5" onchange="dec.value=eval(this.value); hex.value='0x'+eval(this.value).toString(16);bin.value='0b'+eval(this.value).toString(2).padStart(8, 0));"></div>  
  <div class="col-3"><label for='dec'>Dec:</label><input name='dec' id='dec' type="text" value=""></div>
  <div class="col-3"><label for='dec'>Hex:</label><input name='hex' id='hex' type="text" value=""></div>
  <div class="col-3"><label for='dec'>Bin:</label><input name='bin' id='bin' type="text" value=""></div>
</div>

## two's complement
```java
println((al&127)-128);
```

## tips
* hexfiend (tip v Casper)
* prima maar antieke hex viewer/editor: [[http://www.chmaas.handshake.de/delphi/freeware/xvi32/xvi32.htm#download|xvi32]]
* [[http://www.suavetech.com/0xed/0xed.html|0xED]] for osx 
* [[http://ridiculousfish.com/hexfiend/|hexfiend]]

## show hex code(s) of character in bash
```bash
echo -n "é" | od -A n -t x1
```
