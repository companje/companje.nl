---
title: wget
---

## download site recursive
```bash
wget -e robots=off -r -np --page-requisites --convert-links https://SITE
```

## don't follow redirect, keep connection open, tee to file
```bash
wget --max-redirect 0  http://..../{1..1000} 2>&1 | tee file.txt
```
 
## recursive download images
```bash
wget -nd -r -A jpeg,jpg,bmp,gif,png http://www.domain.com
```

## output to stdout
```bash
wget http://connect.doodle3d.com/api/signin.php -O -
```
## output folder / output directory
```bash
wget --directory-prefix=FOLDER URL
```

## wildcards
```bash
wget http://site.com/c={a..z}
wget http://site.com/c={3000..4000}
```

## don't download if file exists
```bash
wget -nc   # or --no-clobber: skip downloads that would download to existing files.
```

## download url's from file
```bash
wget -i file.txt
```

## download all files from ftp folder
```bash
wget -i ftp://domain.com/folder/*
```

## recursive rip a page or site
```bash
wget -r http://site
wget -r --no-parent http://site
```

## basic auth
you can just supply the username and password in the URL like this:
```bash
wget http://user:password@domain.com
```
