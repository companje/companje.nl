<img width="640" height="400" alt="verlets" src="https://github.com/user-attachments/assets/21b9c9f5-50ac-4b85-a547-521b260382ca" />

```nasm
org 100h
cpu 8086

COLS       equ 80              ; visible cell columns
ROWS       equ 50              ; visible cell rows
GREEN      equ 0x3c00          ; RGB plane segment for green

POINTS     equ 12              ; chain point count
LINK_DIST  equ 4               ; rest length in cells
FP_SHIFT   equ 4               ; fixed-point fractional bits
FP_ONE     equ 1 << FP_SHIFT   ; 1.0 in fixed-point
GRAVITY_FP equ 4               ; gravity strength in fixed-point
LINK_DIST_FP equ LINK_DIST << FP_SHIFT ; rest length in fixed-point
CONSTRAINT_STEP equ 4          ; constraint correction per pass
COLS_FP    equ COLS << FP_SHIFT ; screen width in fixed-point
ROWS_FP    equ ROWS << FP_SHIFT ; screen height in fixed-point
ITERATIONS equ 4               ; constraint iterations per frame
START_ROW  equ 1               ; initial chain row
START_COL  equ 18              ; initial chain start column
PIN_START_ROW equ 0            ; pinned point row
PRE_FRAMES equ 150            ; buffered animation frames
DELAY      equ 0x800           ; playback busy-wait

setup:
    push cs
    pop ds

    call clear_green_plane
    call init_chain
    call warm_up_chain
    call copy_screen_positions

    call prerender_frames
    call clear_green_plane

    mov word [play_frame], 0
    mov word [draw_word], 0xffff
    xor ax, ax
    call draw_buffered_chain

main_loop:
    call set_next_frame
    call update_green_chain
    mov ax, [next_frame]
    mov [play_frame], ax
    call frame_delay
    jmp main_loop

simulate_frame:
    call move_pin
    call verlet_step

    mov cl, ITERATIONS
.solve_again:
    push cx
    call pin_first
    call solve_links
    call keep_inside_screen
    pop cx
    loop .solve_again

    call pin_first
    ret

prerender_frames:
    mov word [render_frame], 0
.next:
    call simulate_frame
    mov ax, [render_frame]
    call store_frame_positions
    inc word [render_frame]
    cmp word [render_frame], PRE_FRAMES
    jb .next
    ret

warm_up_chain:
    mov bl, 8
.next:
    call simulate_frame
    dec bl
    jnz .next
    ret

set_next_frame:
    mov ax, [play_frame]
    inc ax
    cmp ax, PRE_FRAMES
    jb .done
    xor ax, ax
.done:
    mov [next_frame], ax
    ret

set_frame_offset:
    mov bx, POINTS
    mul bx
    mov [frame_offset], ax
    ret

store_frame_positions:
    call set_frame_offset
    mov bx, [frame_offset]
    xor si, si
    xor di, di
.next:
    mov cl, FP_SHIFT
    mov ax, [rows + si]
    shr ax, cl
    mov [frame_rows + bx + di], al
    mov ax, [cols + si]
    shr ax, cl
    mov [frame_cols + bx + di], al
    add si, 2
    inc di
    cmp di, POINTS
    jb .next
    ret

load_frame_positions:
    call set_frame_offset
    mov bx, [frame_offset]
    xor di, di
.next:
    mov al, [frame_rows + bx + di]
    mov [screen_rows + di], al
    mov al, [frame_cols + bx + di]
    mov [screen_cols + di], al
    inc di
    cmp di, POINTS
    jb .next
    ret

load_prev_frame_positions:
    call set_frame_offset
    mov bx, [frame_offset]
    xor di, di
.next:
    mov al, [frame_rows + bx + di]
    mov [prev_screen_rows + di], al
    mov al, [frame_cols + bx + di]
    mov [prev_screen_cols + di], al
    inc di
    cmp di, POINTS
    jb .next
    ret

load_next_frame_positions:
    call set_frame_offset
    mov bx, [frame_offset]
    xor di, di
.next:
    mov al, [frame_rows + bx + di]
    mov [next_screen_rows + di], al
    mov al, [frame_cols + bx + di]
    mov [next_screen_cols + di], al
    inc di
    cmp di, POINTS
    jb .next
    ret

draw_buffered_chain:
    call load_frame_positions
    call draw_screen_chain
    ret

update_green_chain:
    mov ax, GREEN
    mov es, ax
    mov ax, [play_frame]
    call load_prev_frame_positions
    mov ax, [next_frame]
    call load_next_frame_positions
    call draw_transition_chain
    ret

draw_transition_chain:
    mov byte [draw_phase], 0
    xor si, si
.next:
    mov word [draw_word], 0x0000
    mov bh, [prev_screen_rows + si]
    mov bl, [prev_screen_cols + si]
    mov dh, [prev_screen_rows + si + 1]
    mov dl, [prev_screen_cols + si + 1]
    call draw_link

    mov word [draw_word], 0xffff
    mov bh, [next_screen_rows + si]
    mov bl, [next_screen_cols + si]
    mov dh, [next_screen_rows + si + 1]
    mov dl, [next_screen_cols + si + 1]
    call draw_link

    inc si
    cmp si, POINTS - 1
    jb .next

    mov word [draw_word], 0x0000
    mov bh, [prev_screen_rows + POINTS - 1]
    mov bl, [prev_screen_cols + POINTS - 1]
    call draw_point

    mov word [draw_word], 0xffff
    mov bh, [next_screen_rows + POINTS - 1]
    mov bl, [next_screen_cols + POINTS - 1]
    call draw_point
    ret

; Initialize a vertical chain.
; rows/cols hold current positions; old_rows/old_cols hold previous positions.
init_chain:
    xor si, si
    mov ax, START_ROW << FP_SHIFT
.next:
    mov [rows + si], ax
    mov [old_rows + si], ax
    mov word [cols + si], START_COL << FP_SHIFT
    mov word [old_cols + si], START_COL << FP_SHIFT
    add ax, LINK_DIST_FP
    add si, 2
    cmp si, POINTS * 2
    jb .next
    ret

copy_screen_positions:
    xor si, si
    xor di, di
.next:
    mov cl, FP_SHIFT
    mov ax, [rows + si]
    shr ax, cl
    mov [screen_rows + di], al
    mov ax, [cols + si]
    shr ax, cl
    mov [screen_cols + di], al
    add si, 2
    inc di
    cmp di, POINTS
    jb .next
    ret

clear_screen:
    xor di, di
    xor ax, ax
    mov cx, COLS * ROWS * 2    ; 80*50 cells, 2 words per cell
    rep stosw
    ret

clear_green_plane:
    mov ax, GREEN
    mov es, ax
    call clear_screen
    ret

frame_delay:
%if DELAY
    mov cx, DELAY
.wait:
    loop .wait
%endif
    ret

; Move the pinned endpoint in a full-width horizontal triangle wave.
move_pin:
    inc byte [frame]
    mov al, [frame]
    cmp al, 158
    jb .phase_ok
    sub al, 158
    mov [frame], al
.phase_ok:
    cmp al, 79
    jbe .rising
    mov bl, 157
    sub bl, al
    mov al, bl
.rising:
    mov ah, 0
    mov cl, FP_SHIFT
    shl ax, cl
    mov [pin_col], ax
    mov word [pin_row], PIN_START_ROW << FP_SHIFT
    ret

; Pin point 0.
pin_first:
    mov ax, [pin_row]
    mov [rows], ax
    mov [old_rows], ax
    mov ax, [pin_col]
    mov [cols], ax
    mov [old_cols], ax
    ret

; Verlet step for points 1..POINTS-1.
; new = current + (current - old) + gravity(row only)
verlet_step:
    mov si, 2
.next:
    mov ax, [rows + si]
    mov bx, ax
    sub ax, [old_rows + si]    ; row velocity
    mov [old_rows + si], bx
    add ax, bx
    add ax, GRAVITY_FP         ; gravity
    mov [rows + si], ax

    mov ax, [cols + si]
    mov bx, ax
    sub ax, [old_cols + si]    ; col velocity
    mov [old_cols + si], bx
    add ax, bx
    mov [cols + si], ax

    add si, 2
    cmp si, POINTS * 2
    jb .next
    ret

; Keep all non-pinned points in the visible 80x50 cell grid.
keep_inside_screen:
    mov si, 2
.next:
    cmp word [rows + si], 0
    jge .row_low_ok
    neg word [rows + si]
    neg word [old_rows + si]
    jmp .col
.row_low_ok:
    cmp word [rows + si], ROWS_FP
    jb .col
    mov ax, (ROWS - 1) << FP_SHIFT
    shl ax, 1
    sub ax, [rows + si]
    mov [rows + si], ax
    mov ax, (ROWS - 1) << FP_SHIFT
    shl ax, 1
    sub ax, [old_rows + si]
    mov [old_rows + si], ax
.col:
    cmp word [cols + si], 0
    jge .col_low_ok
    neg word [cols + si]
    neg word [old_cols + si]
    jmp .done
.col_low_ok:
    cmp word [cols + si], COLS_FP
    jb .done
    mov ax, (COLS - 1) << FP_SHIFT
    shl ax, 1
    sub ax, [cols + si]
    mov [cols + si], ax
    mov ax, (COLS - 1) << FP_SHIFT
    shl ax, 1
    sub ax, [old_cols + si]
    mov [old_cols + si], ax
.done:
    add si, 2
    cmp si, POINTS * 2
    jb .next
    ret

; Constraint pass over adjacent points.
; Processing uses sqrt(dx*dx + dy*dy). This port uses an octagonal distance
; approximation, max(abs(dx),abs(dy)) + min(abs(dx),abs(dy))/2, then corrects
; along the link vector. That avoids the axis/diagonal locking of independent
; row/col nudges without paying for an integer square root.
solve_links:
    xor si, si
.next:
    mov ax, [cols + si + 2]
    sub ax, [cols + si]        ; signed col gap: next - current
    mov [link_dx], ax
    mov bx, ax
    cmp bx, 0
    jge .abs_dx_ready
    neg bx
.abs_dx_ready:
    mov [abs_dx], bx

    mov ax, [rows + si + 2]
    sub ax, [rows + si]        ; signed row gap: next - current
    mov [link_dy], ax
    mov bx, ax
    cmp bx, 0
    jge .abs_dy_ready
    neg bx
.abs_dy_ready:
    mov [abs_dy], bx

    mov ax, [abs_dx]
    mov bx, [abs_dy]
    cmp ax, bx
    jae .have_max_min
    xchg ax, bx
.have_max_min:
    shr bx, 1
    add ax, bx
    cmp ax, 0
    je .done_link
    mov [link_dist], ax
    sub ax, LINK_DIST_FP
    mov [link_error], ax

    imul word [link_dx]        ; DX:AX = error * dx
    idiv word [link_dist]
    mov [move_col], ax

    mov ax, [link_error]
    imul word [link_dy]        ; DX:AX = error * dy
    idiv word [link_dist]
    mov [move_row], ax

    cmp si, 0
    je .pinned_parent

    mov ax, [move_col]
    sar ax, 1
    add [cols + si], ax
    mov bx, [move_col]
    sub bx, ax
    sub [cols + si + 2], bx

    mov ax, [move_row]
    sar ax, 1
    add [rows + si], ax
    mov bx, [move_row]
    sub bx, ax
    sub [rows + si + 2], bx
    jmp .done_link

.pinned_parent:
    mov ax, [move_col]
    sub [cols + si + 2], ax
    mov ax, [move_row]
    sub [rows + si + 2], ax

.done_link:
    add si, 2
    cmp si, (POINTS - 1) * 2
    jb .next
    ret

draw_screen_chain:
    mov byte [draw_phase], 0
    xor si, si
.next:
    mov bh, [screen_rows + si]
    mov bl, [screen_cols + si]
    mov dh, [screen_rows + si + 1]
    mov dl, [screen_cols + si + 1]
    call draw_link

    inc si
    cmp si, POINTS - 1
    jb .next

    mov bh, [screen_rows + POINTS - 1]
    mov bl, [screen_cols + POINTS - 1]
    call draw_point
    ret

draw_chain:
    mov byte [draw_phase], 0
    xor si, si
.next:
    mov cl, FP_SHIFT
    mov ax, [rows + si]
    shr ax, cl
    mov bh, al
    mov ax, [cols + si]
    shr ax, cl
    mov bl, al
    mov ax, [rows + si + 2]
    shr ax, cl
    mov dh, al
    mov ax, [cols + si + 2]
    shr ax, cl
    mov dl, al
    call draw_link

    add si, 2
    cmp si, (POINTS - 1) * 2
    jb .next

    mov cl, FP_SHIFT
    mov ax, [rows + (POINTS - 1) * 2]
    shr ax, cl
    mov bh, al
    mov ax, [cols + (POINTS - 1) * 2]
    shr ax, cl
    mov bl, al
    call draw_point
    ret

; input: BH/BL=start row/col, DH/DL=end row/col
draw_link:
    mov [line_row], bh
    mov [line_col], bl
    mov [line_end_row], dh
    mov [line_end_col], dl

    mov al, [line_end_col]
    sub al, [line_col]
    cbw
    mov byte [line_sx], 1
    cmp ax, 0
    jge .dx_ready
    neg ax
    mov byte [line_sx], -1
.dx_ready:
    mov [line_dx], ax

    mov al, [line_end_row]
    sub al, [line_row]
    cbw
    mov byte [line_sy], 1
    cmp ax, 0
    jge .dy_ready
    neg ax
    mov byte [line_sy], -1
.dy_ready:
    neg ax
    mov [line_dy], ax

    add ax, [line_dx]
    mov [line_err], ax

.next:
    mov bh, [line_row]
    mov bl, [line_col]
    call draw_point

    mov al, [line_col]
    cmp al, [line_end_col]
    jne .step
    mov al, [line_row]
    cmp al, [line_end_row]
    je .done

.step:
    mov ax, [line_err]
    add ax, ax                 ; e2 = 2 * err

    cmp ax, [line_dy]
    jl .skip_col
    mov bx, [line_err]
    add bx, [line_dy]
    mov [line_err], bx
    mov bl, [line_sx]
    add [line_col], bl
.skip_col:
    cmp ax, [line_dx]
    jg .next
    mov bx, [line_err]
    add bx, [line_dx]
    mov [line_err], bx
    mov bl, [line_sy]
    add [line_row], bl
    jmp .next
.done:
    ret

; input: BH=row, BL=col
draw_point:
    call cell_offset
    mov ax, [draw_word]
    cmp ax, 0
    je .erase

    mov al, [draw_phase]
    inc byte [draw_phase]
    cmp byte [draw_phase], 5
    jb .phase_ready
    mov byte [draw_phase], 0
.phase_ready:
    cmp al, 0
    je .full

    mov ax, 0x0800             ; bytes: 00 08
    or word [es:di], ax
    add di, 2
    mov ax, 0x0800             ; bytes: 00 08
    or word [es:di], ax
    ret
.full:
    mov ax, 0xffff
    or word [es:di], ax
    add di, 2
    or word [es:di], ax
    ret
.erase:
    stosw
    stosw
    ret

; input:  BH=row, BL=col
; output: DI=byte offset in active color plane
cell_offset:
    xor di, di

    mov al, bh
    xor ah, ah
    mov cx, COLS * 4           ; 4 bytes per cell, 80 cells per row
    mul cx                     ; AX = row * (COLS*4)
    mov di, ax

    xor ax, ax
    mov al, bl
    shl ax, 1                  ; col * 2
    shl ax, 1                  ; col * 4
    add di, ax
    ret

frame    db 0
render_frame dw 0
play_frame dw 0
next_frame dw 0
frame_offset dw 0
pin_row  dw PIN_START_ROW << FP_SHIFT
pin_col  dw START_COL << FP_SHIFT
draw_word dw 0xffff
draw_phase db 0
rows     times POINTS dw 0
cols     times POINTS dw 0
old_rows times POINTS dw 0
old_cols times POINTS dw 0
screen_rows times POINTS db 0
screen_cols times POINTS db 0
prev_screen_rows times POINTS db 0
prev_screen_cols times POINTS db 0
next_screen_rows times POINTS db 0
next_screen_cols times POINTS db 0
line_row     db 0
line_col     db 0
line_end_row db 0
line_end_col db 0
line_sx      db 0
line_sy      db 0
line_dx      dw 0
line_dy      dw 0
line_err     dw 0
link_dx      dw 0
link_dy      dw 0
abs_dx       dw 0
abs_dy       dw 0
link_dist    dw 0
link_error   dw 0
move_col     dw 0
move_row     dw 0
frame_rows   times PRE_FRAMES * POINTS db 0
frame_cols   times PRE_FRAMES * POINTS db 0
```
