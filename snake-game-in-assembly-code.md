---
title: Snake game in assembly code
---
GSnake is een in assembly geschreven grafische Snake variant die ik heb gemaakt in 1996. Heel lang heb ik gedacht dat de originele sourcecode verloren was gegaan maar op 14-11-2007 ontdekte ik in een mapje met de naam ASMLAB een kopie van de source.

<blockquote>SNAKE.COM v1.00  by Rick Companje 17/12/1996
Copyright (C) 1996  TMR Software Productions
The last update was on 06/01/97 at 22:37:07</blockquote>

(toko:gsnake.jpg?550|GSNAKE.COM - Snake in Assembly Code)

== Sourcecode ==
<code asm>
code    Segment para public 'code'
        assume cs:code,ds:code,es:code
        locals                          ; remove if you want to assemble
        jumps                           ; with Masm
        .386                      
        org 100h                  
                                  
YES             equ     1         
NO              equ     0         
                                  
UPDATEINFO      equ     NO
GRAFISCH        equ     YES
RECLAME         equ     YES
TESTING         equ     NO 
REALTIMING      equ     YES             ; 1=same delay on all computers
                                        ; 0=simple delay loop (varies with MHz)
                                        ; 1 = 14 bytes longer program
KaderKleur      equ     9  
bptr            equ     Byte Ptr
wptr            equ     Word Ptr
dptr            equ     Dword Ptr
UP              equ     72 
DOWN            equ     80 
LEFT            equ     75         
RIGHT           equ     77 
INITSNAKELEN    equ     10              ; length of snake at initialization
FOODINCREMENT   equ     50              ; how many pixels to increment body
MAXLENGTH       equ     1600
                             
start:                                  ; entry point
        jmp     RealStart                                                      
; -- DATA --                 
        if reclame           
        einde           db 'SNAKE.COM v1.00  by Rick Companje 17/12/1996',13,10
                        db 'Copyright (C) 1996  TMR Software Productions',13,10,10,36
        endif
        if UPDATEINFO                                                                
        update          db 'The last update was on ',??date,' at ',??time,13,10,10,36
        endif                                                                  
        IF TESTING                                                             
        TestingMode     db 'You were testing SNAKE... last food position was #####',13,10,10
        ELSE                                                                    
        crashedstr      db 'Dead! Your score is ###',10,13,10                   
        ENDIF                                                                   
        if TESTING                                                              
        menustr         db  'T:Test',10,13                                      
        else                                                                    
        menustr         db  'P:Play',10,13                                      
        endif                                                                   
                        db  'Q:End$'                                            
        toonduur        db  10                                                 
        toon            dw  ?
        foodpos         dw  0
        gepakt          dw  0
        dir             db  RIGHT           ; direction, init to RIGHT
        if TESTING        
        TestingWr       db 'TESTING MODE'
        prpos           dw  0            
        endif             
        if Grafisch       
        delaypop        dw 0
        eerstekeer      db 0
        HappyPop        db NO
        endif             
                                   
RealStart:                
        push    0a000h                  ; this is 1 byte shorter than
        pop     gs                      ; mov ax, 0a000h - mov gs, ax
;       mov     eax, 0a000h             ; i have to clear 16 MSB in EAX
;       mov     gs, ax                  ; i just use this instead of [1,2,3]
;        push    40h                     ; points to bios data area
;        pop     fs                      ; for random number at 40:6ch
        mov     ax, ds
        mov     fs, ax        ; de posities van snake in data segment
        xor     eax, eax                ;
                          
IF REALTIMING             
        mov     al, 36h                  
        out     43h, al   
        mov     ax, 30000               ; try lower values for faster moving
        out     40h, al   
        mov     al, ah    
        out     40h, al   
ENDIF                       
;ððððððððððððððððððððð TOON MENU EN VRAAG VOOR INVOER ððððððððððððððððððððððð
;-- menu --               
        mov     dx, offset menustr
menuloop:                 
if Grafisch               
        cmp     [eerstekeer], 0               
        jnz     GeenSchermModeInstellen      
        mov     ax, 13h                      
        int     10h                    
        mov     [eerstekeer], 1
GeenSchermModeInstellen:   
        call    FillScreen 
        call    PlaatsKader
        call    ShowMenu                 
else                      
        mov     ax, 3     
        int     10h                     ; 80x25 textmode
        mov     ah, 9     
        int     21h                     ; show menu
Endif                       
ask:                               
        mov     ah, 0     
        int     16h                     ; wait for keypress
        and     al, 1011111b            ; convert to uppercase (5Fh)
        cmp     al, 'Q'                 ; was key q or Q ?
        je      @exit                   ; if so jump to exit
if TESTING                
        cmp     al, 'T'   
else                      
        cmp     al, 'P'                 ; was key p or P ?
endif                     
        jne     ask                     ; if not jump back to ask
        mov     [Gepakt], 0
;ððððððððððððððððððððððððððððððððð GO VGA ððð 320x200x256 ððððððððððððððððððð
if grafisch               
else                      
        mov     ax, 13h   
        int     10h       
Endif                     
        Call    FillScreen  
        Call    PlaatsKader        
                          
IF TESTING                
        push    ax        
        push    bx        
        push    cx          
        push    dx        
        push    es        
        mov     bx, offset TestingWr
        mov     ah, 0eh   
        mov     cx, 12       ;lengte zin
TestWrLoop:               
        mov     al, [bx]  
        push    bx        
        mov     bl, 12    
        int     10h                                   
        pop     bx        
        inc     bx        
        loop    TestWrLoop
        pop     ax                       
        pop     bx                 
        pop     cx      
        pop     dx      
        pop     es      
else                    
;ðððððððððððððððððððððð PLAATSEN INITIALISEREN ðððððððððððððððððððððððððððððð
        call    PrintScore
endif                   
                        
        mov     bx, 1                   ; snake current length
        mov     cx, INITSNAKELEN
        mov     bp, 60000               ; points to head of snake (was 60000)
                                        ; welke droom??? ikku snappu het!!
; het WORD die op [bp] staat wordt genomen als begin positie
        mov     ax, 20000        ;midden op het scherm
        mov     fs:[bp], ax
                    
        mov     dptr gs:[80*320+150], 010e0e01h
        mov     dptr gs:[81*320+150], 0e0e0e0eh   ; put first sprite
        mov     dptr gs:[82*320+150], 0e0e0e0eh
        mov     dptr gs:[83*320+150], 010e0e01h
        mov     [foodpos], 80*320+150
                            
                            
                      
