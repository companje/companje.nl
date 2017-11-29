---
title: Checking errorcode / errorlevel / returnvalue in a shell script
---
See [[linux]]

```
#!/bin/sh

export TOPDIR=`pwd`/..

echo Compiling...

make

if [ $? -ne 0 ]
then
  echo Error compiling...
  exit
fi

./executable 1>>log.txt 2>&1
</code>
