# Sanyo MBC-55x Interrupts

I/O Interrupt Controller 8259A
from the manual:
```
8259A control interrupts. 8259A has eight levels of
interrupt request pins. Interrupt requests arriving at
the respective pins are detected when the signal goes
high or low.

Pin Vector:

IRO 248(F8) Interval timer (established to 10 msec in period)
IR1 249(F9) Interval timer 1 (clock counter)
IR2 250(FA) USART Ready (RS232C)
IR3 251(FB) USART Rx Ready (keyboard)
IR4 252(FC) Printer Ready
IR5 253(FD) Floppy disk controller
IR6 254(FE) 8087 digital data processor
IR7 255(FF) User interrupt (external bus IR7) (*1) - *1 Negative logic


The priorities of pins are fixed to the order of IR0
(high) to IR7 (low).
Operation Command Word (OCW) 1 changes the priorities.
Of IRO to IR7, only IR1 to IR3 are associated with BIOS.
Pins other than IR1 to IR3 are masked by the interrupt
mask register (MR) in 8259. The IMR can be read/written
by performing Read/Write command on I/O address 02.
If IR2 or IR3 is masked, response may be delayed
because fetching results in polling by software.
Within BIOS, only the I/O routine related to the floppy
disk is disabled. No interrupts are accepted while
this routine being executed.
Of IR0 to IR7, interrupts other than IR1 k to IR3 and
IR5 can be used by a transient program.
To use interrupts, write the segment and offset the
interrupt servicing routine and reset the corresponding
IMR bits. If the program has terminated, restore the
rewritten vector, set the reset IMR bits, then return
control to DOS. IF the vector and the IMR are not
restored, an overrun results.
NOTE: to use interrupt, master the operations of 8088
and 8259A beforehand.
```

