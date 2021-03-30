---
title: Find
---

# find jpg's case insensitive
```bash
find . -iname "*.jpg"
```

# merge (jpg) files from multiple folders to single folder
```bash
find . -iname "*.jpg" -exec cp {} DESTINATION_FOLDER/ \;
```

