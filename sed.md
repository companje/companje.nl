---
title: sed
---

# prefix and postfix to each line
```bash
ls | sed 's/.*/hoi&?doei/'
#hoiids-unique.txt?doei
#hoiids.txt?doei
```

# remove whitespace
```bash
tr -d ' ' < ids-unique.txt
```