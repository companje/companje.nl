---
title: Basic
---
* Basic interpreter for Arduino: https://github.com/robinhedwards/ArduinoBASIC
* Altair Basic source explained: http://altairbasic.org/

# segments
https://www.tek-tips.com/faqs.cfm?fid=290
"Basically, memory is one long string of bytes, and the actual address of the byte being referred to is calculated on-the-fly by the processor as (segment * 16 + offset)." ... "Note that since segments overlap so much, you can usually take any segment:offset pointer, and add 1 to the segment while subtracting 16 from the offset, or subtract 1 from the segment while adding 16 to the offset."
