---
title: Find
---

# find jpg's case insensitive
  find . -iname "*.jpg"

# merge (jpg) files from multiple folders to single folder
  find . -iname "*.jpg" -exec cp {} DESTINATION_FOLDER/ \;
