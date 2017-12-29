---
title: Malyan m200 / Monoprice iiip v2 3D printer
---

# links
* [MP Select Mini / ProFab Mini / Malyan M200 Wiki](https://www.mpselectmini.com/wifi/start)

# setup WiFi
The Maylan m200 has Smart Config. This means that it can receive WiFi credentials 'over the air' (texas instruments cc3000?). On iOS you can install 'MP 3D Printer WiFi Connect'. You need to select the 'Move' menu on the printer and hold the push button down for a couple of seconds to get into Smart Config mode. Then the app can send the WiFi credentials. Then the printer will display it's IP address on its screen.

# Web UI
http://192.168.1.74

execute:
http://192.168.1.74/set?code=G28

# telnet / netcat
you can use telnet or netcat on port 23 to get a direct TCP connection to the printer.
```bash
nc 192.168.1.74 23
# > G28
# < ok N0 P15 B15
```

# M115
Sending M115 (using netcat) returns:
```
NAME. Malyan	VER: 3.5	MODEL: M200	HW: HH02
BUILD: May 18 2017 20:24:25
```

