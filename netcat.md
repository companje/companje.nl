---
title: netcat
---

==upload file with curl to netcat server==
  nc -l localhost 8000
  curl -F "text=default" -F "file1=@a.html" -F "file1=@a.txt" localhost:8000


==to receive data from Globe4D driver==
<code>
nc -lu -p 12345
</code>

==netcat for windows==
part of nmap project (nmap.org).
Same syntax: `ncat -lu 192.168.2.123 8888`


==read line from netcat==
<code bash>
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
</code>

==tips==
* http://www.homecomputerlab.com/netcat

==quit netcat client after receiving certain string==
  nc localhost 9000 | grep -q "Hoi"
(somehow the word 'Hoi' needs to be followed by two linebreaks)

in a while loop:
<code>
while :
do
  echo listening...
  nc localhost 9000 | grep -q "Hoi"
  echo Bel AAN
  sleep 1
  echo Bel UIT
done
</code>

==listen to port==
<code>
nc -lu -p 7777
</code>

<code>
nc -lu 192.168.2.123 8888
</code>

==send UDP==
<code>
echo "hello" > /dev/udp/10.87.24.174/8888
</code>
<code>
ls > /dev/udp/10.87.24.174/8888
</code>