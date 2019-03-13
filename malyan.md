---
title: Malyan M200 3D printer
---
aka Monoprice Select Mini V2, MP Select Mini, ProFab Mini, PrimaCreator P120

# Documentation
There's a manual on the SD card called `manual.pdf`

# links
* <https://www.mpselectmini.com/wifi/start>

# setup WiFi
The Maylan m200 has Smart Config. This means that it can receive WiFi credentials 'over the air' (<del>texas instruments cc3000</del>EspTouch). On iOS you can install 'MP 3D Printer WiFi Connect'. You need to select the 'Move' menu on the printer and hold the push button down for a couple of seconds to get into Smart Config mode. Then the app can send the WiFi credentials. Then the printer will display it's IP address on its screen.

"The ESP8266 doesn't support 5 GHz. The user will have to make sure their phone is connected to a 2.4 GHz AP so ESP Touch can sniff the packets."

# Web UI
* http://IPaddress
* https://github.com/nokemono42/MP-Select-Mini-Web
 

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
or:
```
(M109 S195\nG21\nG90\nM82\nM107\nG28 X0 Y0\nG28 Z0\nG1 Z15.0 F6000\nG92 E0\nG1 F200 E3\nG92 E0\nG1 F6000\n)
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
;CURA_PROFILE_STRING:eNrtWltv2zY.....
```

# Malyan's wiki
<http://www.wiki.malyansys.com/doku.php>

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

C:T0000 Set T0 temperature
C:P000  Set hotbed temperature

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
M564 IPADDRESS/file.gcode   # download file from server
M565 IPADDRESS/file.gcode   # download and print
not working test: http://192.168.1.70/set?code=M565%20http://149.210.157.74/small.g
not working test: http://192.168.1.70/set?code=M565%20/149.210.157.74/small.g
I received a new firmware version (v158.2) from Malyan where this problem is solved :
Now this works: `http://192.168.1.70/set?code=M564%20149.210.157.74/vaasje.g`
```

# Testing Doodle3D Sketch on Malyan M200
rsync: 
```bash
rsync -av /Users/rick/Documents/Doodle3D/doodle3d-client/www/ doodle3d.com:/domains/doodle3d.com/draw/
```

.htaccess:
```apache
Header set Access-Control-Allow-Origin "*"
Header set Access-Control-Allow-Headers "Origin, X-Requested-With, Content-Type, Accept"
```

# firmware
<http://wiki.malyansys.com/doku.php>
Update method:

Motion conroller
Copy update.bin to clean formatted microSD card. Create empty file named “fcupdate.flg”. Insert card and power on printer to update.

# Version
not tried yet
http://192.168.1.108/set?cmd={V:} 

# SD card
* The SDCard that came with the printer is formatted FAT16 and has about 128MB.
* I tried with a Transcend Premium 300x 16GB card formatted FAT32. The display shows: "No files"
* I tried to format it exFAT but same result.
* I tried to resize SD card to 4GB FAT using Windows DISKPART: http://www.instructables.com/id/Format-USB-Flash-Drive-to-FATFAT16-not-FAT32/. Same result.
* according to Monoprice website: "Note:  If you choose to purchase an SD card, please make sure that it is not labeled HC (High Capacity) as it may not be compatible with the printer. This means that the card must be smaller than 4GB in size.". My 16GB card has the HC label. May be this is a problem even when it's resized to a smaller capacity.
* next attempt: Use Disk Utility to create a diskimage from the original SD card to another (2GB) SD-card. It downsizes this card. They should be identical now but the printer still reports 'No files'.
* related [reddit thread](https://www.reddit.com/r/MPSelectMiniOwners/comments/7kohmz/this_might_have_been_asked_not_in_sticky_post/)
* backup sd card:
```
sudo dd if=/dev/disk3 of=~/printer.img
sudo dd if=~/printer.img of=/dev/disk3
```

# Monoprice Select Mini 3D Printer KB
<https://docs.google.com/document/d/1HJaLIcUD4oiIUYu6In7Bxf7WxAOiT3n48RvOe5pvSHk/edit#heading=h.55rkuyw7uqlw>

# reverse engineering UI board
<https://hackaday.com/2017/06/20/reverse-engineering-the-monoprice-printer/#more-262422>
- UART 500kbps
- esptool.py to backup (4MB flash) and flash firmware. press knob on boot. (not tried yet)
- uses PlatformIO as Arduino IDE
- (todo: <https://hackaday.io/project/12696-monoprice-select-mini-electro-mechanical-upgrades>)
- (notes: <https://gist.github.com/metaquanta/6103fb77116d931e9e4b527088f49ad9>)
- Robin's firmware install via PlatformIO <https://github.com/robin7331/malyan-m200-display-firmware> (This is a WiP. It's not yet ready to upload to your printer!)
- Esptool.py: https://github.com/espressif/esptool

# ESP touch / Smart Config
* [Cordova Plugin for EspTouch](https://github.com/xumingxin7398/cordovaEsptouch)

# traffic
(8 megabytes) / (500 kbps) = 2.13333333 minutes

(8 MB) / (20 (KB / s)) = 6.66666667 minutes


# forget wifi
```
http://10.0.0.140/set?cmd={W:}
```

# auto print
'auto00.g' should start when holding knob on mainmenu. Not tested yet.

# rename cache.gc to something else
```
M566 newname.gc
```

# display
* 3.2" 480x320
* might be HX8357 or compatible
* <https://github.com/Bodmer/TFT_HX8357>
* 16 bits color ([RGB565](rgb))

# websocket test
```html
<form id="frmConnect" action="#" onsubmit="connect(txtIP.value)">
  <input type="text" placeholder="ip address" id="txtIP" value="10.0.0.109"/>
  <input type="submit" id="btnConnect" value="Connect"/>