## interrupt handlers
```nasm
int_0x00: ; 0320:1576 Division Error (Division by zero)
  push si
  push ax
  mov si,0x1597
  call 0x320:0x12a4
  pop ax
  pop si
  int 0x23
  iret

int_0x01: ; 0040:0511  interrupt_trap_halt (Single step)
int_0x02: ; 0040:0511  interrupt_trap_halt (Non-maskable interrupt)
int_0x03: ; 0040:0511  interrupt_trap_halt (For one-byte INT (INT 3)
int_0x04: ; 0040:0511  interrupt_trap_halt (Signed overflow)

; 0x05 to 0x1F : Reserved for Intel (SOFT Interrupt vector FUNCTION)

int_0x05: ; 0040:0511  interrupt_trap_halt
int_0x06: ; 0040:0511  interrupt_trap_halt
int_0x07: ; 0040:0511  interrupt_trap_halt
int_0x08: ; 0040:0511  interrupt_trap_halt
int_0x09: ; 0040:0511  interrupt_trap_halt
int_0x0a: ; 0040:0511  interrupt_trap_halt
int_0x0b: ; 0040:0511  interrupt_trap_halt
int_0x0c: ; 0040:0511  interrupt_trap_halt
int_0x0d: ; 0040:0511  interrupt_trap_halt
int_0x0e: ; 0040:0511  interrupt_trap_halt
int_0x0f: ; 0040:0511  interrupt_trap_halt

interrupt_trap_halt:
  cli
  mov ax,cs
  mov ds,ax
  mov dx,0x40:0x72  ; \r\ninterrupt trap halt\r\n$
  mov ah,9
  int 21h
  hlt

int_0x10: ; 0040:0BED
  sti
  cld
  push bp
  push es
  push ds
  push dx
  push cx
  push bx
  push si
  push di
  push ax
  cs mov [0x793],ah
  cmp ah,0x10
  jc 0xc0a
  sub ah,0x70
  jc 0xc2e
  add ah,0x10
  mov al,ah
  xor ah,ah
  add ax,ax
  mov si,ax
  cmp ax,0x28
  jc 0xc1b
  pop ax
  db 0xeb, 0x14   ; jmp _0xc2e
  nop 
  cs mov ax,[0x73c]
  mov dx,ax
  mov es,ax
  pop ax
  cs mov ah,[0x790]
  jmp [cs:si+0xbc5]
_0xc2e:
  pop di
  pop si
  pop bx
  pop cx
  pop dx
  pop ds
  pop es
  pop bp
  iret

int_0x11: ; 0040:1C40
  sti
  mov ax,0x427d
  iret

int_0x12: ; 0040:1C45
  sti
  push cx
  cs mov ax,[0x36]
  mov cl,6
  shr ax,cl
  pop cx
  iret

int_0x13: ; 0040:17C3
  sti
  push ds
  push bp
  push si
  push di
  push es
  push dx
  push cx
  push bx
  push ax
  call 0x1859
  call 0x17e8
  pop ax
  cs mov ah,[0x16c2]
  cmp ah,1
  cmc
  pop bx
  pop cx
  pop dx
  pop es
  pop di
  pop si
  pop bp
  pop ds
  db 0xca, 0x02, 0x00 ; ret l,0002

int_0x14: ; 0040:1AD4
  sti
  push ds
  push dx
  push si
  push di
  push bx
  push cx
  or ah,ah
  jz 0x1afb
  dec ah
  jnz 0x1ae6
  jmp 0x1b6f
  dec ah
  jnz 0x1aed
  jmp 0x1b91
  dec ah
  jnz 0x1af4
  jmp 0x1bb0
  pop cx
  pop bx
  pop di
  pop si
  pop dx
  pop ds
  iret

int_0x15: ; 0040:0569
  iret

int_0x16: ; 0040:1C51
  sti
  push bx
  or ah,ah
  jz 0x1c61
  dec ah
  jz 0x1c86
  dec ah
  jz 0x1ca1
  pop bx
  iret

0x1c61:
  sti
  call 0x06a2
  cs mov al,[0x057a]
  cs cmp al,[0x0579]
  jz 0x1c61
  xor bh,bh
  mov bl,al
  cs mov ax,[bx+0x057b]
  add bl,2
  and bl,0x0f
  cs mov [0x57a],bl
  pop bx
  iret

0x1c86:
  sti
  call 0x05a2
  xor bh,bh
  cs mov al,[0x057a]
  cs cmp al,[0579]
  mov bl,al
  mov ax,[bx+0x057b]
  sti
  pop bx
  db 0xca,0x02,0x00 ; ret L,0002
  xor al,al
  pop bx
  iret

0x1ca1:
  xor al,al
  pop bx
  iret

0x05a2:
  mov [0x5f],ss
  cs mov [0x61],sp
  mov sp,cs
  mov ss,sp
  mov sp,0x1b6f
  push ax
  push bx
  push dx
  push ds
  call 0x059c
  jz 0x05da
  test al,8
  cs lds bx,[0x594]
  jz 0x059c
  cs lds bx,[0x598]
  xor ax,ax
  in byte 0x38
  shl ax
  add bx,ax
  mov al,0x35
  out byte 0x3a
  mov ax,[bx]
  call 0x05e9
  pop ds
  pop dx
  pop bx
  pop ax
  cs mov ss,[0x5f]
  cs mov sp,[0x61]
  iret

int_0x17: ; 0040:1BD6
  sti
  push ds
  push dx
  push si
  push cx
  push bx
  or ah,ah
  jz 0x1bee
  dec ah
  jz 0x1c1b
  dec ah
  jz 0x1c1b
  pop bx
  pop cx
  pop si
  pop dx
  pop ds
  iret

int_0x18: ; 0040:0569  
  iret

int_0x19: ; 0040:0569  
  iret

int_0x1a: ; 0040:1CA5
  sti
  or ah,ah
  jz 0x1cb0
  dec ah
  jz 0x1cc6
  sti
  iret

int_0x1b: ; 0040:0569  
  iret

int_0x1c: ; 0040:1CD8  
  iret

int_0x1d: ; 0040:0511  interrupt_trap_halt
int_0x1e: ; 0040:0511  interrupt_trap_halt
int_0x1f: ; 0040:0511  interrupt_trap_halt

; 0x20 to 0x3F - Reserved for MS-DOS

int_0x20: ; 0320:004b
  mov ah,0
  jmp 0x6d ; jmps 6d

int_0x21: ; 0320:004f
  cmp ah,0x2e
  jbe 0x6d
  mov al,0
  iret

int_0x22: ; 05af:01e3
  mov dx,0x16a5
  jmp 0x1eb ; jmps 01eb

int_0x23: ; 05af:01e8 
  mov dx,0x16c2   ; \r\n
  mov ax,cs
  mov ds,ax
  mov ss,ax
  mov sp,0x184e
  mov ah,9
  int 0x21

int_0x24: ; 0569:0196
  sti
  push ds
  push cs
  pop ds
  push dx
  call 0x226
  pop dx
  add al,0x41
  mov [0x326],al
  test ah,0x80
  jnz 0x0207
  mov si,0x0307
  test ah,1
  jz 0x01b4
  mov si,0x30c
  lodsw
  mov [0x318],ax
  lodsw
  mov [0x31a],ax
  and di,0xff
  cmp di,+0x0c
  jbe 0x1c8
  mov di,0xc
  mov di,[di+0x2b5] ; write project$Not ready$Data$Seek%Sector not found%Write fault$Disk%read%write$ error writing drive A\r\n$Abort,Retry, Ignore?$\n\rFile allocation table bad,$\r\n .....
  xchg di,dx
  mov ah,9
  int 0x21

int_0x25: ; 0040:0015
  jmp 0x215

int_0x26: ; 0040:0018
  jmp 0x21b

int_0x27: ; 0569:0182
  add dx,+0x0f
  mov  l,4
  shr dx,cl
  cs add [0x0103],dx
  xor ax,ax
  mov ds,ax
  db 0xff,0x2e,0x80,0x00 ; jmp L,[0080]


int_0x28: ; 0040:0511  interrupt_trap_halt
int_0x29: ; 0040:0511  interrupt_trap_halt
int_0x2a: ; 0040:0511  interrupt_trap_halt

int_0x2b: ; 0569:0182  see int 0x27

int_0x2c: ; 0040:0511  interrupt_trap_halt
int_0x2d: ; 0040:0511  interrupt_trap_halt
int_0x2e: ; 0040:0511  interrupt_trap_halt
int_0x2f: ; 0040:0511  interrupt_trap_halt

int_0x30: ; 2000:57ea  ?????????

int_0x31: ; 0040:0503
  and al,0x75
  add ax,0x043a
  jnz 0x0510
  inc si
  call 0x06a5
  jmp 0x0501

0x0501:
  lodsb
  cmp al,24
  jnz 0x50b
  cmp al,[si]
  jnz 0x0510
  inc si
  call 0x07a5
  jmp 0501

int_0x32: ; 0040:0511  interrupt_trap_halt
int_0x33: ; 0040:0511  interrupt_trap_halt
int_0x34: ; 0040:0511  interrupt_trap_halt
int_0x35: ; 0040:0511  interrupt_trap_halt
int_0x36: ; 0040:0511  interrupt_trap_halt
int_0x37: ; 0040:0511  interrupt_trap_halt
int_0x38: ; 0040:0511  interrupt_trap_halt
int_0x39: ; 0040:0511  interrupt_trap_halt
int_0x3a: ; 0040:0511  interrupt_trap_halt
int_0x3b: ; 0040:0511  interrupt_trap_halt
int_0x3c: ; 0040:0511  interrupt_trap_halt
int_0x3d: ; 0040:0511  interrupt_trap_halt
int_0x3e: ; 0040:0511  interrupt_trap_halt
int_0x3f: ; 0040:0511  interrupt_trap_halt
int_0x40: ; 0040:0511  interrupt_trap_halt
int_0x41: ; 0040:0511  interrupt_trap_halt
int_0x42: ; 0040:0511  interrupt_trap_halt
int_0x43: ; 0040:0511  interrupt_trap_halt
int_0x44: ; 0040:0511  interrupt_trap_halt
int_0x45: ; 0040:0511  interrupt_trap_halt
int_0x46: ; 0040:0511  interrupt_trap_halt
int_0x47: ; 0040:0511  interrupt_trap_halt
int_0x48: ; 0040:0511  interrupt_trap_halt
int_0x49: ; 0040:0511  interrupt_trap_halt
int_0x4a: ; 0040:0511  interrupt_trap_halt
int_0x4b: ; 0040:0511  interrupt_trap_halt
int_0x4c: ; 0040:0511  interrupt_trap_halt
int_0x4d: ; 0040:0511  interrupt_trap_halt
int_0x4e: ; 0040:0511  interrupt_trap_halt
int_0x4f: ; 0040:0511  interrupt_trap_halt
int_0x50: ; 0040:0511  interrupt_trap_halt
int_0x51: ; 0040:0511  interrupt_trap_halt
int_0x52: ; 0040:0511  interrupt_trap_halt
int_0x53: ; 0040:0511  interrupt_trap_halt
int_0x54: ; 0040:0511  interrupt_trap_halt
int_0x55: ; 0040:0511  interrupt_trap_halt
int_0x56: ; 0040:0511  interrupt_trap_halt
int_0x57: ; 0040:0511  interrupt_trap_halt
int_0x58: ; 0040:0511  interrupt_trap_halt
int_0x59: ; 0040:0511  interrupt_trap_halt
int_0x5a: ; 0040:0511  interrupt_trap_halt
int_0x5b: ; 0040:0511  interrupt_trap_halt
int_0x5c: ; 0040:0511  interrupt_trap_halt
int_0x5d: ; 0040:0511  interrupt_trap_halt
int_0x5e: ; 0040:0511  interrupt_trap_halt
int_0x5f: ; 0040:0511  interrupt_trap_halt
int_0x60: ; 0040:0511  interrupt_trap_halt
int_0x61: ; 0040:0511  interrupt_trap_halt
int_0x62: ; 0040:0511  interrupt_trap_halt
int_0x63: ; 0040:0511  interrupt_trap_halt
int_0x64: ; 0040:0511  interrupt_trap_halt
int_0x65: ; 0040:0511  interrupt_trap_halt
int_0x66: ; 0040:0511  interrupt_trap_halt
int_0x67: ; 0040:0511  interrupt_trap_halt
int_0x68: ; 0040:0511  interrupt_trap_halt
int_0x69: ; 0040:0511  interrupt_trap_halt
int_0x6a: ; 0040:0511  interrupt_trap_halt
int_0x6b: ; 0040:0511  interrupt_trap_halt
int_0x6c: ; 0040:0511  interrupt_trap_halt
int_0x6d: ; 0040:0511  interrupt_trap_halt
int_0x6e: ; 0040:0511  interrupt_trap_halt
int_0x6f: ; 0040:0511  interrupt_trap_halt
int_0x70: ; 0040:0511  interrupt_trap_halt
int_0x71: ; 0040:0511  interrupt_trap_halt
int_0x72: ; 0040:0511  interrupt_trap_halt
int_0x73: ; 0040:0511  interrupt_trap_halt
int_0x74: ; 0040:0511  interrupt_trap_halt
int_0x75: ; 0040:0511  interrupt_trap_halt
int_0x76: ; 0040:0511  interrupt_trap_halt
int_0x77: ; 0040:0511  interrupt_trap_halt
int_0x78: ; 0040:0511  interrupt_trap_halt
int_0x79: ; 0040:0511  interrupt_trap_halt
int_0x7a: ; 0040:0511  interrupt_trap_halt
int_0x7b: ; 0040:0511  interrupt_trap_halt
int_0x7c: ; 0040:0511  interrupt_trap_halt
int_0x7d: ; 0040:0511  interrupt_trap_halt
int_0x7e: ; 0040:0511  interrupt_trap_halt
int_0x7f: ; 0040:0511  interrupt_trap_halt
int_0x80: ; 0040:0511  interrupt_trap_halt
int_0x81: ; 0040:0511  interrupt_trap_halt
int_0x82: ; 0040:0511  interrupt_trap_halt
int_0x83: ; 0040:0511  interrupt_trap_halt
int_0x84: ; 0040:0511  interrupt_trap_halt
int_0x85: ; 0040:0511  interrupt_trap_halt
int_0x86: ; 0040:0511  interrupt_trap_halt
int_0x87: ; 0040:0511  interrupt_trap_halt
int_0x88: ; 0040:0511  interrupt_trap_halt
int_0x89: ; 0040:0511  interrupt_trap_halt
int_0x8a: ; 0040:0511  interrupt_trap_halt
int_0x8b: ; 0040:0511  interrupt_trap_halt
int_0x8c: ; 0040:0511  interrupt_trap_halt
int_0x8d: ; 0040:0511  interrupt_trap_halt
int_0x8e: ; 0040:0511  interrupt_trap_halt
int_0x8f: ; 0040:0511  interrupt_trap_halt
int_0x90: ; 0040:0511  interrupt_trap_halt
int_0x91: ; 0040:0511  interrupt_trap_halt
int_0x92: ; 0040:0511  interrupt_trap_halt
int_0x93: ; 0040:0511  interrupt_trap_halt
int_0x94: ; 0040:0511  interrupt_trap_halt
int_0x95: ; 0040:0511  interrupt_trap_halt
int_0x96: ; 0040:0511  interrupt_trap_halt
int_0x97: ; 0040:0511  interrupt_trap_halt
int_0x98: ; 0040:0511  interrupt_trap_halt
int_0x99: ; 0040:0511  interrupt_trap_halt
int_0x9a: ; 0040:0511  interrupt_trap_halt
int_0x9b: ; 0040:0511  interrupt_trap_halt
int_0x9c: ; 0040:0511  interrupt_trap_halt
int_0x9d: ; 0040:0511  interrupt_trap_halt
int_0x9e: ; 0040:0511  interrupt_trap_halt
int_0x9f: ; 0040:0511  interrupt_trap_halt
int_0xa0: ; 0040:0511  interrupt_trap_halt
int_0xa1: ; 0040:0511  interrupt_trap_halt
int_0xa2: ; 0040:0511  interrupt_trap_halt
int_0xa3: ; 0040:0511  interrupt_trap_halt
int_0xa4: ; 0040:0511  interrupt_trap_halt
int_0xa5: ; 0040:0511  interrupt_trap_halt
int_0xa6: ; 0040:0511  interrupt_trap_halt
int_0xa7: ; 0040:0511  interrupt_trap_halt
int_0xa8: ; 0040:0511  interrupt_trap_halt
int_0xa9: ; 0040:0511  interrupt_trap_halt
int_0xaa: ; 0040:0511  interrupt_trap_halt
int_0xab: ; 0040:0511  interrupt_trap_halt
int_0xac: ; 0040:0511  interrupt_trap_halt
int_0xad: ; 0040:0511  interrupt_trap_halt
int_0xae: ; 0040:0511  interrupt_trap_halt
int_0xaf: ; 0040:0511  interrupt_trap_halt
int_0xb0: ; 0040:0511  interrupt_trap_halt
int_0xb1: ; 0040:0511  interrupt_trap_halt
int_0xb2: ; 0040:0511  interrupt_trap_halt
int_0xb3: ; 0040:0511  interrupt_trap_halt
int_0xb4: ; 0040:0511  interrupt_trap_halt
int_0xb5: ; 0040:0511  interrupt_trap_halt
int_0xb6: ; 0040:0511  interrupt_trap_halt
int_0xb7: ; 0040:0511  interrupt_trap_halt
int_0xb8: ; 0040:0511  interrupt_trap_halt
int_0xb9: ; 0040:0511  interrupt_trap_halt
int_0xba: ; 0040:0511  interrupt_trap_halt
int_0xbb: ; 0040:0511  interrupt_trap_halt
int_0xbc: ; 0040:0511  interrupt_trap_halt
int_0xbd: ; 0040:0511  interrupt_trap_halt
int_0xbe: ; 0040:0511  interrupt_trap_halt
int_0xbf: ; 0040:0511  interrupt_trap_halt
int_0xc0: ; 0040:0511  interrupt_trap_halt
int_0xc1: ; 0040:0511  interrupt_trap_halt
int_0xc2: ; 0040:0511  interrupt_trap_halt
int_0xc3: ; 0040:0511  interrupt_trap_halt
int_0xc4: ; 0040:0511  interrupt_trap_halt
int_0xc5: ; 0040:0511  interrupt_trap_halt
int_0xc6: ; 0040:0511  interrupt_trap_halt
int_0xc7: ; 0040:0511  interrupt_trap_halt
int_0xc8: ; 0040:0511  interrupt_trap_halt
int_0xc9: ; 0040:0511  interrupt_trap_halt
int_0xca: ; 0040:0511  interrupt_trap_halt
int_0xcb: ; 0040:0511  interrupt_trap_halt
int_0xcc: ; 0040:0511  interrupt_trap_halt
int_0xcd: ; 0040:0511  interrupt_trap_halt
int_0xce: ; 0040:0511  interrupt_trap_halt
int_0xcf: ; 0040:0511  interrupt_trap_halt
int_0xd0: ; 0040:0511  interrupt_trap_halt
int_0xd1: ; 0040:0511  interrupt_trap_halt
int_0xd2: ; 0040:0511  interrupt_trap_halt
int_0xd3: ; 0040:0511  interrupt_trap_halt
int_0xd4: ; 0040:0511  interrupt_trap_halt
int_0xd5: ; 0040:0511  interrupt_trap_halt
int_0xd6: ; 0040:0511  interrupt_trap_halt
int_0xd7: ; 0040:0511  interrupt_trap_halt
int_0xd8: ; 0040:0511  interrupt_trap_halt
int_0xd9: ; 0040:0511  interrupt_trap_halt
int_0xda: ; 0040:0511  interrupt_trap_halt
int_0xdb: ; 0040:0511  interrupt_trap_halt
int_0xdc: ; 0040:0511  interrupt_trap_halt
int_0xdd: ; 0040:0511  interrupt_trap_halt
int_0xde: ; 0040:0511  interrupt_trap_halt
int_0xdf: ; 0040:0511  interrupt_trap_halt
int_0xe0: ; 0040:0511  interrupt_trap_halt
int_0xe1: ; 0040:0511  interrupt_trap_halt
int_0xe2: ; 0040:0511  interrupt_trap_halt
int_0xe3: ; 0040:0511  interrupt_trap_halt
int_0xe4: ; 0040:0511  interrupt_trap_halt
int_0xe5: ; 0040:0511  interrupt_trap_halt
int_0xe6: ; 0040:0511  interrupt_trap_halt
int_0xe7: ; 0040:0511  interrupt_trap_halt
int_0xe8: ; 0040:0511  interrupt_trap_halt
int_0xe9: ; 0040:0511  interrupt_trap_halt
int_0xea: ; 0040:0511  interrupt_trap_halt
int_0xeb: ; 0040:0511  interrupt_trap_halt
int_0xec: ; 0040:0511  interrupt_trap_halt
int_0xed: ; 0040:0511  interrupt_trap_halt
int_0xee: ; 0040:0511  interrupt_trap_halt
int_0xef: ; 0040:0511  interrupt_trap_halt
int_0xf0: ; 0040:0511  interrupt_trap_halt
int_0xf1: ; 0040:0511  interrupt_trap_halt
int_0xf2: ; 0040:0511  interrupt_trap_halt
int_0xf3: ; 0040:0511  interrupt_trap_halt
int_0xf4: ; 0040:0511  interrupt_trap_halt
int_0xf5: ; 0040:0511  interrupt_trap_halt
int_0xf6: ; 0040:0511  interrupt_trap_halt
int_0xf7: ; 0040:0511  interrupt_trap_halt

int_0xf8: ; 0040:051e  8253 PIT counter 0
  sti
  push ds
  push ax
  push dx
  push cs
  pop ds
  cs inc byte [0x71]
  cs cmp byte [0x71],5
  jc 0x566
  cs mov byte [0x71],0
  cs inc word [0x6c]
  jnz 0x0543
  cs inc word [0x6e]
  cmp word [0x6e],+0x18
  jc 0x564
  cs inc word [0x63]
  cs mov word [0x6e],0
  cs mov word [0x6c],0
  cs mov byte [0x70],1
  int 0x1c
  pop dx
  pop ax
  pop ds
  iret

int_0xf9: ; 0040:0569   8253 PIT counter 1
  iret

int_0xfa: ; 0040:1a0f   8251 USART Ready (option RS-232C)
  cs mov [0x5f],ss
  cs mov [0x61],sp
  mov sp,cs
  mov ss,sp
  mov sp,0x16b
  push ax
  push bx
  in byte 0x2a
  and al,2
  jz 0x1a3d
  in byte 0x28
  mov bh,0
  cs mov bl,[0x18e1]
  cs mov [bx+0x18e3],al
  inc bl
  cs mov [0x18e1],bl
  call 0x1a4d
  pop bx
  pop ax
  cs mov ss,[0x5f]
  cs mov sp,[0x61]
  iret

int_0xfb: ; 0040:05a1   8251 USART Rx, Ready (Keyboard)
  cs mov [0x5f],ss
  cs mov [0x61],sp
  mov sp,cs
  mov ss,sp
  mov sp,0x16b
  push ax
  push bx
  push dx
  push ds
  call 0x59c
  jz 0x5da
  test al,8
  cs lds bx,[0x0594]
  jz 0x5c9
  cs lds bx,[0x598]
  xor ax,ax
  in byte 0x38
  shl ax
  add bx,ax
  mov al,0x35
  out byte 0x3a
  mov ax,[bx]
  call 0x5e9
  pop ds
  pop dx
  pop bx
  pop ax
  cs mov ss,[0x5f]
  cs mov sp,[0x61]
  iret


int_0xfc: ; 0040:0569   Printer Ready
  iret

int_0xfd: ; 0040:0569   MB8877 floppy disk controller
  iret

int_0xfe: ; 0040:0569   8087 digital data processor
  iret

int_0xff: ; 0040:0569   User interrupt (optional BUS IR7)
  iret
```
