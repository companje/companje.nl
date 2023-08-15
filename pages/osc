# send in python
```python
import time
from pythonosc import udp_client as osc

osc = osc.SimpleUDPClient("127.0.0.1", 12000)

while True:
    osc.send_message("/test", 123)
    time.sleep(1)
```

# receive in Processing
```processing
import oscP5.*;

OscP5 osc;

void oscEvent(OscMessage msg) {
  println(msg);
}

void setup() {
  osc = new OscP5(this, 12000);
}

void draw() {
}
```
