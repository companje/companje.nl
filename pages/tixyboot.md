# tixyboot.asm
A tribute to Martin Kleppe's beautiful https://tixy.land as well as a tribute to the Sanyo MBC-550/555 PC (1983) which forced me to be creative with code since 1994.

https://github.com/companje/tixy.boot

<img src="https://github.com/companje/tixy.boot/blob/main/doc/screengrab.gif?raw=true" width="400">

```nasm
; tixyboot.asm by Rick Companje, 2021-2022, MIT licence
; a tribute to Martin Kleppe's beautiful https://tixy.land
; as well as a tribute to the Sanyo MBC-550/555 PC (1984)
; which forced me to be creative with code since 1994.
;
; The Sanyo MBC-55x has a very limited ROM BIOS. After some 
; hardware setup by the ROM BIOS a RAM BIOS loaded from
; floppy takes over. This means that we don't have any BIOS
; functions when running our own code from the bootsector. 
;
; The Sanyo has no display mode 13 (not even with the original
; RAM BIOS). It uses a 6845 video chip with three bitmapped 
; graphics planes and is organized as 50 rows by 72 (or 80) columns.
; One column consists of 4 bytes. Then the next column starts.
; After 72 columns a new row starts. A bitmap of 16x8 pixels 
; is made up of 2 columns on row 1 and 2 columns on row 2...
;
; To run this code write the compiled code to the bootsector of a
; Sanyo MBC-55x floppy or use an emulator like MAME.
;
; Add your own visuals by adding your own functions to the fx_table.
;
; t = time  0..255
; i = index 0..255
; x = x-pos 0..15
; y = y-pos 0..15
;
; result: al -15..15 (size and color)
;         al<0 red, al>0 white

org 0
cpu 8086

COLS  equ 72
TOP   equ 9*4*COLS+20*4    ; row=9,col=20
RED   equ 0xf0
GREEN equ 0x08
BLUE  equ 0xf4

effect_timeout equ 30      ; every 30 frames another effect
isqrt_table    equ 1000    ; available location in code segment

; using dx and bx registers as t,i,x,y variables
%define t dh
%define i dl
%define x bh
%define y bl

jmp setup

sin_table: ;31 bytes, (input -15..15 index=0..31)
    db 0,-3,-6,-9,-11,-13,-15,-15,-15,-15,-13,-11,-9,-6,-3,
    db 0, 3, 6, 9, 11, 13, 15, 15, 15, 15, 13, 11, 9, 6, 3,0  
    ; tried to mirror the second line of the sine table with code 
    ; but would take a same of amount of bytes

fx_table:      ; the 'effects' table: 8 bytes, overwriting the 'Sanyo1.2' tag
    db fx0,fx1,fx2,fx3,fx4,fx5

fx1: ; y+t
    mov al,y
    add al,t
    ret

fx0: ; xor
    mov al,x
    xor al,y
    or al,t
    sub al,7
    ret

fx2: ; sin(x+y+t)
    mov al,x
    add al,y
    add al,t
    call sin
    ret

fx3: ; bitmap_data[i+t]
    push bx
    mov al,i
    add al,t
    mov bx,bitmap_data
    xlat
    pop bx
    ret

fx4: ; ((y-x)*-8)+t
    mov al,y
    sub al,x
    mov cl,-8
    mul cl
    call limit
    add al,t
    ret

fx5: ; sin(sqrt(x^2+y^2))-t)
    mov al,i   ; isqrt_table[i] = sqrt(x^2+y^2)
    push bx
    mov bx,isqrt_table
    xlat
    pop bx
    sub al,t
    call sin
    ret

sin: ; sine function
    call wrap
    push bx
    add al,15 ; sin(-15) = sin_table[0]
    mov bx,sin_table
    xlat 
    pop bx
    ret

wrap: ; while (al>15) al-=15; while (al<-15) al+=15
    cmp al,15
    jg .sub16
    cmp al,-15
    jl .add16
    ret
  .sub16:
    sub al,31
    jmp wrap
  .add16:
    add al,31
    jmp wrap

limit: ; if (al>15) al=15; else if (al<-15) al=-15;
    cmp al,15
    jg .pos16
    cmp al,-15
    jnl .ret
    mov al,-15
    ret
  .pos16:
    mov al,15
  .ret:
    ret

calc_isqrt_xx_yy: ; isqrt_table[i] = sqrt(x^2+y^2)
    push dx
    push di
    mov di,isqrt_table      ; di=isqrt_table[0]
    add di,dx               ; di+=i
    mov al,x
    inc al
    mul al                  ; x*x
    xchg ax,cx
    mov al,y
    inc al
    mul al                  ; y*y
    add ax,cx               ; + 
  .isqrt:  ; while((L+1)^2<=y) L++; return L
    xchg cx,ax              ; cx=y
    xor ax,ax               ; ax=L=0
  .loop:
    inc ax
    push ax
    mul ax
    cmp ax,cx
    pop ax
    jl .loop
    dec ax
  .end_isqrt:
    mov [di],al             ; store al
    pop di
    pop dx
    ret

setup:                      ; starting point of code

    ;no need to clear the screen. ROM BIOS does this already.

    ;set ds and es segments to cs
    push cs
    pop ds                  ; ds:si in code segment
    push cs
    pop es                  ; es:di in code segment

    ; generate 16x8 bitmap data for 16 sizes of dots.
    ; Because the dots are symmetric we can save at least
    ; 97 bytes by mirroring the left-top corner 3 times

    call generate_chars

    xor bp,bp               ; start with effect 0
    xor dx,dx               ; t=i=0 (clear time and index)

draw:
    mov di,TOP              ; left top corner to center tixy
dot:
    push dx
    mov al,i                ; al=index
    xor ah,ah               ; ah=0
    mov cl,16
    div cl                  ; calculate x and y from i
    xchg ax,bx              ; bh=x, bl=y
    pop dx

    ;on the first frame calc sqrt table for every i
    ;reusing the i,x,y loop here. this saves some bytes.
    or t,t
    jnz .cont
    call calc_isqrt_xx_yy
  .cont:
   
    push bp
    push bx
    xchg bx,bp
    mov bp,[bx+fx_table]
    and bp,0xff             ; effect function needs to fit in one byte to save 8 bytes
    pop bx
    call bp                 ; call the effect function
    pop bp

draw_char_color:
    cmp al,0
    pushf
    jge .red
    neg al
  .red:
    mov cx,RED << 8              ; ch=0xf0, cl=0
    call draw_char
    popf
    jge .green_blue
    xor al,al               ; if negative then just red so clear (al=0) green and blue
  .green_blue:
    mov ch,GREEN
    call draw_char
    mov ch,BLUE
    call draw_char
  .next:  
    inc i                   ; i++
    add di,8         
    cmp x,15
    jl dot                  ; next col
    add di,4*COLS       
    add di,160
    cmp y,15
    jl dot                  ; next line
    inc t
    cmp t,effect_timeout
    jb draw                 ; next frame
    inc bp                  ; inc effect
    xor t,t                 ; reset time
    cmp bp,6
    jl draw                 ; next effect
    xor bp,bp               ; reset effect
    jmp draw

draw_char:                  ; es:di=vram (not increasing), al=char 0..15, destroys cx
    push ax
    push di

    push cx
    pop es                  ; es=bp (color channel now cx)
    push cs
    pop ds                  ; ds=cs

    mov cx,4
    push cx
    push cx

    and al,15               ; limit al to 15
    cbw                     ; ah=0
   
    shl al,cl               ; al*=16
    add ax,bitmap_data
    xchg si,ax              ; si = source address of rendered bitmap char

    pop cx                  ;cx=4
    rep movsw
    add di,4*COLS-8
    pop cx                  ;cx=4
    rep movsw

    pop di                    
    pop ax
    ret

generate_chars:
    mov di,bitmap_data      ; dest address of render data
    xor bh,bh
  .render_char:
    xor ah,ah
    mov al,bh
    mov cx,4                ; cl is also used below
    mul cl
    mov si,ax
    add si,img
  .render_char_part:        ; input requirement at first time cl=4
    lodsb                   ; use lodsb instead of movsb to keep a copy in al
    stosb                   ; draw in left top nibble
    push bx                 ; save cur x and y
    push cx                 ; cur loop counter (4,3,2,1)
    push cx
    pop bx                  ; bx = counter
    shl bx,1                ; bx *= 2
    push bx                 ; save counter*2 for right bottom
    cmp bx,2                ; skip top line of left bottom nibble
    je .flip_bits
    mov [di+bx+1],al        ; draw in left bottom starting at line 3 instead of 4

  .flip_bits:                 ; flips all bits dropping highest bit
    mov cl,8                ; 8 bits to flip
    xor ah,ah
  .flip_bit:
    mov bx,0x8001           ; bl=1, bh=128  bl doubles, bh halves
    shl bl,cl
    test al,bl
    jz .next_bit
    dec cx
    shr bh,cl
    or ah,bh
    inc cx
  .next_bit:
    loop .flip_bit          ; loop 8 bits for flipping
    mov [di+3],ah           ; draw in right top nibble
    pop bx                  ; bx = counter*2
    cmp bx,2                ; skip top line of right bottom nibble
    je .flip_done
    mov [di+bx+5],ah        ; draw in right bottom starting at line 3 instead of 4
  .flip_done:
    pop cx                  ; restore loop counter
    pop bx                  ; restore x and y
    loop .render_char_part
  .clear_bottom_line:
    add di,7
    xor al,al
    stosb                   ; right bottom
    add di,3
    stosb                   ; left bottom
  .next_char:
    inc bh                  ; next char
    cmp bh,16
    jl .render_char
    ret

img:
    db 0,0,0,0
    db 0,0,0,1
    db 0,0,0,3
    db 0,0,1,3
    db 0,0,3,7
    db 0,0,7,15
    db 0,3,15,31
    db 0,7,31,63
    db 1,15,63,63
    db 3,31,63,63
    db 7,31,63,127
    db 7,31,127,127
    db 7,63,127,127
    db 15,63,127,127
    db 15,63,127,255
    db 31,127,255,255

%assign num $-$$
%warning total num

bitmap_data:                          ; destination for 128 bytes rendered bitmap data

times 368640-num db  0                 ; fill up with zeros until file size=360k
```
