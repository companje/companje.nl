---
title: Excel
---

# returns true, when an error occurs or when B2 is not 1 higher than B1
```vbscript
=IFERROR(VALUE(B2)<>VALUE(B1)+1, TRUE)
```

# bewerk huidige cell
```
Control + U
```

# freeze row / set row as header
View > Freeze Panes > Freeze Top Row
