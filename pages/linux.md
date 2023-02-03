# remove files (recursively) ending with ~
```bash
find . -type f -name '*~' -exec rm -f {} +
```

# login as user www-data
```bash
sudo -u www-data bash
```
 
# recursive file and folder permissions rw for user and group
```bash
sudo find . -type d -exec chmod 775 {} +
sudo find . -type f -exec chmod 664 {} +
```

# show file/folder permissions as octal numbers
```bash
stat -c "%a %n" *
```
output:
```
664 footer.php
775 images
775 include
664 index.php
664 info.php
664 logout.php
```

# mount NAS / Netgear ReadyNAS in Linux
* https://technostuff.blogspot.com/2012/06/how-to-mount-disk-used-by-readynas.html

# user/group rights / ownership drupal
https://www.drupal.org/docs/7/install/step-3-create-settingsphp-and-the-files-directory

# see which groups your linux user account belongs to
```bash
groups
#or
id
```

# output stdout and stderr to same output file
```bash
normalizer --normalize --replace --force "$1" > normalizer.log 2>&1
```

# find exec copy
```bash
find . -name "*text.xml" -exec cp {} DEST_FOLDER \;
```

# Terminate all Excel instances
```bash
killall -9 "Microsoft Excel" 2>/dev/null
```

# read / input variable (with default value)
```bash
default_top_code=Test-1965.01
read -p "Wat is de code van de toegang [$default_top_code]: " top_code
top_code=${name:-$default_top_code}
```

# split
Split a text file into files with 50 lines each: 
```bash
split --lines=50 foo.txt
```

# exit / terminate script after error / when any of the following command fails
```bash
set -e
```
somehow this did not work (anymore?) so here is an alternative:
```bash
./script.py || exit
echo this won't be printed if script.py gives an error
```

# remove extension
```bash
base="${filename%.*}"
```

# show hex code(s) of character
```bash
echo -n "é" | od -A n -t x1
```

# find all words in files containing one or more � characters
```bash
grep -Gonr [0-9a-zA-Z�]*�[0-9a-zA-Z�]* | tr \: \;
```

# find folders recursively ending with '.build' and delete them
```bash
find 2012 -type d -name "*.build" -exec rm -rf {} +
```

# get number of files per extension recursively
```bash
find . -type f | rev | cut -d. -f1 | rev  | tr '[:upper:]' '[:lower:]' | sort | uniq -c | sort -rn
```

# get size of all PNG's or other filetype recursively
```bash
find . -name '*.png' -exec du -sch {} + | tail -1
```

# open Github Desktop from command line with current folder
(add this to .zshrc)
```bash
alias github="open . -a \"Github Desktop\""
alias code="open . -a \"Visual Studio Code\""
alias subl="open . -a \"Sublime Text\""
```

# reload .zshrc
```bash
. ~/.zshrc
```

# find large folders
```bash
du -a | sort -n -r
```

# combine images vertically and create filename with 0's padding
```bash
folder="folder/"

for number in `seq 1 1 370`;
do
  temp_num="00000$number"
  padded="${temp_num:(-5)}"

  input1=$number-1.tif
  input2=$number.tif
  result=NL-UtHUA_0046_00_00727_00J_$padded.jpg

  convert "$folder$input1" "$folder$input2" -clone 0 -delete 0 -gravity North -append $result
done
```

# default value for parameter
```bash
var=${2:-6}    # $2 parameter gets defaulted to 6
```

# set cwd to the script's folder
```bash
cd `dirname $0`
```

# remove .DS_Store files recursively
```bash
find . -type f -name .DS_Store -delete
```

# nested while loop
```bash
while read DB_NAME; do  
  while read ID; do
    echo $DB_NAME/$ID
  done < sketches_dbs/$DB_NAME
done < sketches-dbs.txt
```

# disk use sort by size
```bash
du -hs * | sort -h
```

# remove empty folders
```bash
find DIR -type d -empty -delete
```

# curl & grep
```bash
i=1320000
z=1355000

while [ $i -le $z ]
do
  curl -s ".......index.php?rec=$i" | grep Volledige | cut -c 59- | sed -e "s/ target=\"_blank\">/,\"/" -e "s/<\/a><br>/\",$i/" 

  ((i++))
done
```

# use expr, for, seq and wget
```bash
xxcols=20
numRecords=1957
orgRecordsPerPage=4
newRecordsPerPage=$(expr $orgRecordsPerPage '*' $xxcols)
out=all3.html

rm $out
for i in `seq 0 $newRecordsPerPage $numRecords`; 
do
  echo -ne "\\rDownloading $(expr $i '*' 100 '/' $numRecords)%"
  wget --quiet -O - "XXXX xxstart=$i&xxcols=$xxcols" >> $out
done
echo \ndone
```

