---
title: rsync
---

#  rsync with remote 
```bash
rsync -av localfolder/ server:/remotefolder/
```

#  clone folder 
```bash
rsync -a src/ dest

```
"A trailing slash on the source [...] avoid[s] creating an additional directory level at the destination."

#  clone folder and exclude git folder 
```bash
rsync -a --progress shop/ shopdev --exclude .git
```
