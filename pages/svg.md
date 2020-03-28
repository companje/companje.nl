---
title: SVG
---

# SVGnest
* [[https://github.com/Jack000/SVGnest|SVGnest (SVG’s efficiënt stapelen voor de lasercutter)]]

# Optimize SVG's
http://petercollingridge.appspot.com/svg-editor

# Tips van Peter
Algemeen:
http://www.webdesignerdepot.com/2015/01/the-ultimate-guide-to-svg/
http://tympanus.net/codrops/2013/11/27/svg-icons-ftw/

Animation
https://css-tricks.com/svg-shape-morphing-works/
https://css-tricks.com/guide-svg-animations-smil/
http://tutorials.jenkov.com/svg/svg-animation.html

http://slides.com/sarasoueidan/styling-animating-svgs-with-css--2

Buttons
http://metafizzy.co/blog/making-svg-buttons/
http://tavmjong.free.fr/blog/?p=36

Responsive icons
http://designmodo.com/responsive-icons/

Exporting a SVG file from Illustrator
fontastic.me/faq
http://www.adobe.com/inspire/2013/09/exporting-svg-illustrator.html
Export multiple script: http://blog.iconfinder.com/how-to-export-multiple-layers-to-svg-files-in-adobe-illustrator/

SVG's als CSS background-image:
http://caniuse.com/#feat=svg-css
http://www.smashingmagazine.com/2012/01/resolution-independence-with-svg/ (edited)

# Elastic SVG elements* http://tympanus.net/Development/ElasticSVGElements/button.html

# Clipart
* https://openclipart.org/

# Online SVG editors
* [[http://www.janvas.com/site/home_en.php|Janvas]]
* [[http://www.drawsvg.org/|DrawSVG]]
* [[http://svg-edit.googlecode.com/svn-history/r1771/trunk/editor/svg-editor.html|svg-edit]] ([[https://code.google.com/p/svg-edit|googlecode]])
* !!! [[http://editor.method.ac/|editor.method.ac]] ([[https://github.com/duopixel/Method-Draw|github]])

# SVG coordinate system interactive
* http://sarasoueidan.com/demos/interactive-svg-coordinate-system/index.html

# Links
* http://snapsvg.io/
* http://svgjs.com/
* http://documentup.com/wout/svg.js#plugins
* [[http://jsbin.com/ehayep/65/edit#javascript,live|SVG tekening van enkele Globe4D onderdelen]]
* [[http://jsbin.com/ehayep/67/edit#javascript,live|Recentere versie van mijn SVG editor gebaseerd op SVGKit]]
* http://raphaeljs.com/
* http://ianli.com/sketchpad/
* http://doodle3d.com/help/svg
* https://sketch.io/sketchpad/

# interactive svg with gestures on iPad
* http://techblog.floorplanner.com/interactive-svg-on-the-ipad/
* http://lab.floorplanner.com/ipad/touch.svg
* http://interactjs.io/

# animated lines
* http://jakearchibald.com/2013/animated-line-drawing-svg/
* http://css-tricks.com/svg-line-animation-works/
* http://24ways.org/2013/animating-vectors-with-svg/
* http://product.voxmedia.com/2013/11/25/5426880/polygon-feature-design-svg-animations-for-fun-and-profit
* http://tympanus.net/codrops/2013/12/30/svg-drawing-animation/

#  Creating WordArt font for Doodle3D
  * Type the following string in InkScape, with a height of about 37.346 pixels:
```!"#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~```
  * Object > Object to path
  * Ungroup
  * Select all
  * Edit path by nodes
  * Select all
  * Add segments 3 / 4 times
  * Save and open in Illustrator
  * Simplity with straigt lines
  * Save and open in Inkscape
  * Save to svg, with points in 2 decimals
  * Open svg in text editor with regular expression support
  * remove path's: id, inkscape, sodipodi:nodetypes
  * Save
