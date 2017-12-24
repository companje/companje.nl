---
title: EXIF
---

# exiftool
```bash
brew install exiftool
```

# mediainfo
```bash
brew install media-info
```

# update filemodifieddate based on exif data
```bash
exiftool * '-filemodifydate<createdate' -if '($createdate and (not $createdate eq "0000:00:00 00:00:00"))'
exiftool * '-filemodifydate<datetimeoriginal' -if '($datetimeoriginal and (not $datetimeoriginal eq "0000:00:00 00:00:00"))'
exiftool * '-filemodifydate<mediacreatedate' -if '($mediacreatedate and (not $mediacreatedate eq "0000:00:00 00:00:00"))'
```
