---
title: Sounds / music
---

* see also [audio](/audio)

* https://incompetech.com/music/royalty-free/music.html

# C - G - Am - Em - F - C - F - G
```nasm
dw C4,E4,G4, C5,E5,G5   ;C
dw C4,E4,G4, C5,E5,G5   ;C

dw G3,B3,D4, G4,B4,D5   ;G
dw G3,B3,D4, G4,B4,D5   ;G

dw A3,C4,E4, A4,C5,E5   ;Am
dw A3,C4,E4, A4,C5,E5   ;Am

dw E3,G3,B3, E4,G4,B4   ;Em
dw E3,G3,B3, E4,G4,B4   ;Em

dw F3,A3,C4, F4,A4,C5   ;F
dw F3,A3,C4, F4,A4,C5   ;F

dw C4,E4,G4, C5,E5,G5   ;C
dw C4,E4,G4, C5,E5,G5   ;C

dw F3,A3,C4, F4,A4,C5   ;F
dw F3,A3,C4, F4,A4,C5   ;F

dw G3,B3,D4, G4,B4,D5   ;G
dw G3,B3,D4, G4,B4,D5   ;G
```

# zelfde maar andersom terug
; C - G - Am - Em - F - C - F - G

; C
dw C4,E4,G4 ; linker hand
dw C5,E5,G5,C6,G5,E5,  C5    ; rechterhand >>>> <<
dw G4,E4 ; linker hand

; G
dw G3,B3,D4 ; linker hand
dw G4,B4,D5,G5,D5,B4,  G4    ; rechter hand
dw D4,B3 ; linker hand

; Am
dw A3,C4,E4 ; linker hand
dw A4,C5,E5,A5,E5,C5,  A4    ; rechter hand
dw E4,C4 ; linker hand

; Em
dw E3,G3,B3 ; linker hand
dw E4,G4,B4,E5,B4,G4,  E4  ; rechter hand
dw B3,G3 ; linker hand

; F
dw F3,A3,C4 ; linker hand
dw F4,A4,C5,F5,C5,A4,  F4  ; rechter hand
dw C4,A3 ; linker hand

; C 
dw C4,E4,G4 ; linker hand
dw C5,E5,G5,C6,G5,E5,  C5 ; rechterhand >>>> <<
dw G4,E4 ; linker hand

; F
dw F3,A3,C4 ; linker hand
dw F4,A4,C5,F5,C5,A4,  F4  ; rechter hand
dw C4,A3 ; linker hand

; G
dw G3,B3,D4 ; linker hand
dw G4,B4,D5,G5,D5,B4,  G4 ; rechter hand
dw D4,B3 ; linker hand



# Wonderful days intro
```nasm
dw G3,C4,E4,C4
dw G3,C4,E4,C4
dw G3,B3,D4,B3
dw G3,B3,D4,B3
dw A3,D4,F4,D4
dw A3,D4,F4,D4
dw A3,C4,E4,C4
dw A3,C4,E4,C4
```
