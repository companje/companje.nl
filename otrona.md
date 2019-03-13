---
title: Otrona Attache
---

#  Keyboard 
* [[https://deskthority.net/keyboards-f2/otrona-attache-keyboard-fujitsu-switches-t11728.html|...Fujitsu Leaf Spring 1st Generation...]]
* http://bitsavers.trailing-edge.com/pdf/otrona/Otrona_Attache_Technical_Manual_Jul83.pdf

# Keyboard
The Attache keyboard is a full alphanumeric key set laid out in the IBM Selectrictm keyboard configuration. Additionally, cursor movement, delete, and multi-function keys are located on the keyboard.
A 16 keystroke buffer handles the burst speed that can occur with short
words. The key stroke speed capability is calculated at approximately 60
key strokes per second, or 720 words per minute. •
All keys will enter an auto-repeat mode if depressed for more than 1/2 second. This allows the user efficient data entry and system control capability. The repeat speed during auto-repeat is easily adjusted, on the rear of the keyboard, to the optimum needs of each user.
The audible feedback of the keys are adjustable via Set-up Mode's "volume" and "click" options.
The keyboard's construction makes the keys virtually impervious to contamination.
The keyboard is designed to connect to the system iri a modular fashion. The connector on the system and the keyboard uses a telephone-style, 4- wire modular connector. A standard phone coil cord can be used to provide up to 10 feet of keyboard distance.

# Multi-Function Keys
Keys on the top row of the keyboard are used to perform several functions in addition to numeric and special character typewriter functions. Multi-functions are activated by pressing two or more keys at the same time, as instructed by the keyboard template.
The keys which activate the multi-functions are the CTRL key, SHIFT and CTRL keys, and CTRL and ESC keys.

# 10-Key Mode
The Attache keyboard may be used as a 10-Key pad for entering columns of figures. Certain letter keys are converted to numbers when 10-Key Mode is activated and the letter keys are used in lower case.
10-Key Mode is activated by pressing CTRL and CAPS LOCK simultaneously. Press CAPS LOCK to return to upper case, or press CTRL and CAPS LOCK simultaneously to return to lowercase.

# Keyboard Interface
The I/O RTC section of the processor board contains the connector to the Attache keyboard (J502). Lines B6 and B7 of the PIO offer simple serial interface to the keyboard. Refer to "Keyboard" page 2-67 for additional logic information concerning the keyboard.
The keyboard logic monitors all the keys. When the logic detects a pressed key, it pulls data line 4 of connector J502 to a low level. U511 is a voltage comparator which senses the low signal from line 4, and sends a high signal to B6 of the PIO. The internal PIO logic recognizes the B6 signal as an indication of a pressed key. .
The processor monitors PIO line B6 to check for a character waiting. If a character is waiting, the processor sends clock pulses to PIO line B7, and serially reads the 7 keycode data bits (plus shift and control bits) on B6. The processor then translates these bits into an ASCII keycode, corresponding to the key pressed.
The voltage comparator (U511) is also used to detect system reset from the keyboard. The comparator checks for specific voltage levels from the keyboard. If the voltage level is below one and a half volts, this indicates a reset. The comparator then generates the signal RB Reset. The comparator also checks the POR (Power Okay) line and generates a PB Reset signal accordingly.
The POK line is delayed through a resistor - capacitor network to         the EPROM Enable, CMOS RAM, and RTC from being written to erroneously while the power level is changing.
Power for the keyboard is supplied by a +12 volt line on pin 2 of the keyboard connector. The line uses resistors to protect the keyboard.

# Keyboard
The keyboard is an Attache module that interfaces with the processor board through a four-wire connector (Jl). The major components of the keyboard module are the keyboard itself, a counter, a shift register, and an oscillator.

(::screen_shot_2016-02-20_at_21.21.52.png?nolink|)

# Theory of operations=

# Introduction
The keyboard is set up in a grid pattern. Two multiplexers are attached to the keyboard grid lines. The multiplexers receives a binary counter's output which sequentially enables each grid line.

(::screen_shot_2016-02-20_at_21.24.05.png?nolink|)

A pressed key creates a short to output a high signal that stops the counter and enables a shift register. The character code from the corresponding key loads into this register in parallel. System clock pulses shift the data bits of the keycode out serially to the processor, via the Parallel Input/Output controller (PIO).

# Basic Keyboard Logic
The NAND gate U5, combined with the resistor - capacitor components R9, RIO, and C9, form a 4 KHz oscillator. The oscillator drives a binary counter U8, and flip-flop U3, at 4 KHz.

The counter" inputs high pulses to twoS-channel analog multiplexer / demultiplexer. The multiplexers contain S bidirectional analog switches
and connect to sides of the keyboard grid. The counter's pulses sequentially enable each of the switches, which connect to a grid line
and to pin 3.

When a key is pressed a short is created between the multiplexers which outputs the high enable from the counter through the corresponding grid lines, to U9 pin 3.

This signal is gated into the flip-flop U3 (pin 6) by an oscillator clock pulse. The signal is inverted at U3 pin 2, so NOR gate U4 receives a low signal.

This low signal couples an oscillator clock pulse into the flip-flop, U6. The flip-flop (U6) is now set, indicating a key is down. This drives the load input (pin 9) of the shift register (D7) high which loads the key code from counter (U8).

U7 is a parallel-to-serial register. Pin 9 high latches parallel input; pin 9 low enables serial output. Each pressed key causes six data bits of keycode data to be loaded into the shift register (U7). Two more bits are added: one bit represents the status of the shift key, and the other represents the status of the control key.

U6 sends a signal to pin 2 of a NAND gate (U5) simultaneously with its load signal to U7. This pulls connector JI pin 4 to the PIO low, which signals the processor via the PIO that a key bas been pressed.

The processor sends system clock pulses through connector Jl pin 3 to U7 pin 10, and simultaneously sends reset pulses to U6. Reset sets the U7 shift register's parallel load pin 9 low, enabling serial output. The data "bits are clocked out U7 pin 3, through Jl pin 4 to the processor via the PIO.

# Additional Circuitry Functions
A feedback loop (U3 pin 2 to U4 pin 2), and a delay (C5 and R6) keep the state of U3 from changing too rapidly. This is an allowance for key bounce.

Key repeat is a function of the counter, U2. U2 starts counting when a key is pressed. After a short period of time (set by the potentiometer at the back of the keyboard) U2 drives its output high. The next clock pulse enables the flip-flop (U6). U3 pin 14 goes low, which gates the output of U2 pin 13 through gate U4. This generates a string of pulses. Each pulse sets U6, sending the key code held down to the processor.·

The line from the shift key goes through a diode. The diode prevents using the left Shift key and the Reset key to cause a system reset. (An accidental system reset from pressing both keys would otherwise be easy.)

(::screen_shot_2016-02-20_at_21.27.49.png?nolink|)

Additional information on keyboard layout is contained in the Software chapter of this manual. Refer to "Generating Keyboard Codes", page 3-53.

# Generation of Keyboard Character Code
The following charts show the physical keyboard layout and character codes. Two keys, control and shift, affect the code which generates when a key is pressed.
The character codes in the      and            tables are that of Wordstar. Multiple key strokes are automatically combined by the Wordstar version of character codes.
While in Wordstar mode, 6 keys are position dependent: the four arrow keys, CTRL A, and CTRL -. An up arrow is 05 {hex}, left arrow is 13
{hex}, right arrow is 04 {hex}, down arrow is 18 {hex},      A {hex}, and CTRL - is 86 {hex}.

(tables not included. See Technical Manual)

# Schematic
(::screen_shot_2016-02-20_at_21.39.40.png?nolink|)

# Connector
(:otrona_attache_keyboard_connector.png?nolink|)
