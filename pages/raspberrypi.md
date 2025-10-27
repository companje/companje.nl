---
title: Raspbery Pi
---
See Also: [linux](/linux)

# stream pi cam via flask
```python
import cv2, time
from flask import Flask, Response
from picamera2 import Picamera2

picam2 = Picamera2()
cfg = picam2.create_preview_configuration(main={"format":"RGB888","size":(640,480)})
picam2.configure(cfg)
picam2.set_controls({"ExposureTime": 30000, "AnalogueGain": 1.0})
picam2.start()
time.sleep(0.3)

app = Flask(__name__)

def frames():
    while True:
        frame = picam2.capture_array()
        gray = cv2.cvtColor(frame, cv2.COLOR_RGB2GRAY)
        ok, buf = cv2.imencode(".jpg", gray, [int(cv2.IMWRITE_JPEG_QUALITY), 80])
        if not ok:
            continue
        yield b"--frame\r\nContent-Type: image/jpeg\r\n\r\n" + buf.tobytes() + b"\r\n"

@app.route("/video")
def video():
    return Response(frames(), mimetype="multipart/x-mixed-replace; boundary=frame")

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=8000, threaded=True)
```

# pi cam
```python
import cv2, time
import numpy as np
from picamera2 import Picamera2

i=0
picam2 = Picamera2()
cfg = picam2.create_preview_configuration(main={"format":"RGB888","size":(640,480)})
picam2.configure(cfg)
picam2.set_controls({"ExposureTime": 30000, "AnalogueGain": 1.0})
picam2.start()
time.sleep(0.3)

while True:
    frame = picam2.capture_array()
    gray = cv2.cvtColor(frame, cv2.COLOR_RGB2GRAY)
   
    cv2.imshow("gray",gray)
    # cv2.imwrite(f"jpg_new/frame{i}.jpg",gray)
    i+=1

    k = cv2.waitKey(1) & 0xFF
    if k == 27: break
    if k == ord('+'): thr = min(thr+1,255)
    if k == ord('-'): thr = max(thr-1,0)

cv2.destroyAllWindows()
picam2.stop()
```


# hq camera
```python
pipeline = (
    "libcamerasrc ! "
    "video/x-raw,format=RGBx,width=640,height=480,framerate=30/1 ! "
    "videoconvert ! "
    "video/x-raw,format=BGR ! "
    "appsink drop=true max-buffers=1 sync=false"
)
cap = cv2.VideoCapture(pipeline, cv2.CAP_GSTREAMER)
```

# remove IR blocking filter from HQ Camera
* https://www.youtube.com/watch?v=6FHRcZMVTWc

# Raspberry PI pico
"...the Pico can be powered from 5V, but it works at 3.3V, so leaving software and hardware emulation aside for now, you'd need level shifters on almost every pin."

# Raspberry PI in Virtual Box
* hmm.. https://www.youtube.com/watch?v=CeUDAIPKBGQ
* https://www.youtube.com/watch?v=GubtqMjJgDI

# SSH into Model A
https://www.reddit.com/r/raspberry_pi/comments/2oqs2a/how_can_i_ssh_into_the_a_model/
"By default, the A+ has no way to get onto a network. You need to add a Wifi adapter."

# Raspberri pi internal WiFi
please note that the raspberry pi 3 internal wifi does currently not support the wifi channels 12 and 13

# OS distro for full screen webpage
* https://github.com/guysoft/FullPageOS

Voor Globe4D misschien nog 2 interessante Raspberry Pi alternatieven: 
* [[http://www.cnx-software.com/2014/04/21/solidrun-hummingboard-is-a-raspberry-pi-compatible-board-powered-by-freescale-i-mx6/|solidrun-hummingboard]]
* [[http://www.cnx-software.com/2014/04/20/banana-pi-is-a-raspberry-pi-compatible-board-fitted-with-an-allwinner-a20-soc/|banana-pi]]

# Peter's aantekeningen
* http://kladblok.planb.coop/p/raspberrypi

# GPIO
gpio -g mode 17 out/in
gpio -g write/read 17 1

# PWM
gpio -g mode 18 pwm
gpio -g pwm 18 512

# login
pi
raspberry
