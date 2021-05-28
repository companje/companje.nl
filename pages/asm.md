---
title: Assembler
---
* https://www.onlinedisassembler.com/odaweb/
* JetBrains dotPeek (.NET decompiler)

## compile a com file on osx and run in dosbox
```bash
nasm -f bin first.asm -o first.com
```

## disassemble com file
"To disassemble a DOS .COM file correctly, a disassembler must assume that the first instruction in the file is loaded at address 0x100, rather than at zero."
```bash
ndisasm -o100h filename.com
```

## hello world
from: https://github.com/nanochess
```asm
  org 0x100
start:
  mov bx,string
repeat:
  mov al,[bx]
  test al,al
  je end
  push bx
  mov ah,0x0e
  mov bx,0x000f
  int 0x10
  pop bx
  inc bx
  jmp repeat

end:
  int 0x20

string:
  db "Hello world!",0 
```

