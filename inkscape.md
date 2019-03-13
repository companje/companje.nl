---
title: Inkscape
---

# how to set the color of a pattern
(edit the XML)
https://graphicdesign.stackexchange.com/questions/15580/how-can-i-color-a-pattern-in-inkscape

# fill patterns
how to adjust the pattern using the 'node tool':
http://www.inkscapeforum.com/viewtopic.php?t=4756

# Hershey Text
Single line font rendering for CNC / Laser
Hershey Text is built into Inkscape as of version 0.91.0
> Extensions > Render > Herschey Text

# fonts
* http://understandingfonts.com/blog/2011/11/typography-extensions-in-inkscape-0-49/# stempelen
tijdens slepen kun je met spatie duplicaten van je selectie achterlaten

# keep stroke-width while scaling
Rechtsboven in de toolbar staan 3 'Affect' knopjes. De eerste uitzetten.

# probleem: copy-paste converteert naar bitmap
Oplossing: In X11 preferences het volgende UIT zetten:
  Update Pasteboard when CLIPBOARD changes

# online center line trace
http://www.roitsystems.com/cgi-bin/autotrace/tracer.pl

# inkscape without X11
* http://caih.org/open-source-software/fixing-inkscape-in-mac-os-x/

# Inkscape crashcourse TIP!
* http://www.chrishilbig.com/a-crash-course-in-inkscape/

# The fantastic lxml wrapper for libxml2 is required (on Mac OSX)
This might work:
edit /Applications/Inkscape.app/Contents/Resources/bin/inkscape

add:
```
export VERSIONER_PYTHON_VERSION=2.6
```
just before:
```
export VERSIONER_PYTHON_PREFER_32_BIT=yes
```

# Connect multiple lines
* http://superuser.com/questions/137887/inkscape-how-to-connect-several-lines-and-arcs-into-one-continuous-path

# Sneltoetsen
  * Ctrl+Shift+F voor het Fill & Stroke panel
  * Ctrl+Shift+X = xml editor
  * Ctrl+Shift+K = break apart path
  * Ctrl+K = combine path
  * Ctrl+Shift+A = align and distribute
  * Ctrl+Shift+D = document settings (always set default units to mm)
  * Ctrl+G = group
  * + en - = in en uitzoomen
  * #  grid aan/uitzetten
  * | = snap to guides aan/uitzetten

# Stroke venster aanzetten
Klikken op de stroke color linksonder in de statusbalk. Of Ctrl+Shift+F.

# Snap to things
View->Show/Hide->Snap Controls om de toolbar aan te zetten voor 'snap' knopjes.

# Guides
Er zijn ook diagonale guides. Je kunt dubbelklikken op een guide om cijfers in te typen.

# Affect rechtsboven in toolbar
Bij resizen kun je met Affect aangeven dat ie bijv de lijndikte met rust moet laten.

# accurate dimensions and linear extrude to OpenSCAD
* draw a rect
* type in 0 for x and 0 for y
* stroke width 1mm
* draw two circles / ellipses
* set width and height to 5mm
* position them roughy on the rect
* for left circle make x 10
* for right circle make x 90
* (circles are not centered)
* make y for both circles the same
* group the two circles
* use align center horizontal and vertically

**next step is to extrude**
* ([[OpenSCAD]] cannot do bezier curves yet so converting a circle to a path is not enough). You need to save the dxf with only straight lines.

* ungroup the circles
* set a fill color for rect
* select rect first and than one of the circles
* then do a difference via the (?) menu
* select all nodes 
* menu extensions modify path - add nodes
* make length 2 pixels (it makes a lot of nodes)
* now there are still curves only shorter ones. You now need to make straight lines of it by using the 'make segment lines' button on the toolbar.
* export as DXF (with units mm)

[[OpenSCAD]] code:
```linear_extrude(file = "file.dxf", height=5);```

# templates (for bolds ie.)
* make a clone through the edit menu
* create tiled clones
* it's hard to find the original one (but there is a menuitem to find it)
* it's good to keep track of the original. put it somewhere else.

# grid
there's a grid in inkscape

# non-destructive boolean operators?
* not really possible
* but you can make copies and use layers..

# swap Command & Control buttons on OSX
* http://www.bohemianalps.com/blog/2008/x11-control2command/

# lxml // libxml / libxml2 
* this did not help for me: http://caih.org/open-source-software/fixing-inkscape-in-mac-os-x/
