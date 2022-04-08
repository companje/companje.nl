---
title: Hex editors
---

<fieldset>
  <legend>Hex calculator</legend>
  <div class="row">
    <div class="col-3">Dec:<input id='dec' type="text" value="13" onchange="hex.value=parseInt(this.value).toString(16);"></div>
    <div class="col-3">Hex:<input id='hex' type="text" value="0D"></div>
  </div>
</fieldset>

* hexfiend (tip v Casper)
* prima maar antieke hex viewer/editor: [[http://www.chmaas.handshake.de/delphi/freeware/xvi32/xvi32.htm#download|xvi32]]
* [[http://www.suavetech.com/0xed/0xed.html|0xED]] for osx 
* [[http://ridiculousfish.com/hexfiend/|hexfiend]]

# show hex code(s) of character in bash
```bash
echo -n "é" | od -A n -t x1
```
