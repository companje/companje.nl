See also: [Sanyo](/sanyo)

# Sanyo MBC-555 ROM bootcode disassembly

```nasm
; Disassembling the Sanyo MBC-555 bootcode in ROM (by hand)
; Rick Companje, The Netherlands, May 2022 + Sept 2024

_FFFF_0000:
  jmp 0xFE00:0x1E00

_FE00_1E00:
  mov al,0x04
  out 0x10,al           ; Video RAM page select
  mov al,0xFF
  mov 0x3a,al           ; keyboard
  mov al,0x30
  out 0x3a,al           ; keyboard
  
  mov ax,0x0800         ; 2k page 0-1 ? absolute address 08000
  mov es,ax
  mov cx,0x4000         ; 16k one page green
  xor ax,ax             ; ax=0
  mov di,ax
  cld                   ; clear direction flag
  repz stosw            ; empty screen 16k zeros

  mov ax,0xF000         ; f000=red, f400=blue
  mov es,ax             ; target segment
  mov cx,0x8000         ; 32k
  xor ax,ax
  mov di,ax             ; target offset
  cld
  repz stosw            ; clear red+blue channels

  mov si,0x1EBC         ; 0x10 bytes CRT code
  mov bx,0
  in al,0x1C            ; parallel port status 8255 PPI.
  and al,0x80           ; bit 7: 0=80 cols, 1=72 cols
  jz 0x1E39

_FE00_1E36:
  mov si,0x1ECC         ; for reading second/other CRT sequence

_FE00_1E39:
  mov al,bl             ; goes from 0 to 0x10
  out 0x30,al           ; CRT address 

  CS:
  mov al,[bx+si+0]      ; si=1ECC
  out 0x32,al           ; al contains current byte of CRT data
  inc bx                ; increase data pointer
  cmp bl,0x10           
  jnz 0x1E39            ; send 0x10 bytes data to CRT chip

_FE00_1E4A:
  mov al,0xD8           
  out 0x08,al           ; floppy command: 0b11011000 (0xD8)
  mov al,0x98           
  out 0x1E,al           ; PPI control: 0b10011000 (0x98) 'strobe'

_FE00_1E52:
  in al,0x08            ; floppy status
  test al,0x01
  jnz 0x1E52            ; loop until bit 1 is high

_FE00_1E58:
  mov al,0x08
  out 0x08,al           ; floppy command 0b00001000
  aam                   ; wait?
  aam                   ; wait?
  aam                   ; wait?
  aam                   ; wait?

_FE00_1E64:
  in al,0x08            ; floppy status dx=0x08
  test al,0x01
  jnz 0x1E64            ; wait until bit 1 is high
  test al,0x80
  jnz 0x1E58            ; wait until bit 7 is high

_FE00_1E6E:
  cld                   ; clear direction flag
  mov al,0x01
  out 0x0C,al           ; floppy set sector bit 1
  mov ax,0x0038
  mov es,ax             ; segment for data from floppy
  mov dx,0x08           ; used to read floppy status
  mov si,0x1EA0         ; used for local jmp later on
  mov di,0
  mov bh,0
  mov ah,0
  mov al,0
  out 0x08,al           ; floppy
  aam
  aam
  aam
  aam

_FE00_1E91:
  in al,dx              ; floppy status dx=0x08
  sar al,1
  jnb 0x1EB2            ; floppy
  jnz 0x1E91            ; floppy

_FE00_1E98:
  in al,dx              ; floppy dx=0x08
  and al,bh             ; bit 2 (?)
  jz 0x1E98

_FE00_1E9D:
  in al,0x0E            ; read data from floppy
  stosb                 ; store in es:di++    

_FE00_1EA0:
  in al,dx              ; floppy dx=0x08
  dec ax
  cmp al,bh             ; bh = 2 ?
  jnz 0x1EB2

_FE00_1EA8:
  in al,0x0E            ; read data from floppy
  stosb                 ; store in es:di++
  in al,dx
  cmp al,bh
  jz 0x1EA8

_FE00_1EB0:
  jmp si                ; jump to 0x1EA0

_FE00_1EB2:
  in al,dx              ; floppy status dx=0x08
  test al,0x1C           
  jnz 0x1E52            ; floppy status

_FE00_1EBC:
  jmp 0x0038:0

_FE00_1ECC_crt_data:
  db 0x65,0x50,0x53,0x48,0x69,0x02,0x64,0x64,0x00,0x03,0,0,0,0,0,0
  db 0x70,0x48,0x55,0x4A,0x41,0x00,0x32,0x38,0x00,0x03,0,0,0,0,0,0
```
