---
title: CGI
---

# file upload
```bash
#!/bin/sh

echo "Content-Type: text/plain"
echo ""

read boundary
read disposition
read ctype
read junk
size=$(( ${#boundary} + 5 ))
head -c -$size > "/tmp/upload.tmp"

echo "done"
```
<https://ncrmnt.org/2013/04/18/someblack-magic-bash-cgi-and-file-uploads/>