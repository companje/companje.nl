---
title: Malyan m200 / Monoprice iiip v2 3D printer
---

# links
* [MP Select Mini / ProFab Mini / Malyan M200 Wiki](https://www.mpselectmini.com/wifi/start)

# setup WiFi
The Maylan m200 has Smart Config. This means that it can receive WiFi credentials 'over the air' (texas instruments cc3000?). On iOS you can install 'MP 3D Printer WiFi Connect'. You need to select the 'Move' menu on the printer and hold the push button down for a couple of seconds to get into Smart Config mode. Then the app can send the WiFi credentials. Then the printer will display it's IP address on its screen.

# Web UI
http://192.168.1.74

# Communication
<https://www.mpselectmini.com/communications>

## telnet / netcat
you can use telnet or netcat on port 23 to get a direct TCP connection to the printer.
```bash
nc 192.168.1.74 23
# > G28
# < ok N0 P15 B15
```

## get status
<http://192.168.1.74/inquiry>

## REST API for single commands
<https://www.mpselectmini.com/sample_webui#upload_custom_web_ui>

home (G28): <http://192.168.1.74/set?code=G28>

start print, cancel print, emergency stop
```js
function start_p() {
  $.ajax({
    url: "set?code=M565",
    cache: false
  }).done(function(html) {});
}

function cancel_p() {
  $.ajax({
    url: "set?cmd={P:X}",
    cache: false
  }).done(function(html) {});
}

$('#eStop').click(function() {
    $.ajax({
        url: "set?code=M112\nM999",
        cache: false
    }).done(function(html) {
        $('#gCodeLog').append("<br>M112; Emergency Stop!");
        gCodeLog.scrollTop = gCodeLog.scrollHeight;
        alert('Emergency Stop Sent! You will have to cycle power on the printer to get communications back up.');
    });

});

```

## WebSocket
you can communicate using a websocket:
<script>
var ws = new WebSocket('ws://192.168.1.74:81');
ws.onopen = function () {
  ws.send("G28");
}
</script>

# Machine / firmware info: M115
```
NAME. Malyan	VER: 3.5	MODEL: M200	HW: HH02
BUILD: May 18 2017 20:24:25
```

