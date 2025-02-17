---
title: ZIP / 7z etc. compression
---

## backup map zonder compressie op windows
```batch
"c:\program files\7-zip\7z.exe" a -tzip -mx0 -bb1 -xr!".git" "backups/backup.zip" "bronmap"
```

## maak een 7z bestand (no compression) van elke submap
```bash
for dir in */; do 7zz a -mx0  "${dir%/}.7z" "$dir"; done
```

## uncompressed archive exclude folders
```bash
7zz a -mx0 -bb1 '-xr!.dropbox' '-xr!.dropbox.cache' "Snapshot.7z" /Volumes/Data/Dropbox
```

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