# prefix and postfix to each line
```bash
ls | sed 's/.*/hoi&?doei/'
#hoiids-unique.txt?doei
#hoiids.txt?doei
```

# remove whitespace
```bash
tr -d ' ' < ids-unique.txt
```

# for loop sequence with step
```bash
for i in `seq 0 1000 13000`; do echo $i; done
# 0
# 1000
# 2000
# ...
# 11000
# 12000
# 13000
```

# recursively add file extension to all files
not tried yet:
```bash
find . -type f -exec mv '{}' '{}'.html \;
```

# loop over filenames from file and execute other shell script
```bash
COUNTER=0

while read p; do
  echo $COUNTER
  find . -name "$p" -exec ./hoi '{}' \;
  COUNTER=$((COUNTER+1))
    
done < not-imported.txt
```

in `hoi`:
```bash
echo $1
...
```

# Check return value
```bash
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
```

# script install itself as executable
```bash
ln -s `realpath $0` ~/bin/flash > 2&>/dev/null #installs this script as executable 'flash'
```

# logging
```bash
$ logger test
$ logread
…
Mon Jun 12 09:08:51 2017 user.notice root: test
```

# Check parameters
```bash
if [[ $# -eq 0 ]] ; then
    echo 'Usage: ./toMovie.sh FOLDER'
    exit 0
fi

```

# get foldername from folderpath
```bash
folderpath=$1
foldername=${folderpath##*/}
```

# colors in ~/.profile
```bash
git_branch () { git branch 2> /dev/null | sed -e '/^[^*]/d' -e 's/* \(.*\)/\1/'; }
HOST='[01;32m\]';
LOCATION='`pwd | sed "s#\(/[^/]\{1,\}/[^/]\{1,\}/[^/]\{1,\}/\).*\(/[^/]\{1,\}/[^/]\{1,\}\)/\{0,1\}#\1_\2#g"`'
BRANCH=' [00;33m\]$(git_branch)\[[00m\]
\$ '
PS1=$HOST$LOCATION$BRANCH
PS2='\[[01;36m\]>'

ls --color=al > /dev/null 2>&1 && alias ls='ls -F --color=al' || alias ls='ls -G'

TERM=xterm-color
alias d='ls -la --color'
cd /domains/doodle3d.com/DEFAULT
```

# log file processing
```bash
cut -f4,10 EnecsysLogfile.txt | sort -r | rev | uniq -f 1 | rev | cut -f 2 | egrep [0-9.] | xargs  | sed -e 's/\ /+/g' | bc
```

En hier een stukje log van Duinsels perlscript:
```
date    time    ZigbeeString    deviceID        DCpower Efficiency      ACpower DCcurrent       DCVolt  LifetimeProduction      Time1   Time2   ACvolt  ACfreq  Temperature     HexZigbee
04-01-2015      09:38:42        WZ=PfI1dwCaxjQAAO5EIQEAAEIGClPyGJAGAJrGNEg=03,S=2000024125
04-01-2015      09:39:17        WS=gfaPBgCaxjQAAO6KIQEAAABsFDADiAABPAEpA6syAOYUA3wBkwAACF       110098049       297     0.939   278.883 7.9     37.59   403.892 238     108     230     50      20      81F68F06009AC6340000EE8A21010000006C1430038800013C012903AB3200E614037C01930000085
04-01-2015      09:39:33        WS=dvaPBgCaxjQAAO6pIQEAAABwFDADiAABGQEKA7IyAOYTAwYBWgAAED       110098038       266     0.946   251.636 7.025   37.86   346.774 238     112     230     50      19      76F68F06009AC6340000EEA921010000007014300388000119010A03B23200E6130306015A0000103
04-01-2015      09:39:35        WS=8hiQBgCaxjQAAO6wIQEAAAB0FDADiAABVwFDA60yAOUUAwsBxgAAC1       110106866       323     0.941   303.943 8.575   37.67   454.779 238     116     229     50      20      F2189006009AC6340000EEB021010000007414300388000157014303AD3200E514030B01C600000B5
```

# move files by year
```bash
#!/usr/bin/env bash
BASE_DIR=/Users/rick/Pictures/Diversen2/2004

find "$BASE_DIR" -type f -name "*.jpg" | 
while IFS= read -r file; do

  year=$(stat -f "%Sm" "$file" | rev | cut -c -4 | rev)

  [[ ! -d "$BASE_DIR/$year/" ]] && mkdir -p "$BASE_DIR/$year/"; 

  echo $year: $file
  mv "$file" "$BASE_DIR/$year/"

done
```


# recursive remove empty folders
  find . -type d -empty -delete

