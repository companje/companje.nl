---
title: BBC Micro
---

```basic
cls
rnd(12)
print “Mijn naam is “ NAAM “. Hoe heet jij?”
input REPLY
repeat
until reply=correct or try=3
list 70,100
```

```basic
10 dim name$(50)
20 dim date$(50)
30 for item = 1 to 10
40   read name$(item), date$(item)
50 next item
60 data david, 3 nov, john, 6 aug ……
```

# Sound
```basic
sound 1,-15,4,20  (channel,volume -15 (hard)…0 (stil), 4=C, 20=length
enveloppe: heeft veel parameters. maar kan samengestelde tonen genereren.
```

# alternatief voor PEEK en POKE op de BBC Micro:
```basic
PEEK -> N = ?65088
POKE -> ?65120 = 108
```