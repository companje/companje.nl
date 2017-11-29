---
title: wget
---

==wildcards==
  wget http://site.com/c={a..z}
  wget http://site.com/c={3000..4000}

==don't download if file exists==
  wget -nc   # or --no-clobber: skip downloads that would download to existing files.

==download url's from file==
```bash
wget -i file.txt
```

==download all files from ftp folder==
```bash
wget -i ftp://domain.com/folder/*
```

==recursive rip a page or site==
```bash
wget -r http://site.url
```

==basic auth==
you can just supply the username and password in the URL like this:
```bash
wget http://user:password@domain.com
```
