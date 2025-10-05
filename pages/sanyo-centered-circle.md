# Sanyo - centered circle
```nasm
org 0
cpu 8086
COLS equ 72
CENTER EQU 25*288+144

setup:
  push cs
  pop ds
  mov ax,0x0800  ; GREEN
  mov es,ax

  call circle

  hlt


scale_bx:
; in : BL, BH ∈ [-127..127]
; out: BL, BH ∈ [-100..100]
; trashes: AX
; preserves: CX
  push    cx

  ; BL -> [-100..100]
  mov     al, bl
  mov     cl, 100
  imul    cl            ; AX = BL * 100 (signed)
  mov     cl, 127
  idiv    cl            ; AL = (BL*100)/127 (truncate toward 0)
  mov     bl, al

  ; BH -> [-100..100]
  mov     al, bh
  mov     cl, 100
  imul    cl            ; AX = BH * 100
  mov     cl, 127
  idiv    cl            ; AL = (BH*100)/127
  mov     bh, al

  pop     cx

  ret

circle:
  mov cx,255
.lp:
  push cx
  mov al,cl
  call sin
  mov bh,al

  mov al,cl
  call cos
  mov bl,al

  call scale_bx ; from -127..127 to -100..100
  call dot

  pop cx
  loop .lp
  ret

dot:
  call calc_di_dl_for_pixel
  or [es:di + CENTER],dl  ; set pixel
  ret


;;;;; DEZE WERKT OOK MET NEGATIEVE GETALLEN DUS -127..127

;;; ZOU IK HIER OOK EEN LOOKUP TABLE VAN KUNNEN MAKEN?
; bijv BL=x en BH=y en dan een LUT van BX naar DI
; elke DI zal 8x voorkomen omdat telkens 8 pixels dezelfde DI hebben. Dat maakt de LUT wel behoorlijk groot.. maar dat is niet erg.
; BL=-127..127, BH=-127..127 (BH zal nooit onder -100 of boven 100 mogen komen want dat valt buiten beeld)
; en BL heeft dus een schaling van 2 vanwege de aspect ratio.

; en dan ook de sinus en cosinus in een lookup  ; met input 0..255 en output -127,127

; de output van die sinus wil je dan nog wel schalen naar -100..100 voordat je er een coordinaat van maakt.


; COLS EQU 72
; in : BL = x (signed 8-bit), BH = y (signed 8-bit)
; out: DI = (y\4)*(4*COLS) + (y%4) + ((2x)\8)*4   (relatief t.o.v. CENTER)
;      DL = 2^(7 - ((2x) % 8))
; preserves: CX
; trashes  : AX, DX, SI

calc_di_dl_for_pixel:
  push  cx

  ; ----- y uit BH: qy=floor(y/4), ry=y&3
  mov   al, bh
  cbw
  mov   si, ax              ; SI = y
  mov   di, ax
  and   di, 3               ; DI = ry
  mov   ax, si
  sar   ax, 1
  sar   ax, 1               ; AX = qy
  mov   si, ax              ; SI = qy

  ; DI += qy*(4*COLS) = qy*288 = (qy<<5) + (qy<<8)
  mov   ax, si
  mov   cl, 5
  shl   ax, cl
  add   di, ax
  mov   ax, si
  mov   cl, 8
  shl   ax, cl
  add   di, ax

  ; ----- x uit BL met aspectratio: x' = 2*x
  mov   al, bl
  cbw                        ; AX = x (signed)
  mov   si, ax               ; SI = x

  ; qx2 = floor(x'/8) = floor((2*x)/8) = floor(x/4)
  sar   ax, 1
  sar   ax, 1                ; AX = qx2

  ; rx2 = x' - qx2*8
  mov   dx, si
  shl   dx, 1                ; DX = x' = 2*x
  mov   cx, ax
  shl   cx, 1
  shl   cx, 1
  shl   cx, 1                ; CX = qx2*8
  sub   dx, cx               ; DX = rx2 (0..7)
  mov   cl, dl               ; CL = rx2
  and   cl, 7

  ; DL = bitmask 1<<(7-rx2)
  mov   dl, 128
  shr   dl, cl

  ; DI += (qx2*4)
  shl   ax, 1
  shl   ax, 1                ; AX = qx2*4
  add   di, ax

  pop   cx
  ret

; in:  AL = hoek 0..255
; uit: AL = sin(hoek) in bereik -128..127 (feitelijk -126..126 op basis van tabel)
; gebruikt: CL, DL, BL, BX

cos:
    add  al,64
sin:
    push cx
    push bx
    mov  cl,al  ; deze ontbrak.......
    mov  al,255
    sub  al,cl   ; 0..255 ipv 255..0
    mov  dl,al          ; dl = hoek
    mov  cl,6
    shr  dl,cl          ; q = hoek>>6 (0..3)
    and  al,63          ; i = hoek & 63 (0..63 binnen kwart)
    test dl,1           ; odd quadrants (1 of 3)?
    jz   .no_reflect
    mov  bl,63
    sub  bl,al          ; i = 63 - i
    mov  al,bl
.no_reflect:
    mov  bx, qsin
    xlat                 ; al = amplitude (0..~126)
    test dl,2           ; kwadranten 2 en 3 -> negatief
    jz   .done
    neg  al
.done:
    pop  bx
    pop  cx
    ret

qsin: db 0,3,6,9,12,15,18,21,24,27,30,33,36,39,42,45,48,51,54,57,59,62,65,67,70,73,75,78,80,82,85,87,89,91,94,96,98,100,102,103,105,107,108,110,112,113,114,116,117,118,119,120,121,122,123,123,124,125,125,126,126,126,126,126


%assign num $-$$
%warning total num

times (180*1024)-num db  0                 ; fill up with zeros until file size=180k
```
