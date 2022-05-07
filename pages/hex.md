---
title: Hex editors
---

## hex calculator
<div class="row" style='background:#ddd'>
  <div class="col-3"><label for='dec'>Dec:</label><input name='dec' id='dec' type="text" value="13" onchange="hex.value=parseInt(this.value).toString(16);"></div>
  <div class="col-3"><label for='hex'>Hex:</label><input id='hex' type="text" value="0D" onchange="dec.value=parseInt(this.value, 16)"></div>
</div>

## two's complement
```java
println((al&127)-129);
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
