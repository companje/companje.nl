# connect with telnet
```
telnet 192.168.0.100 4352
```

# power status opvragen
```
%1POWR ?
```
* 0 = Standby
* 1 = Power on
* 2 = Cooling down
* 3 = Warming up

# resolutie opvragen
```
%2IRES
```
returns: `%2IRES=1920 x 1200`

# on/off using netcat
```
printf '%s' '%1POWR 0\r' | nc -w 1 192.168.0.100 4352
printf '%s' '%1POWR 1\r' | nc -w 1 192.168.0.100 4352
```

# document containing info
* https://www.optoma.nl/uploads/manuals/W415-M-nl.pdf
