====== Davinci 3D printer ======
* https://github.com/jasongao/DaVinci1.0/blob/master/print-to-davinci.py
* http://hackaday.com/2014/04/17/using-non-crappy-software-with-the-da-vinci-printer/
* https://www.youtube.com/watch?v=V1UhqT5iOXk
* http://3dprintingindustry.com/2014/02/05/unboxing-printing-da-vinci-1-0-3d-printer/
* https://github.com/m-look/daVinciF10/blob/master/Post-XYZ.sh
* https://github.com/mwm/xyzify


==threedub==
* https://gitlab.com/anthem/py-threedub/blob/master/threedub/davinci.py
* instructions: http://www.thingiverse.com/thing:1915076
* `threedub -e /dev/tty.usbmodem1411 --status`


==decrypt 3w==
"Past release of XYZware used to generate *.3W file which is actually just a base64 encoded gcode file. However, recent XYZware hardened the format by AES-encrypting ZIP-ed gcode, instead of just encoding in base64." https://github.com/tai/decrypt-xyz3w
issue: https://github.com/tai/decrypt-xyz3w/issues/1

==Printing to directly to davinci==
https://github.com/jasongao/DaVinci1.0/blob/master/print-to-davinci.py

==calculate checksum:==
[[http://www.soliforum.com/topic/6279/xyzprinting-da-vinci-10-hacking/page/4/|DanNsk says]]: BTW - just realized checksum is just a sum (only gcode part, after replacing '\n' => '\r\n')
that gives 4 bytes - 00 00 b9 2e for minimal.gcode exact as hardcoded in print-to-davinci.py
<code java>
public static int checksum(byte[] arr, int start, int len) {
  int num = 0;
  for (int index = start; index < start + len; ++index)
    num = (int)arr[index] + num;
  return num;
}
</code>

==upload from Simplify3D==
<code>
SENT: XYZv3/upload=temp.gcode,26272
READ: ok
SENT: 8204 bytes
READ: ok
SENT: 8204 bytes
READ: ok
SENT: 8204 bytes
READ: ok
SENT: 1708 bytes
READ: ok
SENT: XYZv3/uploadDidFinish
</code>


=====Serial communication on 115200=====
  > XYZ_@3D:
  < XYZ_@3D:start
  MDU:dvF100A000
  FW_V:1.0.1
  MCH_ID:3F10APEU4TH4AN0085
  PROTOCOL:2

  > XYZ_@3D:6
  < EE1:5a,41,5a0000,344241,120000,115003,210,90,5448,4742,30323237,52

  > XYZ_@3D:5
  < MCHLIFE:305
  MCHEXDUR_LIFE:117
  
  > XYZ_@3D:8
  < WORK_PARSENT:0
  WORK_TIME:0
  EST_TIME:0
  ET0:31
  BT:27
  MCH_STATE:26
  LANG:0

  > XYZ_@3D:4
  < OFFLINE_OK
  
  
=====send 3w experiment based on py-threedub=====
<code python>
import time, serial, sys
import copy
import string
import os
from Crypto.Cipher.AES import AESCipher, MODE_ECB, MODE_CBC
import logging
import struct

log = logging.getLogger(__name__)

UploadCmd = "XYZv3/upload={filename},{size}{option}"
UploadDidFinishCmd = "XYZv3/uploadDidFinish"

ser = serial.Serial(
    port="/dev/tty.usbmodem1411",
    baudrate=115200,
    parity=serial.PARITY_NONE,
    stopbits=serial.STOPBITS_ONE,
    bytesize=serial.EIGHTBITS
)

def write(data):
    ser.write(data)
    ser.flush()

def writeline(data):
    write(data+"\n")

def readline():
    return ser.readline()

def readlines(expect=None):
    buf = ""
    line = None
    while line is None or line:
        line = readline()
        if line:
            buf += line
            if line.strip() == expect:
                break
            elif line.strip() == "E0":
                return buf
    return buf

def wait_for_ok(expect="ok"):
    resp = readlines(expect=expect)
    if not resp or resp.strip() != "ok":
        raise Exception("Expected token not found: {}, got '{}' instead".format(expect,resp.strip()))
    

f = open("flatcube/flatcube-1-layer.3w", 'rb')
data = f.read()
size = os.fstat(f.fileno()).st_size

writeline(UploadCmd.format(filename="temp.gcode", size=size, option=""))
wait_for_ok();

chunks = (len(data)+8191) / 8192
prev = ""
blocksize = 8192
for n in range(0, chunks):
    log.debug("Sending file chunk {}/{}".format(n, chunks))
    chunk = struct.pack(">l", n) + struct.pack(">l", blocksize)
    start = 8192*n
    chunk += data[start:start+8192]
    chunk += "\x00\x00\x00\x00"
    write(chunk)
    wait_for_ok()

write(UploadDidFinishCmd)

print "done"
</code>