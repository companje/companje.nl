---
title: Convert images
---

# resize to longest axis 2048
```bash
mogrify -resize 2048 *.jpg
```

# resize to certain filesize
will take 4 to 8 times longer
```bash
convert INPUT -define jpeg:extent=2MB OUTPUT
```

# Convert to thumbs
```bash
mogrify -resize x400 *.jpg   # all jpg's in folder are resized to height 400px (overwriting originals)
```

# Convert 16 bit grayscale png to 16 bits raw
```bash
stream -map r -storage-type short earth-elevation.png earth-elevation.raw
```
or
```bash
convert input.png output.gray
```

# Convert 16 bit grayscale png to 2 separate 8 bits png's
there must be a better way...
```bash
convert input16bits.png 16.gray
convert -size 4096x2048 -endian MSB 16bits.gray -depth 8 8bits-hi.png
convert -size 4096x2048 -endian LSB 16bits.gray -depth 8 8bits-lo.png
```

# contrast-stretch / normalize 
```bash
convert earth-elevation.png -depth 16 -normalize earth.png
```

# convert pdf to png high quality
```bash
convert           \
   -verbose       \
   -density 150   \
   -trim          \
    test.pdf      \
   -quality 100   \
   -flatten       \
   -sharpen 0x1.0 \
    24-18.jpg
```

# convert from 16bit to red+green channel
(somehow this doesn't work for me anymore)
```bash
convert Mars_8k_16bit.tif -depth 24 mars24.rgb
convert -size 8192x4096 -depth 8 rgb:mars24.rgb mars_redgreen.png
```

# convert DDS to PNG
```bash
convert Mars-normalmap_8k.dds Mars-normalmap_8k.png
```

-------

# 1 resize (sample) very large image file
```bash
convert -debug cache -limit area 1GB  /Users/rick/Downloads/Mars_MGS_MOLA_DEM_mosaic_global_463m.tif -sample 4096x2048 test.tiff
```

# 2 create redgreen gradient palette 64k wide
```bash
convert -size 10x65535  -rotate -90  gradient:red-green  redgreen.png
# black-yellow
convert -size 1x65535 -rotate -90   gradient:black-yellow blackyellow.png
```
# 3 lookup colors in palette (not sure yet if it solves my problem)
```bash
convert -interpolate bilinear /Users/rick/Downloads/mars-8k-16bpp-bump.tiff redgreen.png -clut -depth 8 out.png
```

# works:
```bash
convert -size 1x65535 -rotate -90   gradient:black-yellow blackyellow.png
convert -interpolate bilinear Mars_8k_Disp_v001.tif blackyellow.png -clut -depth 8 out.png
```

-------

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
