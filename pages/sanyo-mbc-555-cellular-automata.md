# Wolfram Cellular Automata (189 bytes)
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
mask equ setup                 ; Reuse eight setup bytes after they have executed.
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
seed:
  ; mov byte [es:4*(COLS/2)],80h
  xor si,si
  ; Fill the 200 visible lines plus one hidden four-line VRAM row
  ; before performing the first software scroll.
  mov bp,LINES+3

rebuild_rule:
  mov al,4
  ; Treat the rule byte directly as eight algebraic rule masks.
  ; Incrementing it still covers every CA rule, but not in Wolfram order.
  xor di,di
  mov cx,8
.expand_rule:
  ; Expand each coefficient bit to a full-byte mask: 0 -> 00h, 1 -> FFh.
  shr al,1
  sbb ah,ah
  mov [di],ah
  inc di
  loop .expand_rule

draw:
  ; In Sanyo VRAM four scanlines are interleaved. Adjacent scanlines
  ; are one byte apart; after the fourth, advance to the next row.
  mov di,si
  inc di
  mov ax,bp
  and al,3
  jnz .address_step_ready
  add di,ROW_BYTES-4
.address_step_ready:
  cwd                         ; AX is 0..3, therefore this clears DX.
  ; Every path back here leaves CH clear.
  mov cl,COLS
.pixel_bytes:
  ; Read one byte from the current line and the byte to its right.
  ; SI advances by four because horizontally adjacent bytes are
  ; separated by the four interleaved scanlines.
  mov al,[es:si]
  add si,4
  mov bl,[es:si]
  dec cx
  jnz .right_ready
  xor bl,bl
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
  ; The masks contain either 00h or FFh, avoiding a per-pixel rule lookup.
  ; Evaluate the algebraic normal form in nested (Horner) form.
  mov bl,[mask+7]
  and bl,ah
  xor bl,[mask+6]
  and bl,dl
  mov dh,[mask+5]
  and dh,ah
  xor dh,[mask+4]
  xor bl,dh
  and bl,al
  mov dh,[mask+3]
  and dh,ah
  xor dh,[mask+2]
  and dh,dl
  xor bl,dh
  mov dh,[mask+1]
  and dh,ah
  xor dh,[mask+0]
  xor bl,dh

  mov [es:di],bl
  add di,4
  or cx,cx
  jnz .pixel_bytes

  ; DI now points one row beyond the generated line. Move it back to
  ; the start of that line so it becomes the source for the next one.
  sub di,ROW_BYTES
  mov si,di
  dec bp
  jz .scroll

.line_done:
  ; Keep every rule for exactly 200 CA lines.
  dec byte [line_count]
  jnz draw
  ; Store the next coefficient byte in rebuild_rule's immediate operand.
  inc byte [rebuild_rule+1]
  mov byte [line_count],200
  jmp rebuild_rule

.scroll:
  ; Scroll four physical scanlines. The newly generated hidden row is
  ; included at the end of this overlapping copy and lands at the bottom.
  mov bp,4
  xor di,di
  mov si,ROW_BYTES
  mov cx,PLANE_BYTES/2
  ; The boot ROM leaves DF clear, so MOVSW advances through VRAM.
  es rep movsw
  mov si,PLANE_BYTES-ROW_BYTES+3
  jmp .line_done

line_count db 200
```
