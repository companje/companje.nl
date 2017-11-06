---
title: Arduino Pong (lite) on TV
---
(  blog:2008:06:arduino-pong-television-signal.jpg?200|Arduino Pong Composite Television Signal)
I did some experiments with the Arduino board to let it generate television signal and play a pong game. I gave up at the point where I succeeded to have a ball bouncing to the walls, no player interaction yet but may be it's somehow useful for you. It shows at least that it is quite a job to get the timing right for generating the video signal. Since the Arduino chip is relatively slow it forces you to keep your code as compact as possible. Every micro second counts.

I compiled this code with a C compiler, so not from the Arduino IDE but you can easily translate it to Arduino by adding a setup() and a loop() function and pasting the code into there.

**Resources:**
*[[http://javiervalcarce.es/wiki/TV_Video_Signal_Generator_with_Arduino|TV Video Signal Generator with Arduino]] 
* [[http://javiervalcarce.es/wiki/Program_Arduino_with_AVR-GCC|Installing AVR-GCC for compiling and uploading C code to Arduino]]

<code c>
//Arduino-Pong-TV-signal experiment by Rick Companje, 9-June-2008

#define F_CPU 16000000L
#include <avr/io.h>
#include <util/delay.h>

//using digital output pins 6 & 7
#define sync(u)  PORTD = 0b00000000; usec(u);  // 0 0  0.0V - Sync - 0
#define black(u) PORTD = 0b01000000; usec(u);  // 0 1  0.3V - Black - 64
#define grey(u)  PORTD = 0b10000000; usec(u);  // 1 0  0.6V - Grey - 128
#define white(u) PORTD = 0b11000000; usec(u);  // 1 1  1.0V - White - 192

#define usec(u) _delay_us(u);
#define vsync() sync(28); black(5); sync(28); black(5);
#define eq() sync(4); black(28); sync(9); black(28);
#define hsync() sync(5); black(8);
#define vsync9() { eq(); eq(); eq(); vsync(); vsync(); vsync();  eq(); eq(); eq();  }

#define blankline() { hsync(); black(52); }
#define whiteline() { hsync(); white(52); }
#define greyline() { hsync(); grey(52); }
#define blanklines(n) { int i=n; while (i-->0) { blankline(); )
#define whitelines(n) { int i=n; while (i-->0) { whiteline(); )
#define greylines(n) { int i=n; while (i-->0) { greyline(); )

#define ball(a,b,c) hsync(); black(a); white(b); black(c); 

char bx1=20,bx2;
char by1=100,by2;
char dx=1,dy=1;

int main() {
	
	DDRD = 0xff; //set port d to output
	
	while (1) {

		//ball horizontal movement
		if (bx1>40 || bx1<0) dx=-dx; 
		bx1+=dx;
		bx2=40-bx1;

		//ball vertical movement
		if (by1>190 || by1<1) dy=-dy; 
		by1+=dy;
		by2=200-by1;
		
		vsync9(); //vertical sync, beam returns to left-top

		blanklines(45);  //offscreen top
		whitelines(14);  //p1
		blanklines(by1); //no ball
		ball(bx1,1,bx2); //ball
		blanklines(by2); //no ball
		whitelines(10);  //p2
		blanklines(18);  //offscreen bottom
	}
}
</code>

(tag>Tech Electronics C++ Programming Retro)


~~DISCUSSION~~
