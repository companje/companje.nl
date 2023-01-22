---
title: ZIP / 7z etc. compression
---

## add to (uncompressed) archive with 7zip
```
a = add
-mx0 = no compression
-bb1 = do some logging
```

```bash
7zz a -mx0 -bb1 target.7z FOLDER
```

## extract with 7zip
```bash
7z x filename.7z 
```

## unzip to folder
```bash
unzip images.zip -d images
```
