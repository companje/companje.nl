---
title: Stat
---

=====get year from last modified date=====
  year=$(stat -f "%Sm" "$file" | rev | cut -c -4 | rev)
