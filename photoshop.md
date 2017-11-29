---
title: =====Photoshop=====
---

==Paths==
* hold spacebar while drawing a path (for example an ellipse) to move it
* ''A'' for ''Path Selection Tool'' or ''Shift+A'' to toggle between ''Path Selection Tool'' and ''Direct Selection Tool'' to select points.
* ''Ctrl+T'' = ''Free Transform Path''
* ''Window'' > ''Paths'' to get the ''Paths Panel'' beside the ''Layers Panel''

* To create outline from bitmap make selection with the ''Magic Wand Tool'' (''w''), then right-click > ''Make Work Path''
* Use ''Quick Mask'' (''q'') while creating selections with the ''Magic Wand Tool'' to see the result better and use ''Brush'' to fix some pixels for better selection.

* to create another work path from a selection rename the first work path in paths palette, then make another work path.
* ''Path Operations'' in ''Path Properties'' are very useful: Combine, Subtract, Intersect, Exclude.
* nice tutorial from Lynda.com: https://www.youtube.com/watch?v=bt5nw8fco74

==SVG export from Photoshop==
Starting from Photoshop CC 14.2, you can create SVG files directly from Photoshop:

* Create a file named ''generator.json'' with the content below in your user home folder.
* Restart Photoshop and open your PSD file.
* Activate the generator: ''File > Generate > Image Assets''.
* Rename your layer to <something>.svg.
* The svg file will be created in the assets directory next to your PSD file.
Content for ''generator.json'':
```
{
    "generator-assets":  { 
        "svg-enabled": true
    }
}
```
Source: http://stackoverflow.com/questions/5413719/photoshop-custom-shape-to-svg-path-string
Source: http://creativedroplets.com/generate-svg-with-photoshop-cc-beta/

==Panels==
* You can have larger thumbnails in your panels by using ''Panel Options'' from the panel's toolbar.



==inside stroke==
use ''Stroke'' -> ''Inside Blending Option'' to create thicker lines for example for country borders

==create replace black background by transparent==
* http://graphicdesign.stackexchange.com/questions/2549/photoshop-cs5-setting-a-black-background-to-transparent