</form>

<form id="frmSend" action="#" onsubmit="send(txtCmd.value)" hidden>
  <input type="text" placeholder="command" id="txtCmd"/>
  <input type="submit" value="Send"/>
</form>

<textarea cols="60" rows="20" id="txtLog"></textarea>

<script>

var ws;

function connect(ip) {
  ws = new WebSocket("ws://"+ip+":81");
  
  ws.onmessage = function (event) {
    txtLog.value+="< " + event.data;
  }
  
  ws.onopen = function () {
    frmConnect.hidden = true;
    frmSend.hidden = false;
    txtLog.value += "Connected to " + ip + "\n";
  }
}

function send(cmd) {
  if (ws) ws.send(cmd);
  txtLog.value += "> " + cmd + "\n";
  txtCmd.select();
}

</script>
```

#  Access-Control-Allow-Origin - test script
```html
<!DOCTYPE html>
<html>
<head>
<title>Malyan M200 CORS problem</title>
</head>
<body>
  <input type="text" id="ip" placeholder="ip address">
  <button onclick="inquiry(ip.value)">Inquiry</button>
  
  <script>
  function inquiry(ip) {
    var xhr = new XMLHttpRequest();
    xhr.onload = function() { 
      console.log(xhr.response)
    };
    xhr.open('GET', 'http://' + ip + '/inquiry');
    xhr.send();
  };
  </script>

</body>
</html>
```

# raw communication codes
commands and responses are send both ways at 500kbps (gcode with 8 databits, control commands with 7 databits?)
gcode data is uploaded with 8 databits.
```
{P:X} cancel
{C:T0000} set nozzle temp
{C:P000} set bed temp
{S:I} inquiry? / sd init?
{S:L} sd list?

{B:0} ?


{FILE:.gcode}
{FILE:test.gcode}
{FILE:cache.gc}
{FILE:doodle.gcode}
{FILE:log.txt}
{FILE:ster-min-ster.g}
{FILE:G28}
{FILE:vaas-dun.g}
{FILE:vaas-groot.gcode}
{FILE:MM_support test.gcode}
{DIR:wifi}
{FILE:Doodle3D.gcode}
{SYS:OK}

{T1:000/000} temp nozzle
{TP:017/000} temp bed
{TQ:025P} progress
{TT:000001} ???
```

response when uploading looks like 6bits data:
{h01}{h01}{h01}{h01}{h01}{h01}{h01}{h01}{h01}{h01}{h01}{h01}{h01};{h13}{h19}{h13}:{h1B}{h07}{h1D}0{h12}{h14}{h05}{h04}=

# 'bricked' after updating motion controller:
Fix on OSX (replace # by correct value):
```
diskutil list
diskutil info disk#
sudo diskutil unmount /dev/disk#
sudo newfs_msdos -F 32 -v 3DPRINTER -b 512 /dev/disk#
diskutil mount disk#
diskutil info disk#
```
First my File System was MS-DOS FAT16 and Allocation Block Size was 16384 Bytes
Now it is MS-DOS FAT32 with Allocation Block Size 512 Bytes.
Thanks to MatthewUpp.




