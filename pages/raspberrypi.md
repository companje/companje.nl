---
title: Raspbery Pi
---
See Also: [linux](/linux)

# stream cam
```python
from http import server
import io, threading, socketserver
from picamera2 import Picamera2
from picamera2.encoders import MJPEGEncoder, Quality
from picamera2.outputs import FileOutput

PAGE = b"""<html><body><img src="/stream.mjpg"></body></html>"""

class StreamingOutput(io.BufferedIOBase):
    def __init__(self):
        self.frame = None
        self.condition = threading.Condition()
    def write(self, buf):
        with self.condition:
            self.frame = buf
            self.condition.notify_all()

class StreamingHandler(server.BaseHTTPRequestHandler):
    def do_GET(self):
        if self.path == '/':
            content = PAGE
            self.send_response(200)
            self.send_header('Content-Type', 'text/html')
            self.send_header('Content-Length', len(content))
            self.end_headers()
            self.wfile.write(content)
        elif self.path == '/stream.mjpg':
            self.send_response(200)
            self.send_header('Age', 0)
            self.send_header('Cache-Control', 'no-cache, private')
            self.send_header('Pragma', 'no-cache')
            self.send_header('Content-Type', 'multipart/x-mixed-replace; boundary=FRAME')
            self.end_headers()
            try:
                while True:
                    with output.condition:
                        output.condition.wait()
                        frame = output.frame
                    self.wfile.write(b'--FRAME\r\n')
                    self.send_header('Content-Type', 'image/jpeg')
                    self.send_header('Content-Length', len(frame))
                    self.end_headers()
                    self.wfile.write(frame)
                    self.wfile.write(b'\r\n')
            except Exception:
                pass
        else:
            self.send_error(404)

class StreamingServer(socketserver.ThreadingMixIn, server.HTTPServer):
    allow_reuse_address = True
    daemon_threads = True

picam2 = Picamera2()
config = picam2.create_video_configuration(main={"size": (320, 320)})

picam2.configure(config)
output = StreamingOutput()

try:
    picam2.start_recording(MJPEGEncoder(), FileOutput(output), quality=Quality.VERY_HIGH)
    address = ('0.0.0.0', 8000)
    server = StreamingServer(address, StreamingHandler)
    server.serve_forever()
except KeyboardInterrupt:
    pass
finally:
    picam2.stop_recording()
    picam2.close()
```

# fixed IP for wired connection
```bash
sudo nmcli con mod "Wired connection 1" ipv4.addresses 192.168.42.42/24 ipv4.gateway 192.168.42.1 ipv4.dns "1.1.1.1 8.8.8.8" ipv4.method manual
nmcli connection show
sudo nmcli con up "Wired connection 1"
```

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
