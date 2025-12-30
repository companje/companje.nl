---
title: EXIF
---

# edit date
```bash
exiftool -DateTimeOriginal="2016:02:05 08:00:00" FILENAME.JPG
```

# exiftool
```bash
brew install exiftool
```

# mediainfo
```bash
brew install media-info
```

# exiftool update filemodifieddate based on exif data
```bash
exiftool * '-filemodifydate<createdate' -if '($createdate and (not $createdate eq "0000:00:00 00:00:00"))'
#exiftool * '-filemodifydate<creationdate' -if '($creationdate and (not $creationdate eq "0000:00:00 00:00:00"))'
exiftool * '-filemodifydate<datetimeoriginal' -if '($datetimeoriginal and (not $datetimeoriginal eq "0000:00:00 00:00:00"))'
exiftool * '-filemodifydate<mediacreatedate' -if '($mediacreatedate and (not $mediacreatedate eq "0000:00:00 00:00:00"))'
```

# exiftool: move based on date from exif data
```bash
exiftool -d %Y "-directory<filemodifydate" "-directory<createdate" "-directory<datetimeoriginal" .
```
Move image files from the current directory (.) into a directory hierarchy based on year/month. In this command the Directory tag is set from multiple other date/time tags. ExifTool evaluates the command-line arguments left to right, and latter assignments to the same tag override earlier ones, so the Directory for each image is ultimately set by the rightmost copy argument that is valid for that image. Specifically, Directory is set from DateTimeOriginal if it exists, otherwise CreateDate if it exists, and finally FileModifyDate (which always exists) is used as a fallback. [source](https://sno.phy.queensu.ca/~phil/exiftool/filename.html)

# copy exif data from one file to another
```bash
exiftool -tagsFromFile SRC DST
```

# prefix file with date
```bash
exiftool '-filename<filemodifydate' '-filename<createdate' '-filename<datetimeoriginal' -d "%Y-%m-%d-%%f.%%e" *
```
