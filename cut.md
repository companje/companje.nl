---
title: Cut
---

# all characters from position x
  cut -c 27-

# remove all text after a character
  cut -f1 -d":"
This will convert "hello: world" into "hello".

# get last 4 characters (must be a better way)
  echo 12345678 | rev | cut -c -4 | rev