# update Date Modified based on EXIF data
```bash
#!/bin/bash

while [[ "$1" != "" ]] ; do
  echo -n "Processing '$1'..."
  DATE=`identify -verbose "$1" | grep "exif:DateTimeOriginal:" | cut -c 28- | tr -d : | tr -d ' ' | cut -c -12`
  echo $DATE
  touch -t $DATE "$1"
  shift
done
```

# move jpg script based on exif data
using ''imagemagick''.
```bash
#!/bin/bash

for i in *.JPG; do
    echo -n "Processing '$i'..."

    YEAR=`identify -verbose "$i" | grep "exif:DateTime:" | cut -c 20-23`
    
    if [[ "$YEAR" == "" ]]; then
      echo "NO_YEAR"
    else
      echo "YEAR=$YEAR"
      mkdir -p $YEAR
      mv "$i" "$YEAR"
    fi
    
done
```

# list files recursive with full path
```bash
  find .
```

# manipulating strings
```bash
  find.txt | sort | cut -f1 -d"." | uniq | rev | sort > sorted.txt
```

# read line from netcat
```bash
while:
do
  echo listening...

  nc 10.0.0.134 8081 | while read line
  do

    if [ ! -f /root/bel.txt ]
    then
      echo $line > /root/bel.txt
    fi

    grep -q bel.txt -e $line
    if [ $? -eq 0 ]; then
      echo "gevonden: $line"
    fi

    #echo test:$line
  done
done
```

# infinite while
```bash
while :
do
  echo tick
  sleep 1
done
```

# tee
Use tee command to redirect the content to file:
  telnet google.com 80 | tee outfile
Then grep the file

# notes from BEAA talk by Johan at 26 june - Bash
```bash
all chmod a+r  
user chmod u+w
group chmod g-x
owner/ chmod o-w
chmod 755
```

# shell expansion
```bash
{a..z}{0..9} = a1 a2 a3 … z7 z8 z9
/pad/naar/{file1,file2} == /pad/naar/file{1,2}
echo ~  toont home dir
echo ~- toont vorige
echo ~+ volgende

$(command) == `command`

echo $((2+2))
A=test; echo A; echoe $(A:=foo}
echo *
echo Do*
echo {a..z}

cp bestand{,.bak}

sterretje kan ook midden in pad
sudo ls /home/*/Maildir/new
sudo ls /home/user{1,2,3}/Maildir/new
mkdir {2012..2014}-{1..12} && ls
ls -la `whereis ls`
```

# shell expansion vindt plaats voor het commando wordt uitgevoerd
```bash
mkdir tmp && cd tmp
>cp
>foobar
* test
ls

echo ls ${foo:=*}
echo “ls ${foo:=*}”
echo ‘ls ${foo:=*}’  (wordt niks mee gedaan, komt koud als string naar de terminal

|| is exclusieve OR. 1 van de 2 moet waar zijn andere mag niet waar zijn

[ is eigenlijk een alias naar test
man test voor hulp over de if control structures.

cat nonexistingfile && echo 123
cat nonexistingfile || echo 123
```

# history
```bash
!123 voert bepaald history commando uit
!-2 voert twee-na-laatste commando uit
!begintmet
!?bevat
control+R
fc (fix command) opent je laatste commando in vi en voert ‘m uit na :q.
sudo !! voert laatste commando uit met sudo ervoor

lsof -p $$ (lijst van alle open bestanden maar alleen die door jouw PID geopend zijn)

ls &> file
```

# inhoud van een file gebruiken als std input
```bash
mysql database <<<“SELECT * FROM TABLE”

mysql database < <(sql_generator)
```
je koppelt hier de std out van het ene commando aan de std in van het andere commando. Dit is hetzelfde als pipe

LPI 1 boeken zijn heel fijn om bash te leren

rev draait in een bestand voor elke regel alle karakters om

# ncal
```bash
cal
ncal
ncal -wy
```

# bash-completion
for advanced code completion (also reads from makefiles)
```bashsudo port install bash-completion```
add this to ~/.profile
```bash
export BASH_COMPLETION=/opt/local/etc/profile.d/bash_completion.sh
if [ -f $BASH_COMPLETION ]; then
        . $BASH_COMPLETION
fi
shopt -s no_empty_cmd_completion
 
export GIT_PS1_SHOWDIRTYSTATE=true
export GIT_PS1_SHOWSTASHSTATE=true
export GIT_PS1_SHOWUNTRACKEDFILES=true
source /opt/local/share/doc/git-core/contrib/completion/git-completion.bash
source /opt/local/share/doc/git-core/contrib/completion/git-prompt.sh
```

#  Checking errorcode / errorlevel / returnvalue in a shell script 
See [[linux]]

```bash
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
```
