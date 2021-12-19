---
title: OpenCV
---

# sample images in opencv github repo
https://github.com/opencv/opencv/tree/master/samples/data

# see also
[[openFrameworks]], [[Processing]]

# Notes on findContours
  * Source image is modified by this function. 
  * Also, the function does not take into account 1-pixel border of the image (it's filled with 0's and used for neighbor analysis in the algorithm), therefore the contours touching the image border will be clipped.

# Subtract vs absdiff
Subtract can be useful instead of absdiff if you want only difference when something 'appears or moves' and not when it 'disappears'

# Optical Flow
* use Farneback for a dense flow field, (voor een dense flow field met een vector per row/col)
* use PyrLK for specific features (voor feature herkenning. kan objecten volgen)

# Thresholding
* http://docs.opencv.org/3.1.0/d7/d4d/tutorial_py_thresholding.html#gsc.tab=0

# Various
* http://indranilsinharoy.com/2012/11/01/installing-opencv-on-linux/
* see also: [[cv]]
* matrix set all values to 0: ```mat.setTo(0);```
* background subtraction: http://www-staff.it.uts.edu.au/~massimo/BackgroundSubtractionReview-Piccardi.pdf
* [[openFrameworks]]
* http://code.google.com/p/cvblob/
* http://docs.opencv.org/modules/imgproc/doc/structural_analysis_and_shape_descriptors.html