playloop:                   
; -- draw snake --    
        mov     dx, bx                  ; dx = snake length
        mov     si, bp                  ; si points to head of snake
        mov     di, fs:[si]                ; load snake head position
        cmp     bptr gs:[di], kaderkleur; vergelijk met zijkanten veld
        jz      @snakecrashed
        cmp     bptr gs:[di], 14        ; compare with food byte
        ja      @snakecrashed
        jnz     drawbody                ; if not food, don't increment length
        cmp     cx, MAXLENGTH
        jnl     SlangTeLang      
        add     cx, FOODINCREMENT       ; increment snake length
SlangTeLang:        
        mov     di, [foodpos]
        mov     dptr gs:[di+000], 01010101h  ;  ------------
        mov     dptr gs:[di+320], 01010101h  ;  clear sprite
        mov     dptr gs:[di+640], 01010101h  ;  ____________
        mov     dptr gs:[di+960], 01010101h  ;
;        mov     [Gepakt],1
        inc     [Gepakt]
        mov     [toonduur], 2
        mov     [toon], 5
        call    play        
if Grafisch            
        call    PutHappy
        mov     [happypop],YES
endif                  
                       
IF TESTING                  
ELSE                        
        call    PrintScore
ENDIF                  
                       
PosKiezen:               
        push    fs                 
        push    ax     
        mov     ax, 40h
        mov     fs, ax                   
        pop     ax
        mov     di, wptr fs:[6ch]            ; get random number
        shl     di, 8                        ; di = (byte at 40:6ch) * 256
        pop     fs     
        cmp     di, 60000
        jb      KleinerDan60000      ; if di < 60000 then Goto KleinerDan60000
        sub     di, 6000             ; else di = di - 6000
KleinerDan60000:                     ; KleinerDan60000:
        cmp     di, 4800
        ja      GroterDan4800        ; if di > 4800 then goto GroterDan4800
        add     di, 5000             ; else di = di + 6400
GroterDan4800:                       ; GroterDan4800:
        inc     di            ; 1 optellen, zo raakt ie geen zijkant
                       
IF TESTING                               
        mov     [prpos], 34              
        Call    PrintPOS                 
ENDIF                                    
                                         
        mov     [foodpos], di                ; save positie
        mov     dptr gs:[di+000], 010e0e01h  ; 
        mov     dptr gs:[di+320], 0e0e0e0eh  ;  ------------
        mov     dptr gs:[di+640], 0e0e0e0eh  ;   draw sprite
        mov     dptr gs:[di+960], 010e0e01h  ;  ____________
                                               
drawbody:                                      
        lodsw                           ; load body pixel offset
        cmp     bptr gs:[eax], 14              
        jz      GeenPuntZetten                 
        mov     bptr gs:[eax], 15       ; show snake body point (eax msw=0!)
GeenPuntZetten:                                
        dec     dx                      ; decrement snake length
        jnz     drawbody  
;delay pop                
if Grafisch               
        cmp     [Happypop], NO
        jz      GeenNormPopPlaatsen
        inc     [delaypop] 
        cmp     [delaypop], 120
        jng     GeenNormPopPlaatsen
        Call    PutNormal  
        mov     [delaypop],0
endif                      
GeenNormPopPlaatsen:        
                           
        cmp     bptr gs:[eax], 14
        jz      NietWissen 
        mov     bptr gs:[eax], 1         ; clear snake tale
NietWissen:                
; -- check for keypress -- 
                          
        mov     ah, 1  
        int     16h         
        jz      movesnake   
        mov     ah, 0  
        int     16h                     ; get key
        and     al, 1011111b
        cmp     al, 'Q'
        je      endsnake                ; 'Q' pressed -> exit to menu
        cmp     al, 27 
        je      endsnake
        cmp     al, 'P'
        jnz     GeenPauze
if Grafisch            
        call    Pauze  
else                   
        mov     ah,0   
        int     16h    
endif                  
        jmp     NietWissen
GeenPauze:             
        mov     [dir], ah               ; store new direction
                            
; -- move snake --          
movesnake:             
        mov     dl, [dir]               ; dh is always 0 at this point!
        mov     ax, fs:[bp]                ; load snake head offset
        dec     bp                      ; move body -
        dec     bp                      ; 1 byte shorter than 'sub bp, 2'
;---------------------------------
        cmp     dl, UP                  ; shorter than 'cmp [dir], UP'
        jne     @checkdown2
        sub     ax, 320                 ; move head 1 line up    321
        jmp     @moveheadend
@checkdown2:           
        cmp     dl, DOWN
        jne     @checkleft2
        add     ax, 320                 ; move head 1 line down     319
        jmp     @moveheadend
@checkleft2:                            ; we have either LEFT or RIGHT dir
        cmp     dl, LEFT
        jne     @checkright
        dec     ax          
        jmp     @moveheadend
;        dec     ax    
@checkright:           
;        cmp     dl, RIGHT
;        jnz     III   
        inc     ax     
;        jmp     @MoveHeadEnd
                       
;III:                  
;        jmp     NietWissen
;        inc     bp    
 ;       inc     bp    
;      add     ax, dx                  ; if dir = LEFT = 75 it is 75-76 = -1
;      sub     ax, 76                  ; if dir =RIGHT = 77 it is 77-76 = +1
@MoveHeadEnd:          
;        mov     ah, [dir]    ;tmr
        mov     fs:[bp], ax                ; store new head offset
        cmp     bx, cx                  ; cmp current length with full length
        adc     bx, 0                   ; bx<cx -> increment bx
;                           
 ;       cmp     bx, cx                  ; cmp current length with full length
  ;      jz      @lenequal
   ;     inc     bx                      ; continue increment snake length
@lenequal:             
; -- move end --       
                       
; -- a little delay -- 
                       
IF REALTIMING          
        hlt                             ; correct delay
ELSE                   
        mov     edx, 140000             ; simple delay
@@nops:                
        dec     edx    
        jnz     @@nops 
ENDIF             
                   
        jmp     playloop
@snakecrashed:    
        mov     cx, 260     
toonomlaag:                 
        mov     [toonduur], 1
        mov     [toon], cx
        call    play
        inc     cx
        cmp     cx, 300
        jnz     toonomlaag
                  
IF Grafisch       
ELSE              
IF TESTING        
        mov     dx, offset [TestingMode] - offset [menustr]
ELSE              
        mov     bx, offset menustr-4
        mov     ax,[gepakt]
        mov     cx,3
        call    ConvertNumberToString
        mov     dx, offset [crashedstr] - offset [menustr]
ENDIF              
ENDIF              
                   
endsnake:          
        add     dx, offset [menustr]    ; because dx is always 0 here
        jmp     menuloop    
                   
                            
;--------------------------------------------------------------------------
;==========================================================================
                   
