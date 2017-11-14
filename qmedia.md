---
title: QMedia TV
---

* USB lijkt alleen 'oude' MPG te ondersteunen.

Dit lijkt de oplossing:
<code>
  ffmpeg -i <movie.avi> -target pal-vcd <filename.mpg>
</code>
