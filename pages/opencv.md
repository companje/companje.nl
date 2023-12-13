---
title: OpenCV
---

# arrow keys
```python
key = cv2.waitKeyEx(0)  
if key == 27:
    break
elif key == 2555904: #right
    print("right")
elif key == 2424832: #left
    print("left")
```
# filter pixels based on number of neighbours
```python
_, labeled_image = cv2.connectedComponents(fg)
unique_labels, label_counts = np.unique(labeled_image, return_counts=True)
fg[label_counts[labeled_image] < 4] = 0
```

# sharpen
```python
kernel = np.array([[-1,-1,-1], [-1,9,-1], [-1,-1,-1]])
gray = cv2.filter2D(gray, -1, kernel)
```

# remove lonely pixels
```python
kernel = np.array([[1, 1, 1],[1, 0, 1],[1, 1, 1]])
convolved_image = convolve2d(gray, kernel, mode='same', boundary='wrap')
no_neighbor_mask = convolved_image == 0
gray[no_neighbor_mask] = 0
```

# replace pixels with value 0 by value 255 using numpy
```python
img[img == 0] = 255
```

# convert gray to rgb
```python
rgb = cv2.cvtColor(gray, cv2.COLOR_GRAY2RGB)
```

# homography
```python
import cv2
import numpy as np

# Lees de puntparen in (LET OP OMGEWISSELT)
dst_points = np.loadtxt("data/src_points.txt")
src_points = np.loadtxt("data/dst_points.txt")

# Bereken de projectieve transformatiematrix (homografie)
homography_matrix, _ = cv2.findHomography(src_points, dst_points)

print(homography_matrix)

# Pas de homografie toe op een afbeelding
src = cv2.imread("data/vis_200.png")
dst = cv2.warpPerspective(src, homography_matrix, (src.shape[1], src.shape[0]))

cv2.imshow("src", src)
cv2.imshow("dst", dst)
cv2.waitKey(0)
cv2.destroyAllWindows()
```

# mouse
```python
def mouse_callback(event, x, y, flags, param):
    if event == cv2.EVENT_LBUTTONDOWN:
        print(f"LEFT ({x}, {y})")
    elif event == cv2.EVENT_RBUTTONDOWN:
        print(f"RIGHT ({x}, {y})")
    elif event == cv2.EVENT_MOUSEMOVE:
        print(f"MOVE ({x}, {y})")

## ...
cv2.setMouseCallback("img", mouse_callback)
```

# new rgb image
```python
img = np.zeros((480, 640, 3), dtype=np.uint8)
```

# set focus to image window opencv
```python
cv2.namedWindow('img',cv2.WINDOW_NORMAL)
cv2.setWindowProperty('img',cv2.WND_PROP_FULLSCREEN,cv2.WINDOW_FULLSCREEN)
cv2.setWindowProperty('img',cv2.WND_PROP_FULLSCREEN,cv2.WINDOW_NORMAL)
cv2.imshow('img', img)
cv2.waitKey()
cv2.destroyWindow('img')
```

# sample images in opencv github repo
https://github.com/opencv/opencv/tree/master/samples/data

# see also
[[openFrameworks]], [[Processing]]

# Notes on findContours
  * <del>Source image is modified by this function. </del>
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
