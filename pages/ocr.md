---
title: OCR
---

# print parameters
```bash
tesseract --print-parameters
```

#  just supply multiple 'parameter'/'config' files.
those files are in 'tessdata' folder. can be found with `find / -name tessdata`
in my case on OSX : 
`./usr/local/Cellar/tesseract-lang/4.0.0/share/tessdata`
`./usr/local/Cellar/tesseract/4.1.1/share/tessdata`
`./usr/local/share/tessdata`

```bash 
tesseract INPUT.JPG OUTPUT_BASE -l nld tsv get.images
```

# get.images gives the intermediate/input image useful for debugging
```bash 
tesseract INPUT.JPG OUTPUT_BASE -l nld tsv get.images
```
it writes to: `tessinput.tif`
 
## tesseract in Python
```python
#!/usr/bin/env python3

from PIL import Image
import pytesseract
print(pytesseract.image_to_data('filelist.txt'))
```

## compressed pdf
* first compress images using mogrify -quality 40
* use tesseract with 'filelist.txt'

## OCRAD
```bash
brew install ocrad
convert 14.16.12.png img.ppm
ocrad img.ppm > output.txt
```

## or 
```bash
brew install netpbm
pngtopnm filename.png | ocrad
```

## tesseract
```bash
brew install tesseract
brew install tesseract-lang

tesseract NL-UtHUA_A356828_000002.jpg outfile -l nld tsv
tesseract NL-UtHUA_A356828_000002.jpg outfile -l nld pdf
```

## other
* http://www.free-ocr.com/
* http://projectnaptha.com javascript library en chrome addon voor OCR van plaatjes - tip v Simon (d3d)
* tesseract
* 
