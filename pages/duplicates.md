---
title: Duplicate files
---

# DeltaWalker
File and Folder Comparison and Synchronization: <https://www.deltawalker.com/>

# install fdupes on mac
```bash
brew install fdupes
```

# find duplicates
```bash
fdupes .
```

# find and delete duplicates
preserve the first file in each set of duplicates and delete the rest without prompting the user
```bash
fdupes -dN .
```
recursive:
```bash
fdupes -dNr .
```

# dupeGuru Picture Edition 
This great tool finds duplicate images based on content.
* [dupeGuru](http://www.macupdate.com/app/mac/22724/dupeguru-picture-edition)
