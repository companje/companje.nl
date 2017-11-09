---
title: wget
---

==wildcards==
  wget http://site.com/c={a..z}
  wget http://site.com/c={3000..4000}

==don't download if file exists==
  wget -nc   # or --no-clobber: skip downloads that would download to existing files.

==download url's from file==
<code bash>
wget -i file.txt
</code>

==download all files from ftp folder==
<code bash>
wget -i ftp://domain.com/folder/*
</code>

==recursive rip a page or site==
<code bash>
wget -r http://site.url
</code>

==basic auth==
you can just supply the username and password in the URL like this:
<code bash>
wget http://user:password@domain.com
</code>