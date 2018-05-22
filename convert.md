---
title: Convert images
---

# resize (sample) very large image file
```bash
convert -debug cache -limit area 1GB  /Users/rick/Downloads/Mars_MGS_MOLA_DEM_mosaic_global_463m.tif -sample 4096x2048 test.tiff
```

# create redgreen gradient palette 64k wide
```bash
convert -size 10x65535  -rotate -90  gradient:red-green  redgreen.png
```
# lookup colors in palette (not sure yet)
```bash
convert -interpolate bilinear /Users/rick/Downloads/mars-8k-16bpp-bump.tiff redgreen.png -clut -depth 8 out.png
```

# Convert multiple jpg's into bmp's
```bash
for i in *.jpg; do sips -s format bmp $i --out ../clouds-201509-bmp/$i.bmp;done
```

# Resize batch
first height, then width!
```bash
  for i in *.jpg; do sips -z 1024 2048 $i --out ../clouds-201509-2k/$i;done
```

# create gradient
```bash
convert -size 100x300 gradient:#000-#f00 gradient:#f00-#ff0 gradient:#ff0-#fff gradient:#fff-#0ff gradient:#0ff-#00f gradient:#00f-#000 -append lut.png
```

* more info http://www.imagemagick.org/Usage/canvas/#gradient_colorspace
