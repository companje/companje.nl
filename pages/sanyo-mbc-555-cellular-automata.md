# Wolfram Cellular Automata
<img width="576" height="400" alt="Wolfram-Cellular-Automata" src="https://github.com/user-attachments/assets/497d8f43-37e7-497e-8f9f-522a1225e6d5" />
[View source](/sanyo-mbc-555-cellular-automata)

```nasm
GREEN_SEG   equ 1c00h ; 0800h in mame
ROWS        equ 50
COLS        equ 72
LINES       equ 4*ROWS
ROW_BYTES   equ 4*COLS
PLANE_BYTES equ COLS*LINES

setup:
  ; DS addresses code/data; ES addresses the green VRAM plane.
  ; 14,400 bytes are visible (576x200 monochrome); 16 KB are addressable.
  push cs
  pop ds
  mov ax,GREEN_SEG
  mov es,ax
  
  ; Select the RAM page used as the green video plane.
  mov al, 5
  out 10h, al

  ; Start with one live pixel in the centre of the first scanline.
  mov byte [es:4*(COLS/2)],80h
  xor si,si
  ; Fill the 200 visible lines plus one hidden four-line VRAM row
  ; before performing the first software scroll.
  mov bp,LINES+3
  jmp draw.rebuild_rule

draw:
  ; In Sanyo VRAM four scanlines are interleaved. Adjacent scanlines
  ; are one byte apart; after the fourth, advance to the next row.
  mov di,si
  inc di
  test bp,3
  jnz .address_step_ready
  add di,ROW_BYTES-4
.address_step_ready:
  and di,3fffh
  xor dx,dx
  mov cx,COLS
.pixel_bytes:
  ; Read one byte from the current line and the byte to its right.
  ; SI advances by four because horizontally adjacent bytes are
  ; separated by the four interleaved scanlines.
  mov al,[es:si]
  add si,4
  and si,3fffh
  xor bl,bl
  cmp cx,1
  je .right_ready
  mov bl,[es:si]
.right_ready:
  ; Shift neighbouring bytes through the carries to construct three
  ; bit lanes containing the left, centre and right CA neighbours.
  mov ah,al
  shr dl,1
  rcr al,1
  mov dl,ah
  shl bl,1
  rcl ah,1

  ; Combine the eight neighbourhood masks selected by the active rule.
  ; a0..a7 contain either 00h or FFh, avoiding a per-pixel rule lookup.
  mov bl,[mask+0]
  mov dh,ah
  and dh,[mask+1]
  xor bl,dh
  mov dh,dl
  and dh,[mask+2]
  xor bl,dh
  mov dh,dl
  and dh,ah
  and dh,[mask+3]
  xor bl,dh
  mov dh,al
  and dh,ah
  and dh,[mask+5]
  xor bl,dh
  mov dh,al
  and dh,dl
  and dh,[mask+6]
  xor bl,dh
  mov dh,al
  and dh,[mask+4]
  xor bl,dh
  mov dh,al
  and dh,dl
  and dh,ah
  and dh,[mask+7]
  xor bl,dh

  mov [es:di],bl
  add di,4
  and di,3fffh
  loop .pixel_bytes

  ; DI now points one row beyond the generated line. Move it back to
  ; the start of that line so it becomes the source for the next one.
  sub di,ROW_BYTES
  and di,3fffh
  mov si,di
  dec bp
  jnz .line_done

  ; Scroll four physical scanlines. The newly generated hidden row is
  ; included at the end of this overlapping copy and lands at the bottom.
  mov bp,4
  push ds
  push es
  pop ds
  xor di,di
  mov si,ROW_BYTES
  mov cx,PLANE_BYTES/2
  cld
  rep movsw
  pop ds
  mov si,PLANE_BYTES-ROW_BYTES+3

.line_done:
  ; Keep each rule for a pseudo-random interval of 1..200 CA lines.
  dec byte [line_count]
  jnz draw
  ; An 8-bit Galois LFSR is stored directly in the immediate operand
  ; of the MOV at .rebuild_rule, saving a separate rule variable.
  mov al,[draw.rebuild_rule+1]
  shr al,1
  jnc .random_ready
  xor al,0b8h
.random_ready:
  mov [draw.rebuild_rule+1],al
  aam 200
  inc al
  mov [line_count],al
.rebuild_rule:
  mov al,4
  ; Reverse the eight rule bits so they match the a0..a7 lookup order.
  mov ah,al
  shl ah,1
  and ah,0aah
  xor al,ah
  mov ah,al
  shl ah,1
  shl ah,1
  and ah,0cch
  xor al,ah
  mov ah,al
  mov cl,4
  shl ah,cl
  and ah,0f0h
  xor al,ah
  mov di,a
  mov cx,8
.expand_rule:
  ; Expand each rule bit to a full-byte mask: 0 -> 00h, 1 -> FFh.
  shr al,1
  sbb ah,ah
  mov [di],ah
  inc di
  loop .expand_rule
  jmp draw

line_count db 200
mask: times 8 db 0
```
