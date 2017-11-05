# cd /Users/rick/Documents/Websites/companje.github.io/tmp/companje.github.io

# SRC=attic-copy
# DST=tmp

a=0

for f in *.md; do

  a=$(($a+1))

  base=$(basename "${f}")
  
  # timestamp=`cut -d "." -f 2 <<< $base`
  # title=`cut -d "." -f 1 <<< $base`
  title=$base

  datestr=`date -r $base` #$timestamp '+%Y-%m-%d %H:%M:%S'`

  echo "$title - $datestr"

if [ "$a" -gt 10 ]
 then
   break  # Skip entire rest of loop.
 fi


  # echo `date -r
done

### NOTE: I don't want to do 600+ commits after renaming to .md and adding Front Matter..

# git commit --allow-empty --date="Sat Nov 14 14:00 2015 +0100" -m '2 Dec commit'