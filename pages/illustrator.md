---
title: Illustrator
---

# JSX code for generating a circular pattern with icons and titles
voor een fijne dev-workflow doe eerst het volgende:
* Maak een JSX-bestand met de volgende inhoud: `app.preferences.setBooleanPreference("ShowExternalJSXWarning", false)`
* Ga naar Bestand > Scripts > Ander script en kies het zojuist opgeslagen JSX-bestand.
* daarna kun je JSX bestand koppelen om altijd met Illustrator te openen.
* vervolgens kun je in SublimeText bijv. een shellscript met `open script.jsx` runnen bij Cmd+B.
* dan wordt de code direct uitgevoerd in Illustrator.

in de onderstaande code worden eerst het icoon en de title gemaakt. Dan worden die in een eigen groep gezet. En de groep wordt vervolgens gepositioneerd en geroteerd.

```js
#target illustrator

function deg2rad(d) { return d * Math.PI / 180; }
var white = new RGBColor(); white.red = 255; white.green = 255; white.blue = 255;

(function () {

  var doc = app.activeDocument;
  var layerName = "Radial Icons";
  var iconFolder = "/Users/rick/Globe4D/Ostrava/Ring gx800 ontwerp/iconen/";
  var iconFilenames = ["sun.png","mercury.png","venus.png","earth.png","moon.png","mars.png","jupiter.png","saturn.png","uranus.png","neptune.png"];
  var titles = ["Slunce","Merkur","Venuše","Země","Měsíc","Mars","Jupiter","Saturn","Uran","Neptun"];
  var center = { x: doc.width / 2, y: -doc.height / 2 };
  var angleOffset = 90 + 360/10*2;
  var radius = 1390;
  var iconScale = 50;
  var textOffsetY = -175;
  var textSize = 80;
  var embedPlaced = false;
  var num = iconFilenames.length;
  
  try { doc.layers.getByName(layerName).remove(); } catch(e) {}
  var layer = doc.layers.add();
  layer.name = layerName;

  for (var i = 0; i < num; i++) {
    //icon
    var icon = layer.placedItems.add();
    icon.file = new File(iconFolder + iconFilenames[i]);
    icon.name = "Icon";
    icon.position = [-icon.width/2, icon.height/2]; //[px - icon.width / 2, py + icon.height / 2];
    icon.resize(iconScale, iconScale, true, true, true, true, iconScale, Transformation.CENTER);

    //text
    var text = layer.textFrames.add();
    text.contents = titles[i];
    text.position = [0, textOffsetY];
    text.textRange.paragraphAttributes.justification = Justification.CENTER;
    text.textRange.characterAttributes.size = textSize;
    text.textRange.characterAttributes.textFont = app.textFonts.getByName("ArialMT");
    text.textRange.characterAttributes.fillColor = white;

    //move icon and text to its own group
    //and rotate the group
    var grpEl = layer.groupItems.add();
    grpEl.name = titles[i];
    text.move(grpEl, ElementPlacement.PLACEATEND);
    icon.move(grpEl, ElementPlacement.PLACEATEND);
    var angle = i/num * 360 + angleOffset;
    var px = center.x + radius * Math.cos(deg2rad(angle));
    var py = center.y + radius * Math.sin(deg2rad(angle));
    grpEl.position = [px - grpEl.width / 2, py + grpEl.height / 2];
    grpEl.rotate(angle+90, true, true, true, true, Transformation.CENTER);
  }
})();
```

# Shortcut keys
```
|''F7''|Show/hide Layers|
|''Cmd+Y''|Outline View|
|'', . /''|wisselen tussen 3 soorten fills (solid, gradient, transparent)|
|''Shift+c''|convert anchor point tool (wisselen tussen rechte hoeken of curves)|
|''p''|pen tool|
|''=''|(+) add anchor points|
|''-''|remove anchor points|
|''Cmd+U''|smart guides on/off|
|''Cmd+[ or ]''|arrange forward / backward within layer|
|''x''|kiezen of fill of stroke geselecteerd moet zijn|
|''Shift+x''|stroke/fill kleur omdraaien van het geselecteerde object|
|''Shift+Cmd+a''|Deselect|
|''Cmd+F10''|show/hide stroke panel|
|''r''|rotate tool|
|''o''|reflect tool|
|''s''|scale tool|
|''shift+O''|open artboard settings|
|Cmd+Shift+B|handles aan/uit o.a. om Free Transform te kunnen doen|
```

# Tips
 * Op de paper-size in te stellen kun je bij Document Properties -> Edit ArtBoards gebruiken. Klinkt als een omslachtige methode dus misschien is er een betere.
 * Als je iets geselecteerd hebt met de Direct Selection Tool (a) dan kun je met Cmd op het geheel een soort Free Transform doen.