FillScreen PROC    
        mov     cx, 32000-(4*320)       ; 4 and not 8 because fill with words
        mov     ax, 0101h
        mov     di, 9*320
        push    0a000h
        pop     es 
        rep     stosw                                    ; fill screen blue
        ret        
FillScreen ENDP    
                   
PlaatsKader PROC   
        mov     ah, kaderkleur
        mov     al, kaderkleur
        mov     di, 320*10-1
        mov     cx, 190
HerhaalLijnen:              
        stosw               
        add     di, 318              ;zijkanten
        cmp     di, 32000
        loop    HerhaalLijnen
;-------------------------------- bovenkant ---------------------------------
        mov     cx, 320/2
        mov     di, 9*320
        rep     stosw
;----------------------------------------------------------------------------
        mov     di, 320*199
        mov     cx, 320/2             ;onderkant
        rep     stosw
        ret        
PlaatsKader ENDP                   
                   
                   
                   
Play PROC         
; [toon] --> toon           
; [toonduur] --> lengte
        push    dx
        Call    SpeakerOn
        mov     dx, 42h
        push    ax
        mov     ax, [toon]
        xchg    ah, al
        out     dx, al
        xchg    ah, al
        out     dx, al
        pop     ax
delay:            
        hlt       
        dec     [toonduur]
        jnz     Delay
        Call    SpeakerOff
        pop     dx
        ret         
ENDP Play         
                            
SpeakerOn PROC    
         push    ax
         push    cx
         push    dx
         mov     dx, 61h
         in      al, dx
         or      al, 3
         out     dx, al
         pop     ax
         pop     cx
         pop     dx
         ret
ENDP SpeakerOn
        
SpeakerOff PROC
         push    ax
         push    cx
         push    dx 
         mov     dx, 61h
         in      al, dx     
         and     al, 0fch
         out     dx, al
         pop     ax
         pop     cx
         pop     dx
         ret
ENDP SpeakerOff
                  
ConvertNumberToString PROC
        push    si
        mov     si,10
Convertloop:
        sub     dx,dx
        div     si
        add     dl,'0'
        mov     [bx],dl
        dec     bx
        loop    ConvertLoop
        pop     si
        ret
ENDP ConvertNumberToString
        
IF TESTING
PrintPOS PROC
    ;    push    ax
   ;     push    bx
  ;      push    cx
 ;       push    dx
;        push    es
        pusha
        mov     bx, offset menustr-4
        mov     ax,di
        mov     cx, 5
        call    ConvertNumberToString
        mov     dx, [prpos]
        mov     ah, 2
        mov     bh, 0
        int     10h 
        mov     ah, 0eh
        mov     cx, 5
        mov     bx, offset menustr-8
Poswriteloop2:
        mov     al, [bx]
        push    bx
        mov     bl, 15
        int     10h
        pop     bx
        inc     bx
        loop    PosWriteloop2
;       pop     ax
 ;      pop     bx
  ;     pop     cx
   ;    pop     dx
    ;   pop     es
        popa
        ret
PrintPOS ENDP  
else                
PrintScore PROC
        push    ax
        push    bx
        push    cx
        push    dx
        push    es
        mov     bx, offset menustr-4
        mov     ax,[gepakt]
        mov     cx, 3
        call    ConvertNumberToString
        mov     ah, 2
        mov     dx, 004ch
        mov     bh, 0
        int     10h
        mov     ah, 0eh
        mov     cx, 3
        mov     bx, offset menustr-6
Scorewriteloop:
        mov     al, [bx]
        push    bx  
        mov     bl, 15
        int     10h
        pop     bx
        inc     bx
        loop    ScoreWriteloop
        pop     ax
        pop     bx
        pop     cx
        pop     dx
        pop     es
        ret
PrintScore ENDP
ENDIF         
              
if Grafisch   
              
PutNormal PROC
        push    di  
        push    si
        push    cx
        push    bx
        push    0a000h
        pop     es
        mov     di, 640+((320/2)-(27/2))
        mov     si, offset normal
        mov     cx, 27*7
        xor     bx,bx   
PlaatsPicNormal:        
        movsb           
        inc     bh      
        cmp     bh, 27  
        jnz     PlaatsPicNormal
        inc     bl      
        cmp     bl, 7   
        jz      PicNormalKlaar
        xor     bh,bh   
        add     di,320-27
        jmp     PlaatsPicNormal
PicNormalKlaar:         
        pop     bx      
        pop     cx      
        pop     si      
        pop     di      
        ret             
PutNormal ENDP          
                        
PutHappy PROC           
        push    di      
        push    si      
        push    cx      
        push    bx
        push    0a000h
        pop     es
        mov     di, 640+((320/2)-(26/2))
        mov     si, offset happy
        mov     cx, 26*7
        xor     bx,bx
PlaatsPicHappy:     
        movsb  
        inc     bh
        cmp     bh, 26
        jnz     PlaatsPicHappy
        inc     bl
        cmp     bl, 7
        jz      PicHappyKlaar
        xor     bh,bh
        add     di,320-26
        jmp     PlaatsPicHappy
PicHappyKlaar:
        pop     bx      
        pop     cx
        pop     si
        pop     di
        ret
PutHappy ENDP
endif
    
                    
if grafisch    
Pauze PROC   
        pusha


;------------ zet in buffer

        push    0a000h                    ;Video geheugen
        pop     es
        mov     si, 320*(200/2-47/2)+320/2-134/2
        mov     di, offset Buffer
        xor     bx,bx   
PlaatsInBuffer:         
        mov     al,es:[si]
        mov     ds:[di], al
        inc     di      
        inc     si      
        inc     bh      
        cmp     bh, 134 
        jnz     PlaatsInBuffer
        inc     bl
        cmp     bl, 47
        jz      BufferVolGemaakt
        xor     bh,bh
        add     si,320-134
        
        jmp     PlaatsInBuffer
BufferVolGemaakt:
        mov     si, offset picture
        mov     di, 320*(200/2-47/2)+320/2-134/2
        mov     cx, 134*47
        xor     bx,bx
PutPic: 
        lodsb       
        cmp     al, 1
        jz      GeenPixel
        stosb
        dec     di
GeenPixel:              
        inc     di
        inc     bh
        cmp     bh, 134
        jnz     PutPic
        inc     bl
        cmp     bl, 47
        jz      KlaarMetTekenen
        xor     bh,bh
        add     di,320-134
        jmp     PutPic
KlaarMetTekenen:  
        mov     bptr es:[320*84+158], 12
        hlt
        hlt
        hlt         
        hlt
        hlt
        hlt
        mov     bptr es:[320*84+158], 1
        hlt             
        hlt
        hlt    
        hlt  
        hlt
        hlt     
                
        mov     ah, 1                       ;Keypressed
        int     16h
        jz      KlaarMetTekenen
        mov     ah, 0                       ;Readkey (ClrKbdBuf)
        int     16h
        
        mov     si, offset buffer
        mov     di, 320*(200/2-47/2)+320/2-134/2
        mov     cx, 134*47
        xor     bx,bx
