---
title: Sanyo MBC-550/555
---

# finding the font in TimeBandit
It took me quite some time to find the font in the binary file. I was expecting a similar way of storing the pixels as for the sprites: 32x16 in steps of 4 bytes per cell with 4 cells in a row repeated 4 times. And that for 3 color channels. However, the font is only one channel and it is stored as 16 bits (the lowest 5 bits are 0) per line with 12 lines (the last line is always 0):

![Finding the font in TimeBandit for Sanyo MBC-550/555](https://github.com/user-attachments/assets/063ed157-d692-4100-b3e6-28401727158c)

I used following Python script to convert the EXE to a textfile with just zeros and ones, 800 bytes per line to minimize linebreaks.
```python
input_binary_file = "bandit-without-code.exe"
output_text_file = "bandit-without-code.txt"

with open(input_binary_file, 'rb') as bin_file, \
     open(output_text_file, 'w') as text_file:
  
  binary_string = ''.join(format(byte, '08b') for byte in bin_file.read())

  for i in range(0, len(binary_string), 800):
    print(binary_string[i:i+800], file=text_file)
```
 Then I could use the find function in my texteditor to find parts of the letter in binary. For example 10010011001.
![Screenshot 2024-12-12 at 22 00 00](https://github.com/user-attachments/assets/6ce7f787-2c44-4909-a8f4-a4fd91a4bfeb)

<img align="right" height="250" src="https://github.com/user-attachments/assets/565e48f7-cb6d-4323-bbc1-849c995d076d">
Changing the line length to 16 and replacing the zeros by spaces and the ones by blocks immediately shows the characters in your texteditor.

```python
binary_string = binary_string.replace("0"," ").replace("1","█")
for i in range(0, len(binary_string), 16):
     print(binary_string[i:i+16], file=text_file)
```

![Screenshot 2024-12-13 at 10 14 26](https://github.com/user-attachments/assets/5b4c54c1-b4dc-4d3b-bd76-b8c3cbc68b91)

characters:
```
!"#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ
```
<img style="height: 24px; image-rendering: pixelated" src="https://github.com/user-attachments/assets/760a968d-1476-4940-afa0-03d43684eeb0">

# draw_char_to_vram in Processing (on the grid)
```java
draw_char_to_vram(chrA, 0,0);

void draw_char_to_vram(byte[] bytes, int si_offset, int di_offset) { //this only works 'on the grid' (every 8 pixels) 
  for (int b=0; b<bytes.length*8; b++) { // b=which bit 0..192 //2 bytes per line (12 lines)
    int x = b%16;
    int y = b/16;
    int si = si_offset + b/8;
    int di = di_offset + (y/4)*(4*COLS) + (y%4) + (x/8)*4;
    int dl = 128 >> (x%8);
    memory[R+di] ^= (bytes[si] & dl);  //XOR
    //println("x="+x,"y="+y,"---","di="+di, "dl="+dl);
  }
}
```
# draw char/bitmap anywhere
```java
draw_char_xy(chrA, 0, 0, 16, 12); //width must be multiple of 8
draw_char_xy(chrB, 12, 0, 16, 12); //width must be multiple of 8

void draw_char_xy(byte[] bytes, int ox, int oy, int w, int h) { // draw anywhere! 'off the grid'
  for (int b = bytes.length*8-1; b>=0 ; b--) { //in case of 24 bytes (16x12 pixels) b=bit index 0..192   //16 bits (2 bytes) per line (12 lines)
    //2*12 = 24 destination bytes affected when horizontally 'on the grid'
    //3*12 = 36 bytes affected when horiztonally 'off the grid'

    int x = (b % w) + ox; // x position including offset
    int y = (b / w) + oy; // y position including offset

    int src_byte_index = b/8; // source index
    int src_bit_index = b%8;
    boolean bit_value = (bytes[src_byte_index] & (128>>src_bit_index)) != 0;

    int dst_byte_index = (y / 4) * (4 * COLS) + (y % 4) + (x / 8) * 4; // destination index
    int dst_bit_index = x%8;

    if (bit_value) {
      memory[R + dst_byte_index] ^= 128>>dst_bit_index; // Set the bit
    } else {
      memory[R + dst_byte_index] &= ~(128>>dst_bit_index); // Clear the bit
    }
  }
}
```

# Assembly code to draw a 12x12 bitmap char anywhere on the screen.
It can be optimized a lot. It's rather slow but it does work! Next step is to optimize it with nested loops. Then add gradient masks.
Here is a version that is at least twice as fast: https://gist.github.com/companje/b7bdbed737ee4709b3d50b027e8e5c43
```nasm
%include "sanyo.asm"

char_index: db 0
hello1: db "RICKYBOYII",0

draw_string:  ; input si=string offset
  mov word [draw_char.ox],0
  mov word [draw_char.oy],0
  mov word [draw_char.width],16
  mov word [draw_char.height],12

.lp
  push si ; string offset
  lodsb
  or al,al
  jz .done

  mov byte [draw_char.char],al
  call draw_char
  inc byte [char_index]
  add word [draw_char.ox],12
  pop si
  inc si
  jmp .lp
.done
  pop si
  ret

setup:
  mov ax,RED
  mov es,ax
  mov si,hello1
  call draw_string
  hlt

%include "func.asm"

%include "assets.asm"

times (180*1024)-($-$$) db 0
```
## func.asm
```nasm
draw_char: 
jmp .__________
    ; public
    .char db 'A'
    .ox dw 0
    .oy dw 0
    .width dw 16
    .height dw 12

    ; private
    .bpdiv8 dw 0
    .bpmod8 dw 0
    .bpdivw dw 0
    .bpmodw dw 0
    .x dw 0
    .y dw 0
    .ydiv4 dw 0
    .ymod4 dw 0
    .xdiv8 dw 0
    .xmod8 dw 0
    .src_bit dw 0
    .dst_bit dw 0
    .__________

    ;bp=width*height
    mov ax,[.width]
    mov bx,[.height]
    xor dx,dx
    mul bx
    mov bp,ax
    dec bp            ; n=length*8-1 (zero based)

    ; --- prepare di
    mov di,0

    ; --- prepare si
    xor ah,ah
    mov al,[.char]
    sub al,32
    mov cx,24
    mul cx
    mov si,ax
    add si,font

.loop_bits:
    
    ; --- bpdiv8, bpmod8
    mov ax,bp         ; bp = current bit index 0..192 (in case of 16x12)
    xor dx,dx
    mov cx,8
    div cx            ; ax=bp/8, dx=bp%8
    mov [.bpmod8],dx
    mov [.bpdiv8],ax

    ; --- bpdivw, bpmodw
    mov ax,bp         ; bp = current bit index 0..192 (in case of 16x12)
    xor dx,dx
    mov cx,[.width]
    div cx            ; ax=bp/w, dx=bp%w
    mov [.bpmodw],dx
    mov [.bpdivw],ax

    ; --- int x,y
    add dx,[.ox]      ; (b % w) + ox
    add ax,[.oy]      ; (b / w) + oy
    mov [.x],dx
    mov [.y],ax

    ; --- ydiv4, ymod4
    mov ax,[.y]
    xor dx,dx
    mov cx,4
    div cx
    mov [.ydiv4],ax
    mov [.ymod4],dx

    ; --- xdiv8, xmod8
    mov ax,[.x]
    xor dx,dx
    mov cx,8
    div cx
    mov [.xdiv8],ax
    mov [.xmod8],dx

    ; --- DI = (y/4) * (4*COLS) + (y%4) + ((x/8)*4);
    mov ax,[.ydiv4]
    xor dx,dx
    mov cx,4*COLS
    mul cx
    add ax,[.ymod4]
    mov cx,[.xdiv8]
    times 2 shl cx,1   ; *=4
    add ax,cx
    mov di,ax          ; destination index

    ; --- dst_bit = 128>>xmod8
    mov ax,128
    mov cx,[.xmod8]
    shr ax,cl
    mov [.dst_bit],ax  ; destination bit mask

    ; --- src_bit = 128>>bpmod8
    mov ax,128
    mov cx,[.bpmod8]
    shr ax,cl
    mov [.src_bit],ax  ; source bit mask

    ; --- if [si + bpdiv8] & [.src_bit]
    mov bx,[.bpdiv8]   ; source index
    test [si + bx],ax
    mov byte al,[.dst_bit]  ; load bit mask into al
    jz .clr
.set
    or byte [es:di],al
    jmp .while
.clr  
    not al  
    and byte [es:di],al
.while
    ;loop while(bp-->0)
    dec bp
    or bp,bp
    jnz .loop_bits

    ret

; ───────────────────────────────────────────────────────────────────────────
```


# animated characters from TimeBandit by Michtron
![timebandit-animations](https://github.com/user-attachments/assets/645d75db-b0bf-4b7e-bda7-f7e0737dd316)

# position of code and data in BANDIT.EXE
```java
offset=0x0000, count=0x0014  //MZ header
offset=0x0297, count=0x0081  //code
offset=0x0338, count=0x08A8  //FONT
offset=0x08D1, count=0x0293  //code
offset=0x0C30, count=0x3000  //sprites: key till bandit
offset=0x3C20, count=0x002E  //strings: 0+NCN@NLNONRNUNFNI01
offset=0x3C4E, count=0x1E00  //sprites: donut till scorpion
offset=0x627A, count=0x0018  //string 1234567890123456789012345
offset=0x6CF0, count=0x062F  //strings
offset=0x7393, count=0x03BF  //strings trees, cacti, the timegates ...
offset=0x7753, count=0x03D8  //strings
offset=0x7E0A, count=0x0678  //strings
offset=0x84A1, count=0x0477  //code
offset=0x8BBB, count=0x048F  //strings
offset=0x90DB, count=0x003F  //code
offset=0x914A, count=0x1E4C  //code
offset=0xAF96, count=0x0012  //string?
```


# keyboard test
<img src="https://github.com/user-attachments/assets/54f8ce2d-6d18-4736-bba4-a323b368ab23" align="right">

```nasm
cpu 8086
org 0

GREEN equ 0x1c00

setup:
  mov al,0xFF
  out 0x3a,al           ; keyboard
  mov al,0x30
  out 0x3a,al           ; keyboard

  mov al, 5
  out 10h, al               ; select address 0x1c000 as green video page
  
  mov ax,GREEN      
  mov es,ax
  xor di,di
  
draw:
  in al,0x38  ;get data byte
  stosb

  cmp di,14400
  jb draw
  xor di,di
  jmp draw

times (180*1024)-($-$$) db 0
```

# Expression parser
Work in progress expression parser: https://companje.nl/parse

# xy-loop with one label
```nasm
draw4x12:               ; bx should be zero when called
  push bx
  call calc_di_from_bx
  mov bh,4              ; width in cols (1 col = 8px)
  mov bl,4              ; height in rows (1 row = 4px)
  call draw_pic
  pop bx
  add bl,4
  cmp bl,4*8
  jl draw4x12
  mov bl,0
  add bh,4
  cmp bh,4*12
  jl draw4x12
  ret  
```

# set cursor / calc DI
```nasm
calc_di:          ; input bl,bh [0,0,71,49]
  mov ax,144      ; 2*72 cols
  mul bh          ; bh*=144 resultaat in AX
  shl ax,1        ; verdubbel AX
  mov di,ax       ; di=ax (=bh*288)
  shl bl,1        ; bl*=2
  shl bl,1        ; bl*=2
  mov bh,0
  add di,bx       ; di+=bl
  ret
```

# mame debugger
```bash
dump memory.dmp,0,fffff   # hex
save memory.bin,0,fffff   # bin
```

# visual dump of the current memory in MAME
![Screenshot 2024-12-01 at 07 57 19](https://github.com/user-attachments/assets/f1d826e5-1b80-406e-836f-7f6a2e7e088f)

# getImageFromBytes
```java
PImage getImageFromBytes(byte[] bytes, int w, int h) { //w,h in pixels - 3 channel 3 bit image
  PImage img = createImage(w, h, RGB);
  img.loadPixels();
  for (int y=0, bit=128, j=0; y<h; y++) {
    for (int x=0; x<w; x++, bit=128>>(x%8), j++) {
      int i = int(y/4)*(w/2)+(y%4)+int(x/8)*4;
      int r = (bytes[i+0*w/8*h] & bit)>0 ? 255 : 0;
      int g = (bytes[i+1*w/8*h] & bit)>0 ? 255 : 0;
      int b = (bytes[i+2*w/8*h] & bit)>0 ? 255 : 0;
      img.pixels[j] = color(r, g, b);
    }
  }
  img.updatePixels();
  return img;
}
```

# draw picture
The image format is optimized for the Sanyo's videomemory. color planes are separated. 
In the case of a 32x16 picture: 64 bytes red, 64 bytes green, 64 bytes blue. 16px vertical means 4 rows (1 row is 4 lines). 32px horizontal means 4 cols (4*8).

![beker-resized](https://github.com/user-attachments/assets/c9805942-6cd5-493a-978b-098a0ba5a897)

```nasm
push cs
pop ds      ; ds=cs

mov si, img
mov bh,4 ; cols 
mov bl,4 ; rows
call draw_pic
hlt

draw_pic:
  mov ax, RED
  call draw_channel
  mov ax, GREEN
  call draw_channel
  mov ax, BLUE
  call draw_channel
  ret
draw_channel:
  mov es,ax
  xor di,di
  xor cx,cx
  mov cl,bl        ; rows (bl)
rows_loop:
  push cx
  xor cx,cx
  mov cl,bh        ; cols (bh)
cols_loop:
  movsw
  movsw
  loop cols_loop
  add di,COLS*4 - 4*4
  pop cx
  loop rows_loop
  ret

img: 
  db 0,112,127,112,255,255,255,255,245,250,253,250,0,14,254,14
  db 56,28,7,0,255,127,255,255,253,250,245,255,28,56,224,0
  db 0,0,0,0,15,3,3,3,240,192,192,128,0,0,0,0
  db 0,0,0,0,3,7,63,127,192,176,212,234,0,0,0,0
  db 0,96,117,96,255,255,255,255,160,208,232,208,0,4,234,4
  db 48,24,7,0,255,127,127,191,232,208,224,215,8,16,224,0
  db 0,0,0,0,15,3,3,3,160,192,128,128,0,0,0,0
  db 0,0,0,0,3,7,63,127,128,144,196,226,0,0,0,0
  db 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0
  db 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0
  db 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0
  db 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0
```

# clear green channel
```nasm
clear_green_channel:
  mov ax,GREEN
  mov es,ax
  xor di,di
  mov cx,0x2000
  xor ax,ax
  rep stosw
  ret
```


# mame in debug mode
* start with `-debug`
* F11 step into
* F4 run to cursor
* `-debugscript autostart.txt` containing `g` for 'go'

# Ports (hex)
* 00: PIC
* 02: PIC
* 08: disk command/status
* 0A: disk track
* 0C: disk sector
* 0E: disk data
* 10: video page
* 18: joystick
* 1a: printer data
* 1C: parallel/drive control
* 1e: PPI control
* 20: timer channel 0
* 22: timer channel 1
* 24: timer channel 2
* 26: timer control
* 28: (serial header) serial 
* 29: (serial header)
* 2a: (serial header) serial 
* 2b: (serial header)
* 30: CRTC address
* 32: CRTC data
* 38: keyboard data
* 3A: keyboard command/status

# HxC Floppy Emulator / Floppy image file converter
See [hxc](hxc)

# Time Bandit raw flux file
write this [flux file](https://github.com/companje/companje.nl/raw/refs/heads/master/downloads/bandit.scp) (19,2MB) using [Greaseweazle](https://github.com/keirf/greaseweazle/) to an empty floppy to play the classic Time Bandit game on your Sanyo MBC-550/555.
```batch
gw write bandit.scp --tracks="c=0-39:step=2"
```
<img src="https://github.com/user-attachments/assets/83f37143-a29a-4712-b4b2-1cafa2ae4827" height="150">
<img src="https://github.com/user-attachments/assets/3f9f401e-e7e0-447b-94e4-920afa7f47ea" height="150">

# Run Time Bandit on Gotek with Flashfloppy 3.42 and HFE_v3 disk image
Place this HFE_v3 file (2,5MB) on your Gotek drive with FlashFloppy to play Time Bandit on your Sanyo MBC-550/555. First boot in MS-DOS, than switch to this diskimage and type BANDIT to start Time Bandit. Enjoy!
[0001_TimeBandit_Sanyo_MBC55x.hfe](https://github.com/companje/companje.nl/raw/refs/heads/master/downloads/0001_TimeBandit_Sanyo_MBC55x.hfe)
The flux file by Greaseweazle was converted to HFE_v3 using [HxC2001](https://github.com/jfdelnero/HxCFloppyEmulator).

<img src="https://github.com/user-attachments/assets/38c10676-2188-4882-b830-55903b125830" height="250">

# RAMDISK
put this in autoexec.bat to get an extra drive in RAM.
```batch
ramdisk 64
rem OPTIONAL copy diskcopy.com e:
path e: 
```

you need this in config.sys:
```batch
device=ramdrv.sys
```
and you need these files on your msdos 2.11 floppy:
```batch
RAMDISK.COM
RAMDRV.SYS
DISKCOPY.COM (optional)
CONFIG.SYS
AUTOEXEC.BAT
```

# Mame save screenshot
* F12 - Saves screenshot to subfolder in 'snap'. for example: ./snap/mbc55x/0000.png'
* Shift F12 - saves movie file in same location

# Mount a .DSK file on Mac
rename it to .DMG and open it. Now you can copy the files.
(does not work for MS-DOS v1.25)

# Sanyo MBC-555 boot code / BIOS in ROM disassembly
* clears the screen, inits CRT, keyboard, loads bootsector from floppy and jumps to it
* `FFFF:0000` jump to code in ROM
* `FE00:1E00` start of code in ROM
* `0038:0000` start of loaded code in floppy bootsector
* Details: <a href="/Sanyo-MBC555-ROM">Sanyo MBC-555 boot code in ROM disassembled</a>

# Sanyo MBC-555 floppy bootsector disassembly
* Details: <a href="/Sanyo-MBC555-floppy-bootsector">Sanyo MBC-555 floppy bootsector disassembly</a>

# Repair story by Mike @ Leaded Solder 
https://www.leadedsolder.com/2022/08/23/sanyo-mbc555-power-supply-swap-pickup.html
tip: https://github.com/keirf/Greaseweazle

# black & white ordered dithering
```nasm
;from dark to light: 4x8 bits (4 lines, 8 bits per line). in total 8 chars.
grays: db 0,0,0,0, 136,0,34,0, 170,0,170,0, 170,17,170,68, 170,85,170,85, 85,238,85,187, 119,255,221,255, 255,255,255,255
```
<img width="644" alt="ordered-dithering-black-white" src="https://user-images.githubusercontent.com/156066/193271595-1b238df0-80f1-4101-8205-6babedb8365a.png">

# 3 bit grayscale dithering on monochrome monitor
```processing
PImage img = loadImage("input/"+filename);
img.resize(width, 200);
img.filter(GRAY);
img = applyDithering(img, 8); //8 levels of brightness
img = grayTo3bitIntensity(img);
savePIC(img, "data/output/"+filename.replace(".jpg", ".pic"));
///
void savePIC(PImage img, String filename) {
  byte bytes[] = new byte[img.width*img.height*3/8+4];
  bytes[0] = byte(img.width & 255);
  bytes[1] = byte(img.width >> 8);
  bytes[2] = byte(img.height & 255);
  bytes[3] = byte(img.height >> 8);

  for (int i=0, x=0, y=0, n=img.width*img.height/8; i<n; i++) {
    for (int j=128; j>0; j/=2, x=(x+1)%img.width, y=i/(img.width/8)) {
      color c = img.get(x, y);
      bytes[i+4+2*n] |= byte(j * red(c)/255);
      bytes[i+4+1*n] |= byte(j * green(c)/255);
      bytes[i+4+0*n] |= byte(j * blue(c)/255);
    }
  }
  saveBytes(filename, bytes);
}

PImage grayTo3bitIntensity(PImage img) {
  PImage img2 = img.get();
  color c[] = {color(0), color(0,0,255),color(0,255,0),color(0,255,255),color(255,0,0),color(255,0,255),color(255,255,0),color(255,255,255)};
  int lut[] = {0,1,4,2,5,3,6,7};
  img.loadPixels();
  for (int y = 0; y<img.height; y++) {
    for (int x = 0; x<img.width; x++) {
      int index = int(brightness(img.pixels[y*img.width+x])/32); //32=256/8 because 8 colors in 3 bit
      img2.set(x,y,c[lut[index]]);
    }
  }
  return img2;
}
```

# Panel mounted switches for diskimage and drive selection
![IMG_8359](https://user-images.githubusercontent.com/156066/190622182-a80b7757-808f-47d2-b0a2-368dac2b7d0b.jpeg)
<img width="1443" alt="Screenshot 2022-09-16 at 12 12 41" src="https://user-images.githubusercontent.com/156066/190617011-8e396784-b571-4402-b703-3166797d6445.png">
* check if I can replace the physical USB switch with a chip controlled by an Arduino: 
  * https://www.ti.com/product/TS5V330C
  * https://hackaday.com/2017/05/17/a-few-of-our-favorite-chips-4051-analog-mux/
  * https://nl.farnell.com/on-semiconductor/fsusb42umx/switch-usb-2-2-port-smd-umlp-10/dp/1495467?ost=fsusb42umx
  * https://www.tme.eu/en/details/pi5c3257qe/analog-multiplexers-and-switches/diodes-incorporated/ (deze zou leverbaar en geschikt moeten zijn. Datasheet: https://www.tme.eu/Document/67211692f5b32aa4a424cdbb7a0b36ac/PI5C3257.pdf)

# run Mame for Sanyo MBC-555
* ROM in roms/mbc55x/mbc55x-v120.rom
* Fn+DEL (to enable UI interface controls) then TAB to show menu
* make mame for (just) Sanyo: ```make SUBTARGET=mbc55xtest SOURCE=src/mame/sanyo/mbc55x.cpp```
* make mame tools: ```make TOOLS=1 REGENIE=1```
```bash
./mbc55xtest mbc55x -ramsize 256K -verbose -skip_gameinfo -effect scanlines -window -nomaximize -resolution0 800x600 -flop1 floppies/disk-a.img
```

# BASIC CALL function
* http://www.antonis.de/qbebooks/gwbasman/call.html

# BASIC manual Sanyo MBC-555
* https://hwiegman.home.xs4all.nl/downloads/mbc550_series.pdf

# decode Michtron PIC image file with Processing
```js
size(640,400);
noStroke();

//load and check width
byte bytes[] = loadBytes("SATURN.PIC");
int w = (bytes[1]<<8) + (bytes[0] & 0xff);
int h = (bytes[3]<<8) + (bytes[2] & 0xff);

//check and fix width if needed
//int bytesPerChannel = (bytes.length-4)/3;
//if (w*h/8<bytesPerChannel) 
//  w = (int(w+8)/8)*8;
  
//draw
for (int i=0, x=0, y=0, n=w*h/8; i<n; i++) {
  for (int j=128; j>0; j/=2, x=(x+1)%w, y=i/(w/8)) {
    int rr=(bytes[i+2*n+4]&j)/j<<8;
    int gg=(bytes[i+1*n+4]&j)/j<<8;
    int bb=(bytes[i+0*n+4]&j)/j<<8;
    fill(rr, gg, bb);
    rect(x, y*2, 1, 1.75); //double height, with slight vertical raster line in between the lines
  }
}

get(0,0,w,int(h*1.75)).save("SATURN.PNG");
```
![SATURN](https://user-images.githubusercontent.com/156066/185075825-8ee7b504-55c3-4246-bd89-2f91893d62e6.PNG)

# receive data from Python
on Sanyo: ```type file.asm > aux```

```python
#!/usr/bin/env python3

import serial

ser = serial.Serial('/dev/tty.usbmodem1301',1200)

while True:
    x = ser.read()
    print(x.decode('ascii'), end="")

ser.close()
```

# sanyo mbc-555 VRAM emulation in Processing/Java
(running in 72 cols mode, update to 80 = 640px if needed)
```js
PImage getVRAM() {
  PImage img = createImage(576, 200, RGB);
  img.loadPixels();
  for (int y=0, bit=0, j=0; y<img.height; y++) {
    for (int x=0; x<img.width; x++, bit=128>>(x%8), j++) {
      int i=int(y/4)*img.width/2+(y%4)+int(x/8)*4;
      int r = (mem[RED+i] & bit)>0 ? 255 : 0;
      int g = (mem[GREEN+i] & bit)>0 ? 255 : 0;
      int b = (mem[BLUE+i] & bit)>0 ? 255 : 0;
      img.pixels[j] = color(r, g, b);
    }
  }
  img.updatePixels();
  return img;
}
```

# tixyboot.asm
A tribute to Martin Kleppe's beautiful https://tixy.land as well as a tribute to the Sanyo MBC-550/555 PC (1984) which forced me to be creative with code since 1994.

https://github.com/companje/Sanyo-MBC-550-555-experiments/tree/main/tixy.boot

<img src="https://github.com/companje/Sanyo-MBC-550-555-experiments/blob/main/tixy.boot/doc/screengrab.gif?raw=true" width="400">


# My own emulator and bootsector experiments
* https://github.com/companje/Sanyo-MBC-550-555-experiments

## Mame
* sanyo mbc55x.cpp class in Mame: https://github.com/mamedev/mame/blob/master/src/mame/drivers/mbc55x.cpp
* mamedev documentation: https://docs.mamedev.org/_files/MAME.pdf

## segments and offsets
"The 8086 has 20-bit addressing, but only 16-bit registers. To generate 20-bit addresses, it combines a segment with an offset. " SEGMENT*16+OFFSET

## qemu
not for Sanyo but emulator for x86 in general:
On Mac:
```
brew install qemu
qemu-system-x86_64 -fda boot-basic.img
```

## libdsk
nog onderzoeken: http://www.seasip.info/Unix/LibDsk/

## debug
```
-l 100 1 0 1  # load onto address 100h from 2nd drive (1) the first sector (0) and only one sector
-n A:tmp.com
-rcx
100
-w
writing 0200h bytes (512 bytes)
```

## interrupt controller info
* https://en.wikibooks.org/wiki/X86_Assembly/Programmable_Interrupt_Controller

## Sanyo MBC550 - John Elliott 27 January 2016
* https://www.seasip.info/VintagePC/sanyo.html#serhw

## int 14h Serial, int 16h keyboard etc
* https://www.plantation-productions.com/Webster/www.artofasm.com/DOS/ch13/CH13-3.html#HEADING3-1

## ms-dos int services
* http://spike.scu.edu.au/~barry/interrupts.html#ah57

## debug search
```bat
debug basic.exe
s 1c67:ffff ffff e4 18   # searches for the bytes e4 and 18
```

## Time Bandit BANDIT.EXE crack
this is a first step. Skipping disk access at start.
```bat
debug BANDIT.EXE
-g 8ee8
-g =8ef3
```

## samdisk
https://simonowen.com/samdisk/
(samdisk-388-osx works on Macbook Air M1)
```bash
./samdisk IMAGE.td0  /Volumes/FLASHFLOPPY/IMAGE.dsk
```

## GOTEK
<img src="https://user-images.githubusercontent.com/156066/160270847-03ebfc54-547e-4a9a-813f-6114f2f6213b.jpg" alt="Sanyo-MBC-555-Rick-Companje" width="200" align="right">

Goed nieuws! Gotek met FlashFloppy firmware werkt super op de Sanyo MBC-555. https://gotek.nl/
```ini
# in FF.CFG 
interface = shugart
host = pc-dos
pin02 = auto
pin34 = auto
nav-mode = indexed   # voor 0001-msdos211.img etc
indexed-prefix = ""
```

<img src="/pages/img/Screenshot%202021-05-21%20at%2000.09.05-sanyo-mbc555-gotek.jpg">

## Diversen
* wellicht deze bestellen: http://www.deviceside.com/fc5025.html (nee niet, mailcontact gehad. Werkt alleen voor 1.2MB diskdrives, en dan ook nog eens readonly.)
* 
* http://www.seasip.info/VintagePC/sanyo.html
* 
* Capacitor C9 on the board may need to be dealt with if disk access is slow or erratic (it was installed backwards at the factory)."
* http://www.vintage-computer.com/vcforum/showthread.php?24281-Teac-FD-54A
* "The PC floppy cable (assuming that you don't have any drives with a READY/ line on pin 34) can be a bit problematical to fabricate. On the other hand, if you can find a Teac FD235HF with the appropriate jumpers or a FD235F (which does have a READY/ line), you're in business, sort of." [[http://www.vintage-computer.com/vcforum/archive/index.php/t-23641.html?s=382f5103d732b9ff22b19f0dcba42069|source]]
* see [[disk]]

* index sensor measures optically the hole in the floppy disk. It marks the start of the current track. Read 'index sensor adjustment' in Sams Computer Facts about the Sanyo. I measure 208ms (milliseconds) for one turn of the disk. Around 5 turns a second and 300 turns per minute. Which is right according to 'spindle speed adjustment' part.
* Weird thing: when I remove the index sensor this has no effect on the readings on TP9 and TP10. There's 10us between the pulses.
* 'Precompensation adjustment': Connect input of a scope to TP1 on System Board. Set scope sweep to 1uSec, voltage to 2V and trigger to positive slope. Adjust Precompensation Control (RV1) for 2uSec from the rising edge of the first pulse to the rising edge of the second pulse. RESULT: 2uSec 500kHz.

## DISKCOPY
Als een diskette bad sectors heeft kun je geen DISKCOPY gebruiken. Je kunt in veel gevallen wel met COPY de bestanden overzetten. Eventueel kun je met DEBUG de sectors 1 voor laden en wegschrijven (l 0 0 5 1 -> w 0 0 0 5 1). 

## Info about segments:offsets
* https://thestarman.pcministry.com/asm/debug/Segments.html

## Test pins on mainboard
* TP1: Precompensation adjustment test. Should measure 2 uSec / 500 kHz. Adjust RV1 to fix.
* TP2: ??

## Drive Track Program
The following Basic program can be used to select Driva A or B, select side 0 or 1 and step the Drive Head to a specific track. To stop the program, press the BREAK key.
```gwbasic
10 INPUT "ENTER DRIVE (A OR B)"; D$
20 INPUT "ENTER SIDE (0 OR 1)"; S
30 IF S=0 THEN Y=0 ELSE Y=4
40 IF D$="A" THEN Z=0 ELSE Z=1
50 TS=0: OUT 28, Y+Z: OUT 8,8
60 FOR D=1 TO 500: NEXT D
70 INPUT "ENTER TRACK NUMBER"; T
80 IF T>40 THEN 70
90 IF T>TS THEN TR=T-TS ELSE TR=TS-T
100 IF T>TS THEN C=72 ELSE C=104
110 FOR X=1 TO TR
120 OUT 8,C
130 FOR D=1 TO 5: NEXT D
140 NEXT X: TS=T
150 PRINT "PRESS ANY KEY TO STOP"
160 A$=INKEY$: OUT 8,228: IF A$="" THEN 160 ELSE 70
```

## EDLIN
https://www.computerhope.com/edlin.htm
```
1l  # list from line one
1   # edit line 1
5i  # insert line(s) before line 5
q   # exit without saving
e   # save and exit
```

```
edlin autoexec.bat
i
  line of text
  ^Z
e
```

## wordstar manual
http://www.textfiles.com/bitsavers/pdf/microPro/Wordstar_3.3/Wordstar_3.3_Reference_Manual_1983.pdf

## debug.com
* fill memory with 0's: `e 0 ffff 0`
* `rcx` sets cx register. This register is used in debug.com to store the amount of bytes to write to the loaded (or newly created file).
* `l 0 0 5 1` load sector 5 of drive 0 at currentSeg:0000

## create a program with debug.com (ms-dos 1.25 without assemble command)
```
A> debug test.com
- e 100
  B8 {space} 00 {space} 4C {space} CD {space} 21 {enter}
-u
0AAC:0100 B8004C    MOV    AX,4C00
0AAC:0103 CD21      INT    21
-w
-q
```

```
-rcx
6
-n filename
-w
```
more info about Debug: https://thestarman.pcministry.com/asm/debug/debug.htm

## asm.com
* asm.com seems not to work well on Sanyo: http://www.datapackrat.com/86dos/index.shtml
* TinyASM works better. See: https://github.com/nanochess/tinyasm/

## technical info
* http://www.seasip.info/VintagePC/sanyo.html

## Game I/O
* http://www.built-to-spec.com/blog/2009/09/10/using-a-pc-joystick-with-the-arduino/
* https://www.epanorama.net/documents/joystick/pc_joystick.html
* https://github.com/phillipmacon/m.a.m.e/blob/master/src/devices/bus/a2gameio/gameio.cpp
```
 Apple II Game I/O Connector
    This 16-pin DIP socket is described in the Apple II Reference
    Manual (January 1978) as "a means of connecting paddle controls,
    lights and switches to the APPLE II for use in controlling video
    games, etc." The connector provides for four analog "paddle"
    input signals (0-150KΩ resistance) which are converted to
    digital pulses by a NE558 quad timer on the main board. The
    connector also provides several digital switch inputs and
    "annunciator" outputs, all LS/TTL compatible. Apple joysticks
    provide active high switches (though at least one third-party
    product treats them as active low) and Apple main boards have no
    pullups on these inputs, which thus read 0 if disconnected.
    While pins 9 and 16 are unconnected on the Apple II, they provide
    additional digital output and input pins respectively on the Sanyo
    MBC-550/555 (which uses 74LS123 monostables instead of a NE558).
    The Apple IIgs also recognizes a switch input 3, though this is
    placed on pin 9 of the internal connector rather than 16.
    ...
                                ____________
                   +5V   1 |*           | 16  (SW3)
                   SW0   2 |            | 15  AN0
                   SW1   3 |            | 14  AN1
                   SW2   4 |            | 13  AN2
                  /STB   5 |  GAME I/O  | 12  AN3
                  PDL0   6 |            | 11  PDL3
                  PDL2   7 |            | 10  PDL1
                   GND   8 |            |  9  (AN4/SW3)
                            ------------
```
