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

# uploading
the uploaded file is stored on the SD card as 'cache.gc'
```js
function upload() {
  var blob = new Blob([$("textarea").val()]);
  var reader = new FileReader();

  reader.onload = function(event){   // this function is triggered once a call to readAsDataURL returns
      var fd = new FormData();
      fd.append('fname', 'doodle.g');
      fd.append('data', event.target.result);
      $.ajax({
          type: 'POST',
          url: '/upload',
          data: fd,
          processData: false,
          contentType: false
      }).done(function(data) {
          console.log(data);
      });
  };      
  reader.readAsDataURL(blob); // trigger the read from the reader...

}

function get(cmd) {
 $.get(cmd,function(successData) { console.log(cmd+": "+successData)} ); 
}

function start() {
  get("/set?code=M565");
}

function home() {
  get("/set?code=G28");
}

function stop() {
  get("/set?cmd={P:X}");
}

function status() {
  get("/inquiry");
}
```

# start gcode for the demo print 'cat.gcode' on the SD card
```gcode
M109 S195.000000
;Sliced at: Wed 15-06-2016 08:14:50
;Basic settings: Layer height: 0.2 Walls: 1.2 Fill: 20
;Print time: 2 hours 29 minutes
;Filament used: 7.45m 22.0g
;Filament cost: None
;M190 S0 ;Uncomment to add your own bed temperature line
;M109 S195 ;Uncomment to add your own temperature line
G21        ;metric values
G90        ;absolute positioning
M82        ;set extruder to absolute mode
M107       ;start with the fan off
G28 X0 Y0  ;move X/Y to min endstops
G28 Z0     ;move Z to min endstops
G1 Z15.0 F6000 ;move the platform down 15mm
G92 E0                  ;zero the extruded length
G1 F200 E3              ;extrude 3mm of feed stock
G92 E0                  ;zero the extruded length again
G1 F6000
;Put printing message on LCD screen
M117 Printing...

;Layer count: 299
;LAYER:-2
;RAFT
G0 F6000 X43.681 Y44.418 Z0.300
;TYPE:SUPPORT
G1 F1200 X44.482 Y43.662 E0.13738
G1 X44.847 Y43.323 E0.19951
G1 X45.818 Y42.600 E0.35050
G1 X46.870 Y41.930 E0.50606
;...
```

# end gcode for the demo print 'cat.gcode' on the SD card
```gcode
;...
M107
G1 F6000 E7440.46357
G0 X54.969 Y63.344 Z64.948
;End GCode
M104 S0                     ;extruder heater off
M140 S0                     ;heated bed heater off (if you have it)
G91                                    ;relative positioning
G1 E-1 F300                            ;retract the filament a bit before lifting the nozzle, to release some of the pressure
G1 Z+0.5 E-5 X-20 Y-20 F6000 ;move Z up a bit and retract filament even more
G28 X0 Y0                              ;move X/Y to min endstops, so the head is out of the way
M84                         ;steppers off
G90                         ;absolute positioning
;CURA_PROFILE_STRING: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
```