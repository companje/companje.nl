---
title: RGB files
---
see also [[pgm]]

====Analyse RGB file with Processing====
* <https://gist.github.com/a70fe3b13e6269b1238f>
* <https://github.com/companje/RGB-File-Analyzer>

====RGB 565====
16 bits color
* <https://msdn.microsoft.com/en-us/library/windows/desktop/dd390989(v=vs.85).aspx>

decode:
```c
char r = char(((c & 0xF800) >> 11) << 3);
char g = char(((c & 0x7E0) >> 5) << 2);
char b = char(((c & 0x1F)) << 3);
```

encode:
```c
WORD pixel565 = (red_value << 11) | (green_value << 5) | blue_value;
```