PutBufferToScreen:
        movsb
        inc     bh
        cmp     bh, 134 
        jnz     PutBufferToScreen
        inc     bl
        cmp     bl, 47
        jz      KlaarMetBufferTerugPlaatsen
        xor     bh,bh
        add     di,320-134
        jmp     PutBufferToScreen
KlaarMetBufferTerugPlaatsen:
        popa    
        ret     
Pauze ENDP          
                    
ShowMenu PROC       
        call    SetPalette
        mov     si, offset menupic
        push    0a000h                    ;Video geheugen
        pop     es                          
        mov     di, 320*(200/2-114/2)+320/2-174/2+320*10
        mov     cx, 174*114      ;134-20 because enter p or q is ugly
        xor     bx,bx                      
MenuPutPic:                                
        movsb                              
        inc     bh                         
        cmp     bh, 174                    
        jnz     MenuPutPic                 
        inc     bl                         
        cmp     bl, 114                    
        jz      MenuReady                  
        xor     bh,bh                      
        add     di,320-174                 
        jmp     MenuPutPic                 
MenuReady:                                 
        ret                    
ShowMenu ENDP                
                     
SetPalette PROC      
                     
        lea     si,  [palette]
        mov     dx, 3c8h
        xor     al, al                      ;Beginnen met kleur 0
        out     dx, al
        inc     dx   
        mov     cx, 768
        rep     outsb                       ;set palette                    
        ret          
SetPalette ENDP      
endif               
@exit:              
IF REALTIMING                           ; reset PIT
         mov    al, 36h
         out    43h, al
         mov    al, 0
         out    40h, al
         out    40h, al
ENDIF           

         mov    ax, 3                    ;text mode
         int    10h

IF RECLAME     
         mov    ah,9
         mov    dx, offset einde         ; print reclame
         int    21h
ENDIF
     
IF UPDATEINFO
         mov    ah, 9
         mov    dx, offset Update
         int    21h
ENDIF
         mov    ah, 4ch
         int    21h               
                               
if Grafisch                    
        ; (*----- File created with BIN2DB from file NORMPICT.PIX. -----*)
label normal byte
 DB 0,0,0,0,0,0,0,0,0,0,6,6,6,6,6,6,6,0,0,0
 DB 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,6,6,6,6
 DB 6,6,6,6,6,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0
 DB 0,0,0,6,6,50,50,50,50,50,6,6,0,0,0,0,0,0,0,0
 DB 0,0,0,0,0,0,0,0,0,0,6,50,50,50,50,50,50,50,6,0
 DB 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,50,50,50
 DB 9,50,9,50,50,50,0,0,0,0,0,0,0,0,0,0,0,0,50,0
 DB 50,0,0,0,50,50,50,50,50,50,50,50,50,0,0,0,50,0,50,0
 DB 0,0,0,0,50,50,50,50,50,0,0,50,50,50,12,12,12,50,50,50
 DB 0,0,50,50,50,50,50,0,0
;-------------------------------------------------------------------------
; (*----- File created with BIN2DB from file HAPPYPIC.PIX. -----*)
label happy byte
 DB 0,0,0,0,0,0,0,0,0,0,6,6,6,6,6,6,6,0,0,0
 DB 50,0,50,0,0,0,0,0,0,0,0,0,0,0,0,6,6,6,6,6
 DB 6,6,6,6,0,0,0,50,50,0,0,0,0,0,0,0,0,0,0,0
 DB 0,6,6,50,50,50,50,50,6,6,0,0,0,50,50,0,0,0,0,0
 DB 0,0,0,0,0,0,0,6,50,50,9,50,9,50,50,6,0,0,0,50
 DB 50,0,0,0,0,0,0,0,0,0,0,0,0,50,50,50,1,50,1,50
 DB 50,50,0,0,0,50,50,0,0,0,0,0,0,50,0,50,0,0,0,50
 DB 50,12,50,50,50,12,50,50,0,0,0,50,50,0,0,0,0,0,50,50
 DB 50,50,50,0,0,50,50,50,12,12,12,50,50,50,0,0,50,50,50,0
 DB 0,0
;-------------------------------------------------------------------------
label palette byte                              
; (*----- File created with BIN2DB from file pauze.pal. -----*)
 DB 0,0,0,0,0,42,0,42,0,0,42,42,42,0,0,42,0,42,42,21
 DB 0,42,42,42,21,21,21,21,21,63,21,63,21,21,63,63,63,21,21,63
 DB 21,63,63,63,21,63,63,63,59,59,59,55,55,55,52,52,52,48,48,48
 DB 45,45,45,42,42,42,38,38,38,35,35,35,31,31,31,28,28,28,25,25
 DB 25,21,21,21,18,18,18,14,14,14,11,11,11,8,8,8,63,0,0,59
 DB 0,0,56,0,0,53,0,0,50,0,0,47,0,0,44,0,0,41,0,0
 DB 38,0,0,34,0,0,31,0,0,28,0,0,25,0,0,22,0,0,19,0
 DB 0,16,0,0,63,54,54,63,46,46,63,39,39,63,31,31,63,23,23,63
 DB 16,16,63,8,8,63,0,0,63,42,23,63,38,16,63,34,8,63,30,0
 DB 57,27,0,51,24,0,45,21,0,39,19,0,63,63,54,63,63,46,63,63
 DB 39,63,63,31,63,62,23,63,61,16,63,61,8,63,61,0,57,54,0,51
 DB 49,0,45,43,0,39,39,0,33,33,0,28,27,0,22,21,0,16,16,0
 DB 52,63,23,49,63,16,45,63,8,40,63,0,36,57,0,32,51,0,29,45
 DB 0,24,39,0,54,63,54,47,63,46,39,63,39,32,63,31,24,63,23,16
 DB 63,16,8,63,8,0,63,0,0,63,0,0,59,0,0,56,0,0,53,0
 DB 1,50,0,1,47,0,1,44,0,1,41,0,1,38,0,1,34,0,1,31
 DB 0,1,28,0,1,25,0,1,22,0,1,19,0,1,16,0,54,63,63,46
 DB 63,63,39,63,63,31,63,62,23,63,63,16,63,63,8,63,63,0,63,63
 DB 0,57,57,0,51,51,0,45,45,0,39,39,0,33,33,0,28,28,0,22
 DB 22,0,16,16,23,47,63,16,44,63,8,42,63,0,39,63,0,35,57,0
 DB 31,51,0,27,45,0,23,39,54,54,63,46,47,63,39,39,63,31,32,63
 DB 23,24,63,16,16,63,8,9,63,0,1,63,0,0,63,0,0,59,0,0
 DB 56,0,0,53,0,0,50,0,0,47,0,0,44,0,0,41,0,0,38,0
 DB 0,34,0,0,31,0,0,28,0,0,25,0,0,22,0,0,19,0,0,16
 DB 60,54,63,57,46,63,54,39,63,52,31,63,50,23,63,47,16,63,45,8
 DB 63,42,0,63,38,0,57,32,0,51,29,0,45,24,0,39,20,0,33,17
 DB 0,28,13,0,22,10,0,16,63,54,63,63,46,63,63,39,63,63,31,63
 DB 63,23,63,63,16,63,63,8,63,63,0,63,56,0,57,50,0,51,45,0
 DB 45,39,0,39,33,0,33,27,0,28,22,0,22,16,0,16,63,58,55,63
 DB 56,52,63,54,49,63,53,47,63,51,44,63,49,41,63,47,39,63,46,36
 DB 63,44,32,63,41,28,63,39,24,60,37,23,58,35,22,55,34,21,52,32
 DB 20,50,31,19,47,30,18,45,28,17,42,26,16,40,25,15,39,24,14,36
 DB 23,13,34,22,12,32,20,11,29,19,10,27,18,9,23,16,8,21,15,7
 DB 18,14,6,16,12,6,14,11,5,10,8,3,0,0,0,0,0,0,0,0
 DB 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,49,10,10,49
 DB 19,10,49,29,10,49,39,10,49,49,10,39,49,10,29,49,10,19,49,10
 DB 10,49,12,10,49,23,10,49,34,10,49,45,10,42,49,10,31,49,10,20
 DB 49,11,10,49,22,10,49,33,10,49,44,10,49,49,10,43,49,10,32,49
 DB 10,21,49,10,10,63,63,63
