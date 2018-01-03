---
title: Malyan M200 3D printer
---
aka Monoprice Select Mini V2, MP Select Mini,  ProFab Mini, PrimaCreator P120

# Documentation
There's a manual on the SD card called `manual.pdf`

# links
* <https://www.mpselectmini.com/wifi/start>

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

# Machine / firmware info and config
(the display shows: V35.115.2) 
V35 = Motion Controller Version
115.2 = UI/LCD Controller Version


## M115:
```
NAME. Malyan	VER: 3.5	MODEL: M200	HW: HH02
BUILD: May 18 2017 20:24:25
```
## M503
```
echo:Steps per unit:
echo:  M92 X93.00 Y93.00 Z1097.50 E97.00
echo:Maximum feedrates (mm/s):
echo:  M203 X150.00 Y150.00 Z1.50 E50.00
echo:Maximum Acceleration (mm/s2):
echo:  M201 X800 Y800 Z20 E10000
echo:Accelerations: P=printing, R=retract and T=travel
echo:  M204 P3000.00 R3000.00 T3000.00
echo:Advanced variables:
S=Min feedrate (mm/s),
T=Min travel feedrate (mm/s),
B=minimum segment time (ms),
X=maximum XY jerk (mm/s),
Z=maximum Z jerk (mm/s),
E=maximum E echo:  M205 S0.00 T0.00 B20000 X20.00 Z0.40 E5.00
echo:Home offset (mm):
echo:  M206 X0.00 Y0.00 Z0.00
echo:Invert axis: M562 XYZE
XYZABCD-+-+-+-
echo:PID settings:
echo:  M301 P20.00 I0.02 D250.00 C100.00 L20
echo:  M304 P10.00 I0.02 D305.40
echo:Filament settings: Disabled
echo:  M200 D1.75
echo:  M200 D0
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
;CURA_PROFILE_STRING:eNrtWltv2zY.....==
```

# Malyan's wiki
http://www.wiki.malyansys.com/doku.php

# Malyan's firmware sourcecode on github
https://github.com/MalyanSystem

# Malyan on Alibaba
http://malyansys.en.alibaba.com/

# optimal layer heights
<https://www.mpselectmini.com/optimal_layer>
```
Layer Height (mm)
0.04375 (results may vary)*
0.0875
0.13125
0.175
0.21875
0.2625
0.30625
```

# Available Commands
<https://www.mpselectmini.com/communications>
```
P:X Cancel print
P:H Homing
P:P Pause print
P:R Resume print
P:M Print cache.gc

C T0000 Set T0 temperature
C P000  Set hotbed temperature

e:e Return printing status
e:M Return mac address

J:X Moves the X-Axis
J:Y Moves the Y-Axis
J:Z Moves the Z-Axis
S:I List files on the microSD card
V   Displays firmware versions on LCD
W   Deletes WiFi SSID and password
```

# Download from remote server
```
M564 url.gcode   # download file from server
M565 url.gcode   # download and print
```





