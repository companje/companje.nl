---
title: dd
---

# status
See status with Ctrl+T

# read 1000 bytes from file
  dd count=1000 bs=1 if=/Volumes/tijdmachine/zaagsel.rgb > zaagsel-1000bytes.rgb

# difference between /dev/rdisk and /dev/disk
Since /dev/rdisk# is a character device, anything written to it will be transmitted one character at a time in an unbuffered single stream of bytes. Using /dev/disk# instead will treat the node as a block device, using fixed-size blocks and also buffering the data you are sending, which allows for random access. Since we’re not interested in random access, but rather a straight 1:1 copy of an image file (eg. sequential access), using /dev/rdisk# won’t cause any downsides. It will only make it a lot faster.