;----------------------------------------------------------------------
label picture byte                              
; (*----- File created with BIN2DB from file pauze.pix. -----*)
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,6,6,6,6
 DB 6,6,6,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,6,6,6,6,6,6,6,6,6,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,6,6,50,50,50,50,50,6,6,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,6,50,50
 DB 50,50,50,50,50,6,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,50,50,50,1,50,9,50,50,50
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,150,247,247,247,247,247,247,247,247,247,247,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,247,247,247,247,247
 DB 247,247,247,247,247,247,246,246,246,246,246,246,246,246,246,246,246,246,246,50
 DB 246,50,246,246,1,50,50,50,50,12,50,50,50,50,1,1,1,50,1,50
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,150,247
 DB 140,139,138,138,138,138,138,138,138,138,15,15,15,15,15,15,15,15,15,15
 DB 15,15,15,15,15,15,15,137,138,139,139,139,139,139,139,139,139,139,139,139
 DB 138,138,138,138,138,138,138,138,138,138,138,138,50,50,50,50,50,138,138,50
 DB 50,50,12,1,12,50,50,50,15,15,50,50,50,50,50,140,140,9,246,247
 DB 1,1,1,1,1,247,247,247,247,9,139,138,137,15,137,138,138,139,139,139
 DB 139,139,139,139,139,139,139,139,139,139,139,139,139,139,138,137,15,15,15,15
 DB 15,15,15,15,15,15,15,15,15,1,1,1,150,246,138,136,16,16,17,17
 DB 17,17,17,16,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15
 DB 15,16,17,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18,18
 DB 18,18,18,18,18,18,18,18,18,18,18,18,18,17,192,15,12,12,12,15
 DB 15,15,15,15,16,16,48,18,18,19,20,21,23,26,1,1,1,1,1,1
 DB 27,26,26,139,138,137,18,192,192,48,48,18,18,18,18,18,18,18,18,19
 DB 19,19,19,19,19,18,18,18,17,16,15,15,15,15,15,15,15,15,15,15
 DB 15,15,15,1,1,1,150,246,138,17,192,194,196,197,197,197,197,197,15,15
 DB 15,15,15,15,15,15,15,15,193,195,195,196,196,196,196,196,197,198,198,198
 DB 198,198,198,198,198,198,50,50,50,50,50,50,50,50,50,50,50,50,50,50
 DB 50,50,50,50,50,50,50,50,50,49,49,15,15,15,193,195,196,196,196,196
 DB 197,198,198,199,199,200,23,25,247,151,152,153,153,155,29,26,25,25,24,22
 DB 22,198,198,198,198,198,198,198,198,198,198,198,199,199,199,199,199,50,50,50
 DB 50,198,198,197,196,196,196,196,196,196,196,195,194,192,15,15,15,1,1,1
 DB 150,246,138,18,196,199,203,205,205,205,205,205,204,204,204,204,204,204,204,204
 DB 204,204,204,204,205,205,205,205,205,205,205,205,205,205,205,205,205,205,205,205
 DB 205,205,205,205,205,206,206,206,233,233,233,233,233,233,233,233,233,233,233,233
 DB 233,233,233,206,205,205,204,204,204,204,204,205,205,205,205,205,205,205,205,206
 DB 210,24,247,153,155,157,158,159,30,8,26,189,189,215,210,207,205,205,205,205
 DB 205,205,205,205,205,205,205,205,205,205,205,205,205,205,205,205,205,205,205,205
 DB 205,204,204,204,204,204,200,197,193,15,15,1,1,1,150,246,138,18,198,203
 DB 206,233,232,232,232,232,232,232,232,232,232,232,232,232,232,232,232,232,232,232
 DB 232,232,232,232,232,232,232,232,232,232,232,232,232,232,232,232,232,232,232,232
 DB 232,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,232,232,232
 DB 232,232,232,232,232,232,232,232,232,232,232,232,232,232,253,189,173,127,127,111
 DB 111,111,30,29,190,190,190,41,39,232,232,232,232,232,232,232,232,232,232,232
 DB 232,232,232,232,232,232,232,232,232,232,232,232,232,232,232,232,232,232,233,233
 DB 205,200,195,192,15,1,1,1,150,246,138,19,50,205,233,232,37,38,38,38
 DB 38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38
 DB 38,38,38,38,38,38,38,38,38,38,38,38,38,38,4,4,4,4,4,4
 DB 4,4,4,4,4,4,4,4,4,4,4,4,38,38,38,38,38,38,38,38
 DB 38,38,38,38,38,38,38,38,41,190,29,110,109,108,108,110,31,191,46,44
 DB 42,41,40,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38
 DB 38,38,39,38,38,38,38,38,38,38,38,38,37,232,233,204,197,192,15,1
 DB 1,1,150,246,138,19,50,205,232,37,38,4,4,4,4,4,4,4,4,38
 DB 38,38,38,38,38,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4
 DB 4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4
 DB 4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4
 DB 40,40,41,43,222,109,107,106,106,109,111,223,45,44,41,40,40,40,40,40
 DB 40,40,4,4,4,4,4,4,4,4,4,4,4,40,40,40,40,40,40,40
 DB 41,42,42,41,40,4,38,37,233,204,197,192,15,1,1,1,150,246,139,19
 DB 50,205,232,38,4,40,40,40,40,40,40,4,38,38,38,38,38,38,38,38
 DB 4,40,40,40,40,40,40,40,40,40,4,4,4,4,4,4,4,4,4,4
 DB 4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4
 DB 4,4,4,4,4,4,4,4,4,4,4,4,4,40,40,40,40,42,222,109
 DB 107,105,106,108,110,30,191,44,41,40,40,40,40,40,40,40,40,4,4,4
 DB 4,40,40,40,40,40,40,40,41,42,43,44,44,44,190,190,190,43,41,40
 DB 4,38,232,205,197,192,15,1,1,1,150,247,139,19,50,205,232,38,4,40
 DB 40,40,40,40,4,38,232,232,232,232,232,232,232,232,38,40,40,40,40,40
 DB 40,40,40,40,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4
 DB 4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4
 DB 4,4,4,4,4,4,4,4,40,40,40,42,221,110,108,107,107,108,109,30
 DB 191,44,41,40,40,40,40,40,40,40,40,4,4,4,40,40,40,40,40,40
 DB 41,42,44,191,191,191,191,191,174,173,190,190,42,40,40,38,232,205,197,192
 DB 137,1,1,1,150,247,139,19,50,206,232,38,4,40,40,40,40,38,37,232
 DB 53,53,253,253,188,188,253,232,232,38,40,40,40,40,40,40,40,4,38,38
 DB 4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4
 DB 38,38,4,4,4,4,38,38,38,4,4,4,4,38,38,4,4,4,4,4
 DB 4,4,4,4,39,40,43,127,127,127,127,127,106,190,190,44,42,41,41,40
 DB 40,40,40,40,4,4,4,4,40,40,40,40,40,41,43,45,191,175,159,159
 DB 159,158,174,174,173,190,190,42,40,38,232,205,197,192,137,1,1,1,150,247
 DB 139,19,50,205,232,38,4,40,40,40,38,232,232,53,12,12,253,188,189,189
 DB 188,253,232,38,40,40,40,40,40,40,4,38,232,233,62,62,62,62,4,4
 DB 4,4,4,4,4,4,4,4,62,62,62,62,62,62,233,232,38,4,4,38
 DB 232,232,232,38,4,4,38,232,232,38,4,4,38,38,232,62,62,62,62,63
 DB 215,216,217,44,43,43,217,217,217,217,216,215,63,38,4,4,4,4,4,4
 DB 4,4,40,40,40,40,41,43,190,191,175,159,159,127,127,127,127,175,174,174
 DB 190,43,40,38,232,205,197,192,137,1,1,1,150,247,139,19,50,205,232,38
 DB 4,40,40,40,38,232,53,12,12,12,253,189,190,190,189,253,232,38,40,40
 DB 40,40,40,40,38,232,234,235,235,235,235,235,236,236,22,4,4,4,4,4
 DB 23,236,236,236,236,236,235,235,235,234,232,38,38,233,209,209,233,38,38,38
 DB 232,233,233,38,4,38,232,233,234,235,235,235,235,235,210,213,217,41,41,215
 DB 213,213,213,213,213,211,210,233,38,4,4,4,4,4,4,40,40,40,40,40
 DB 41,190,190,174,158,159,127,109,108,9,109,30,175,174,191,44,41,38,232,205
 DB 197,192,137,1,1,1,150,247,139,19,50,205,232,38,4,40,40,40,38,232
 DB 53,12,12,12,253,190,190,190,190,232,38,40,40,40,40,40,40,40,38,233
 DB 236,14,14,14,14,14,14,14,68,80,23,4,4,4,236,14,14,14,14,14
 DB 69,69,236,235,232,38,233,206,205,205,209,232,38,62,234,235,234,62,4,38
 DB 232,209,235,236,236,236,236,236,235,210,63,40,232,212,209,235,235,209,209,235
 DB 235,234,232,38,4,40,4,40,40,40,40,40,40,4,232,253,189,174,158,127
 DB 110,108,1,106,108,110,127,157,174,191,43,40,232,205,197,192,137,1,1,1
 DB 150,247,139,19,50,205,232,38,4,40,40,40,38,232,53,12,12,53,232,41
 DB 190,190,42,40,40,40,40,40,40,40,40,40,38,232,23,80,68,14,68,80
 DB 236,80,68,14,80,4,4,4,23,80,68,14,68,80,80,68,14,236,4,4
 DB 22,68,14,68,22,4,4,4,236,14,236,4,4,4,4,23,236,236,235,235
 DB 235,236,237,209,232,232,233,207,236,235,234,233,234,208,235,234,232,38,4,40
 DB 40,40,40,40,40,4,38,232,253,253,189,174,127,110,108,106,105,106,6,4
 DB 4,157,156,174,190,41,232,205,197,192,137,1,1,1,150,247,139,19,50,205
 DB 232,38,4,40,40,40,38,232,232,53,53,232,38,40,42,42,40,40,40,40
 DB 40,40,40,40,40,40,40,38,4,4,80,14,80,4,4,4,80,14,68,22
 DB 4,4,4,4,80,68,21,4,4,80,14,236,4,4,236,14,68,21,4,4
 DB 4,4,236,14,236,4,4,4,4,4,4,4,4,4,234,21,23,212,232,232
 DB 208,80,69,236,233,38,232,210,209,23,4,4,4,40,40,40,40,40,40,38
 DB 36,232,253,253,189,29,109,108,106,105,106,108,110,111,127,156,155,173,190,41
 DB 232,205,198,192,137,1,1,1,150,247,139,19,50,205,232,38,4,40,40,40
 DB 40,38,232,232,232,38,40,40,40,40,40,40,40,40,40,40,40,40,40,40
 DB 40,4,4,4,236,14,236,4,4,4,236,14,14,236,4,4,4,4,80,80
 DB 4,4,4,236,14,236,4,4,236,14,80,4,4,4,4,4,236,14,236,4
 DB 4,4,4,4,4,4,4,4,21,80,21,4,4,4,236,14,14,80,4,4
 DB 4,4,4,4,4,4,4,40,40,40,40,40,40,38,36,232,253,253,28,29
 DB 107,105,105,106,108,110,111,127,157,155,155,173,190,41,232,205,198,18,138,247
 DB 1,1,150,247,139,19,50,205,232,38,4,40,40,40,40,40,38,38,38,40
 DB 40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,4,4,236,14
 DB 236,4,4,4,236,14,14,236,4,4,4,22,68,236,4,4,4,236,14,236
 DB 4,4,236,14,236,4,4,4,4,4,236,14,236,4,4,4,4,4,4,4
 DB 4,21,68,80,4,4,4,4,236,14,14,68,80,236,236,236,236,23,4,4
 DB 4,40,40,40,40,40,40,38,36,232,232,213,87,240,104,105,105,107,110,111
 DB 159,157,155,155,173,190,43,41,232,205,198,18,138,247,1,1,150,247,139,19
 DB 50,205,232,38,4,40,40,40,40,40,40,40,40,40,38,38,40,40,40,40
 DB 40,40,40,40,40,40,40,40,40,40,4,4,236,14,80,4,4,4,80,14
 DB 68,22,4,4,4,236,14,80,4,4,4,80,14,236,4,4,236,14,236,4
 DB 4,4,4,4,236,14,236,4,4,4,4,4,4,4,21,68,68,22,4,4
 DB 4,4,236,14,14,14,14,14,14,14,14,236,4,4,4,40,40,40,40,40
 DB 40,38,36,232,232,213,239,102,104,105,106,108,111,159,157,155,155,173,173,190
 DB 42,40,233,205,198,18,138,247,1,1,150,247,139,19,50,205,232,38,4,40
 DB 40,40,40,38,38,38,38,38,232,232,38,38,38,40,40,40,40,40,40,40
 DB 40,40,40,40,40,4,236,14,68,80,236,80,68,14,80,4,4,4,4,80
 DB 14,68,80,236,80,68,14,236,4,4,236,14,236,4,4,4,4,4,236,14
 DB 236,4,4,4,4,4,4,22,68,68,21,4,4,4,4,4,236,14,14,68
 DB 80,236,236,236,236,23,4,4,40,40,40,40,40,40,40,38,36,232,232,213
 DB 239,102,105,105,108,110,127,157,155,155,155,173,190,43,41,40,233,205,198,48
 DB 138,247,1,1,150,247,139,19,50,205,232,38,4,40,40,40,38,232,232,232
 DB 232,253,188,188,253,253,232,40,41,42,42,40,40,40,40,40,40,40,40,4
 DB 236,14,14,14,14,14,68,80,23,4,4,4,22,68,14,14,14,14,14,14
 DB 14,236,4,4,236,14,236,4,4,4,4,4,236,14,236,4,4,4,4,4
 DB 4,80,68,21,4,4,4,4,4,4,236,14,14,80,4,4,4,4,4,4
 DB 4,40,40,40,40,40,40,40,40,38,36,232,232,213,87,104,106,107,109,127
 DB 157,155,155,173,173,173,190,43,41,40,233,205,197,48,138,247,1,1,150,247
 DB 139,19,50,205,232,38,4,40,40,40,38,232,232,232,253,253,189,172,172,189
 DB 190,42,43,190,190,42,40,40,40,40,40,40,40,6,236,14,68,80,236,236
 DB 22,4,4,4,4,4,236,14,68,80,236,236,80,68,14,236,4,4,236,14
 DB 80,4,4,4,4,4,236,14,236,4,4,4,4,4,21,68,80,4,4,4
 DB 4,4,4,4,236,14,14,236,4,4,4,4,4,4,4,40,40,40,40,40
 DB 40,40,40,38,36,232,232,214,87,105,107,222,30,175,157,155,173,173,190,190
 DB 190,42,40,38,232,205,197,48,138,247,1,1,150,247,139,19,50,205,232,38
 DB 4,40,40,40,4,38,39,41,190,189,173,154,153,173,190,45,45,191,190,42
 DB 40,40,40,40,40,40,39,62,236,14,80,4,4,4,4,4,4,4,4,4
 DB 236,14,80,4,4,4,4,80,14,236,4,4,22,68,68,21,4,4,4,4
 DB 236,14,236,4,4,4,4,22,68,68,22,4,4,4,4,4,4,4,236,14
 DB 14,236,4,4,4,4,4,4,4,40,40,40,40,40,40,40,40,4,38,232
 DB 232,63,77,78,78,219,191,191,174,174,173,190,190,43,42,41,40,38,232,205
 DB 197,48,138,247,1,1,150,247,139,19,50,205,232,38,4,40,40,40,40,40
 DB 42,190,190,173,173,154,153,155,175,31,222,221,219,42,40,40,40,40,40,40
 DB 39,62,236,14,236,4,4,4,4,4,4,4,4,4,236,14,80,4,4,4
 DB 4,236,14,236,62,38,233,235,236,236,207,4,4,4,80,14,80,4,4,4
 DB 4,236,14,80,4,4,4,4,4,4,4,4,22,68,14,80,4,4,4,4
 DB 4,4,4,40,40,40,40,40,40,40,40,40,4,38,38,39,40,41,41,42
 DB 44,190,190,190,190,190,43,41,40,40,40,38,232,205,197,17,138,247,1,1
 DB 150,247,139,19,50,205,232,38,4,40,40,40,40,42,190,190,173,173,173,155
 DB 155,158,30,111,110,79,220,43,40,40,40,40,40,40,39,62,235,14,236,4
 DB 4,4,4,4,4,4,4,4,236,14,68,80,23,4,4,236,236,235,233,38
 DB 232,234,235,235,235,235,236,80,68,14,68,80,23,4,4,236,14,68,80,236
 DB 236,236,236,23,4,4,4,80,14,68,80,236,236,236,236,23,4,40,40,40
 DB 40,40,40,40,40,40,40,40,40,40,40,40,40,40,41,42,42,42,42,42
 DB 41,40,40,40,40,38,232,205,197,17,138,247,1,1,150,247,139,19,199,205
 DB 232,38,4,40,40,40,42,190,190,190,173,173,173,156,157,127,110,110,110,110
 DB 79,43,40,40,40,40,40,40,39,6,234,236,23,4,4,40,40,40,40,40
 DB 40,4,23,236,236,236,23,4,4,233,234,233,232,38,37,233,234,235,235,235
 DB 236,236,236,236,236,236,23,4,4,23,236,236,236,236,236,236,236,23,4,4
 DB 4,23,236,236,236,236,236,236,236,23,4,4,4,40,40,40,40,40,40,40
 DB 40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,38
 DB 232,205,197,17,138,247,1,1,150,247,139,19,199,205,232,38,4,4,40,40
 DB 42,190,190,190,173,173,174,175,127,110,108,108,108,110,79,43,40,40,40,40
 DB 40,40,40,39,4,4,4,39,40,40,40,40,40,40,40,39,232,233,62,233
 DB 4,38,38,232,232,232,38,4,4,38,232,233,62,62,4,4,4,4,4,4
 DB 4,38,38,232,62,62,62,62,62,62,233,232,38,4,38,232,233,62,62,62
 DB 62,62,233,232,38,4,4,40,40,40,40,40,40,40,40,40,40,40,40,40
 DB 40,40,40,40,40,40,40,40,40,40,40,40,4,38,232,205,197,15,15,1
 DB 1,1,150,247,139,18,198,205,232,38,4,4,40,40,42,190,190,190,173,174
 DB 175,30,109,108,106,106,107,108,79,42,40,40,40,40,40,40,40,40,40,40
 DB 40,40,40,40,40,40,40,40,40,40,39,38,4,4,4,4,4,38,38,38
 DB 40,40,40,40,38,38,4,39,4,4,4,4,4,4,4,4,4,4,4,4
 DB 4,4,4,4,38,38,4,4,4,38,38,4,4,4,4,4,38,38,4,4
 DB 4,4,4,4,4,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40
 DB 40,40,40,40,40,4,4,38,232,205,197,15,15,1,1,1,150,247,139,18
 DB 198,205,232,38,4,40,40,40,41,43,190,190,174,174,30,109,107,105,105,105
 DB 106,107,78,41,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40
 DB 40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40
 DB 40,40,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4
 DB 4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,40
 DB 40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,4,4,4
 DB 4,38,232,205,197,15,15,1,1,1,150,247,139,18,198,205,232,38,4,4
 DB 40,40,41,43,190,190,191,30,109,107,105,105,105,106,107,78,77,41,40,40
 DB 40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40
 DB 40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,4,4,4,4
 DB 4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4
 DB 4,4,4,4,4,4,4,4,4,4,4,4,4,4,40,40,40,40,40,40
 DB 40,40,40,40,40,40,40,40,40,40,40,4,4,4,4,38,232,205,197,15
 DB 15,1,1,1,150,247,139,18,198,205,233,37,38,4,40,40,41,42,44,191
 DB 31,110,107,105,104,104,104,106,78,43,40,40,40,40,40,40,40,40,40,40
 DB 40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40,40
 DB 40,40,40,40,40,40,40,40,40,4,4,4,4,4,4,4,4,4,4,4
 DB 4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4
 DB 4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4,4
 DB 4,4,4,4,4,4,4,4,38,37,232,205,197,15,15,1,1,1,150,247
 DB 139,18,198,204,233,232,37,38,38,40,40,41,43,223,110,108,105,103,103,102
 DB 103,87,216,40,38,38,38,38,38,38,38,38,4,4,4,4,4,4,4,4
 DB 4,4,4,4,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38
 DB 38,4,4,4,4,4,4,4,4,4,4,38,38,38,38,38,38,38,38,38
 DB 38,38,38,38,4,4,4,4,4,4,4,4,4,4,4,4,4,38,38,38
 DB 38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38,38
 DB 38,38,37,232,233,204,197,15,15,1,1,1,150,247,139,18,197,203,206,233
 DB 232,232,232,232,38,40,42,79,108,105,104,102,100,240,239,214,212,233,232,232
 DB 232,232,232,232,232,232,232,38,38,38,38,38,38,38,38,38,38,38,232,232
 DB 232,232,232,232,232,232,232,232,232,232,232,232,232,232,232,38,38,38,38,38
 DB 38,38,38,38,232,232,232,232,232,232,232,232,232,232,232,232,232,232,38,38
 DB 38,38,38,38,38,38,38,38,38,38,232,232,232,232,232,232,232,232,232,232
 DB 232,232,232,232,232,232,232,232,232,232,232,232,232,232,232,232,233,233,206,201
 DB 196,15,15,1,1,1,150,247,139,18,196,199,203,205,205,205,205,206,233,233
 DB 214,216,107,104,101,240,239,238,208,207,206,205,205,205,205,205,205,205,205,205
 DB 206,233,233,233,233,233,233,233,233,233,233,233,206,205,205,205,205,205,205,205
 DB 205,205,205,205,205,205,205,205,206,233,233,233,233,233,233,233,233,206,206,205
 DB 205,205,205,205,205,205,205,205,205,205,205,206,233,233,206,206,233,233,233,233
 DB 233,206,206,206,206,206,206,205,205,205,205,205,205,205,205,205,205,205,205,205
 DB 205,205,205,205,205,205,205,205,205,205,205,204,201,198,194,15,15,1,1,1
 DB 1,247,139,17,193,196,198,50,50,50,50,50,205,208,23,24,25,239,239,92
 DB 91,22,200,200,50,50,50,50,50,199,199,199,50,50,50,50,50,50,50,50
 DB 50,50,50,50,50,50,50,50,50,50,50,50,50,50,199,199,50,50,50,50
 DB 50,199,200,50,50,50,50,50,50,50,50,50,199,199,199,199,199,199,200,199
 DB 199,199,199,199,199,199,200,200,199,199,199,199,199,199,197,198,198,199,199,199
 DB 199,199,199,199,199,199,199,199,199,199,199,199,199,199,199,199,199,199,199,199
 DB 199,199,199,199,198,198,196,194,192,15,15,1,1,1,1,1,139,136,18,18
 DB 18,18,18,18,18,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19
 DB 19,19,19,19,19,19,19,18,18,18,18,19,19,19,19,19,19,19,19,19
 DB 19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,19,18,18
 DB 18,18,18,18,19,19,19,19,19,19,20,20,198,197,196,196,194,194,194,194
 DB 194,194,194,194,194,193,192,192,192,192,192,193,193,194,194,194,194,194,194,194
 DB 194,194,194,194,194,194,194,194,194,195,197,197,197,197,197,197,196,19,18,18
 DB 17,16,15,15,15,1,1,1,1,1,139,138,139,139,139,139,139,139,139,139
 DB 139,139,139,139,139,139,139,139,139,139,139,139,139,139,139,139,139,139,139,139
 DB 139,139,139,139,139,139,139,139,139,139,139,139,139,139,139,139,139,139,139,139
 DB 139,139,139,139,139,139,139,139,139,139,139,139,139,139,139,139,139,139,139,139
 DB 139,139,139,139,139,24,23,23,22,22,19,19,15,15,15,15,15,15,15,15
 DB 15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15
 DB 15,15,15,7,22,22,22,22,22,22,22,138,138,138,138,137,15,15,15,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,247,247,247
 DB 247,247,247,247,247,247,247,247,247,247,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,150,150,150,150,150,150,150,150
 DB 150,150,151,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 DB 1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1
 Buffer db 134*47 dup (2)     
label menupic                
include menu.db
endif                                
     
code    Ends
End     start
```
