---
title: OCR
---

# compressed pdf
* first compress images using mogrify
* use tesseract with 'filelist.txt'

# ...
* http://www.free-ocr.com/
* http://projectnaptha.com javascript library en chrome addon voor OCR van plaatjes - tip v Simon (d3d)
* tesseract

# OCRAD
```bash
brew install ocrad
convert 14.16.12.png img.ppm
ocrad img.ppm > output.txt
```

# or 
```bash
brew install netpbm
pngtopnm filename.png | ocrad
```

# tesseract
```bash
brew install tesseract
brew install tesseract-lang

tesseract NL-UtHUA_A356828_000002.jpg outfile -l nld tsv
tesseract NL-UtHUA_A356828_000002.jpg outfile -l nld pdf
```
