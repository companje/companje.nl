---
title: Javascript
layout: default
---

# disable ipad zoom gesture
https://stackoverflow.com/questions/37808180/disable-viewport-zooming-ios-10-safari

```js
document.addEventListener('touchmove', function (event) {
  if (event.scale !== 1) { event.preventDefault(); }
}, false);
```

# disable ipad doubletap for zoom gesture
```js
var lastTouchEnd = 0;
document.addEventListener('touchend', function (event) {
  var now = (new Date()).getTime();
  if (now - lastTouchEnd <= 300) {
    event.preventDefault();
  }
  lastTouchEnd = now;
}, false);
```

# Download
```js
//download
var element = document.createElement('a');
element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
element.setAttribute('download', "payments.csv");
element.style.display = 'none';
document.body.appendChild(element);
element.click();
document.body.removeChild(element);
```

# Chart.js 
Simple yet flexible JavaScript charting for designers & developers
* http://www.chartjs.org/

# GPU Accelerated JavaScript 
*http://gpu.rocks/

# Parallel processing
Interessante technieken voor als we met de app meer parallel willen gaan doen, zoals die floodfill:
http://www.htmlgoodies.com/html5/client/using-web-workers-to-improve-performance-of-image-manipulation.html
https://developer.mozilla.org/en-US/docs/Web/API/Worker/terminate
De toekomst ziet er nog beter uit:  
https://hacks.mozilla.org/2016/01/webgl-off-the-main-thread/
https://hacks.mozilla.org/2016/05/a-taste-of-javascripts-new-parallel-primitives/

# Local Storage
Klein browser storage onderzoekje afgerond, conclusies en links:

​*Local storage*​: Limited from 2 MB (android browser) to 10 MB (besides opera there is no option to expand this). There is no nice way to check the available space (left). Bad performance & synchronous. 
​*WebSQL*​: Deprecated, but supported on iOS and Android browser. Usually up to 50MB. 
​*IndexedDB*​: The future, but less supported (iOS8 has gotten buggy support). Generally at least 50 MB. More space can be requested. There is a polyfill that uses WebSQL.

Advies; Gebruik IndexedDB met Polyfill. Minder enge limieten, beschikbare ruimte is te controlleren, betere performance. Liefst via een library die het makkelijker maakt zoals Dexie.


http://www.html5rocks.com/en/tutorials/offline/quota-research/
http://www.smashingmagazine.com/2014/09/building-simple-cross-browser-offline-todo-list-indexeddb-websql/
http://stackoverflow.com/questions/3027142/calculating-usage-of-localstorage-space
IndexedDB Polyfill: https://github.com/axemclion/IndexedDBShim
IndexedDB wrapper: http://www.dexie.org/ (can work with polyfill)
http://diveintohtml5.info/storage.html
http://caniuse.com/#search=indexeddb
https://developer.mozilla.org/en-US/docs/Web/API/IndexedDB_API/Browser_storage_limits_and_eviction_criteria
https://developers.google.com/web/updates/2011/11/Quota-Management-API-Fast-Facts

# reducers
* http://devdocs.io/javascript/global_objects/array/reduce
<code javascript>
[0, 1, 2, 3, 4].reduce(function(previousValue, currentValue, index, array) {
  return previousValue + currentValue;
});
```

# immutable
* immutable.js

# Printable bulletlist
<code javascript>
<div id="data">
# Section 1
- Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
- Nam eu ligula eu felis placerat consectetur quis eu arcu. 
- Duis tempus ex nec bibendum consequat. Suspendisse at mi ut mi mattis mollis in a leo.

# Section 2
-  In mollis est nec tempor scelerisque. 
- Nulla augue velit, commodo in feugiat et, suscipit vitae nisi. Quisque augue justo.
- tincidunt quis mattis sit amet, tincidunt et felis.
</div>

<style>
  div#data {
    display: none;
  }
  table {
    border: 1px solid black;
    width: 100%;
    width: 800px;
    page-break-inside:avoid;
  }
  td,th {
    text-align: left;
    font-family: Abel;
    border: 1px solid black;
  }
  h2 {
    font-family: 'Arial Black';
    font-size: 30px;
  }
  th {
    font-size: 30px;
    padding: 10px 10px 10px 10px;
  }
  table {
    margin-bottom: 30px;
    border-spacing: 0;
    border-collapse: collapse;
  }
  td.section {
    font-size: 24px;
    font-family: 'Arial Black';
  }
  td {
    width: 25%;
    padding: 10px 10px 10px 10px;
  }
  @media print {
    div.section {page-break-after: always;}
  }
  .color0 { background-color: #fdf; }
  .color1 { background-color: #dff; }
  .color2 { background-color: #fdd; }
  .color3 { background-color: #dfd; }
  .color4 { background-color: #ddf; }
  .color5 { background-color: #ffd; }
  .color6 { background-color: #fcc; }
  .color7 { background-color: #cff; }
  }
</style>

<script type="text/javascript">
  var sections = [];
  var lines = data.innerHTML.split('
');
  for (var i in lines) {
    if (lines[i].charAt(0)=='#') sections.push({title:lines[i].substr(2),items:[]});
    if (lines[i].charAt(0)=='-') sections[sections.length-1].items.push(lines[i].substr(2));
  }

  for (var i in sections) {
    var section = sections[i];
    document.write('<div class="section color'+i+'">');
    document.write('<h2>'+section.title+'</h2>');
    for (var j in section.items) {
      var item = section.items[j].trim();
      document.write('<table><tr><th colspan=4>'+item+'</th></tr><td class="section">'+section.title+'</td><td>Prioriteit:</td><td>Tijd:</td><td>Sprint #</td><//tr></table>');
    }
    document.write('</div>');
  }

</script>
```
# React.js
see [[reactjs]]

# Peter's presentation=
* [[https://docs.google.com/presentation/d/1AqDrZH5RyiYpTeoCbevKUHNh_YaPX05vOjLBL8FFv8E/edit#slide=id.p|Peter's presentation]]

# Babel playground
https://babeljs.io/repl/

# blogpost React on ES6 plus
http://babeljs.io/blog/2015/06/07/react-on-es6-plus/

# ES6 / ES7 presentation video
https://www.youtube.com/watch?v=6AytbSdWBKg

# Javascript in 2015
https://www.youtube.com/watch?v=iukBMY4apvI
* http://voxel.js
* [[http://curran.github.io/screencasts/jsModulesAndBuildTools/examples/viewer|voorbeelden van formats en tools]]
* browser-sync (webserver met ingebouwde watch die ook de browser refresht)
* serve: `sudo npm install -g serve` (simpelere webserver)
* `jspm init`
* atom
* `jspm install npm:voxel-demo`

# rendering
* http://www.graphycalc.com/
* http://ego.deanmcnamee.com/pre3d/
* http://canonical.org/~kragen/sw/torus.js

# Array filter
<code javascript>
function removeShortPaths(minLength,minPoints) {
  if (!minLength) minLength = 10;
  if (!minPoints) minPoints = 1;

  path.setPolylines(path.getPolylines().filter(function(polyline) {
    return polyline.getPoints().length>minPoints && polyline.getPerimeter()>minLength;
  }));

}
```

# compile c code to javascript
* http://kripken.github.io/emscripten-site/

# objectInfo
  console.log(JSON.stringify(result, null, 4));

# future of javascript (ES6)
talk at FOSDEM 15: Hannes Verschore (http://H4writer.com)

# compilers
* traceur
* 6to5

===generators
generate a sequence, one item at a time. a function that can be paused in the middle
<code javascript>
function *foo() {
   console.log("running");
   var x = yield 1;
   var y = yield 2;
   return 3;
}
var generator = foo();
//generator = { next: function() { }, throw: function() { } }
var result1 = geneator.next();
//console: "running"
//result =  {value:1, done:false}
var result 2 = generator.next();
//result = {value: 2, done: false}
var result3 = generator.next();
//result3 = {value:3, done:true }

var result 2= generator.next(10);
//result = {value:2, done:false}
var result = generator.next(20);

function *range(start,end) {
  while(start!=end) {
    yield start;
    start++;
  }
}

for (let i of range(0,10)) {
  console.log(i);
}
//result
>0
>1
>2
>......

function *fibonacci() {
....
}
```

# classes
(with inheritance)
<code javascript>
class Animal() {
  constructor() {
    this.cuteness = 0
  }
  approach() {
    //...
  } 
  get fullness() {
    return 100-this.cuteness; //...
  }
  set
.......
```

# arrow function
(alternative for 'self' and/or 'bind')
<code javascript>function Archer() {
this.arrows = 100;
setInterval( () => {
this.arrows--;
}, 5 * 10000);
.....
```

# destructuring
lists:
<code javascript>
var [a,b] = [1,2];

var [a,b, ...c] = [1,2,3,4,5]
//result a=1, b=2, c=[3,4,5]

var { data:var1 } = {data : 50 }
//var1 = 50

var {m:month, y:year } =  { d:1, m:2,y:2015}
//month=2, year=2015

var { data }= { data:50 } 
//result: data=50

function g({name:x}) {
  console.log(x);
}
g({name:5, foo:0})
//console: 5

var [a] = []
a===undefined
```

# modules
///lib.js
var privateProperty =1 ;
export var publicProperty = 2

function privateFunction() { ... }
export function publicFunction() { ...}
export default function() { ... }

/////main.js
import { publicProperty, publicFunction } from 'lib';
//..........

import (publicFunction  as libfunc } from 'lib'
//libfunc = function() {....}

module loader: 
System.import('some_module')
//returns a promise? that loads the module async

# promises
<code javascript>
function executor(resolve, reject) {
  ////

  if (....) reject();
  
  resolve(); //if success
}

img.then(function() {
  return imgs[1].getReadyPromise(); 
}).then(function() {
  ...
});

//Promise.all...... ?
```

# inheritance with prototypes
<code javascript>
var Animal = function(name) {
  this.name= name;
}
Animal.prototype.breath = function() {
  //do breath
}

Dog.prototype = Object.create(Animal.prototype);
Dog.prototype.bark = function() {
  //do bark
}
```
call the Animal super constructor from the Dog constructor
<code javascript>
var Dog = function(name,color) {
  Animal.call(this,name);
  this.color = color; //where to declare color?
}
```
#  ECMAScript 6 
* http://stackoverflow.com/questions/6506519/ecmascriptharmony-es6-to-javascript-compiler
* http://peter.michaux.ca/articles/javascript-is-dead-long-live-javascript
* https://github.com/google/traceur-compiler/wiki/LanguageFeatures

# FileReader
* [[https://developer.mozilla.org/en-US/docs/Web/API/FileReader.readAsDataURL|readAsDataURL!!!]]

# libraries & frameworks
* [[http://trackingjs.com|tracking.js, video processing/tracking library]]
* [[https://code.google.com/p/dat-gui/|dat.GUI]] A lightweight graphical user interface for changing variables in JavaScript.
* [[http://somajs.github.io/|soma.js]]
* [[http://phaser.io/|phaser.io]] gaming framework
* [[http://www.soundstep.com/blog/experiments/jstracking/js/libs/matrix.js|matrix.js]]
* [[http://fhtr.org/JSARToolKit/|jsARtoolkit]]
* [[https://github.com/kig/magi|magi / glMatrix.js]] - High performance matrix and vector operations for WebGL
* [[https://raw.githubusercontent.com/mrdoob/three.js/master/examples/js/libs/tween.min.js|tween.js]]
* [[https://raw.githubusercontent.com/mrdoob/three.js/master/examples/js/renderers/CSS3DRenderer.js|CSS3DRenderer.js for three.js]]
* [[https://chili-research.epfl.ch/AR.js/chilitags.js/|chillitags]] AR library

# yeoman
Yeoman helps you start new projects, prescribing best practices and tools to help you stay productive.
* http://yeoman.io/

#  online IDEs 
* http://codepen.io/
* http://sketchpad.cc/
* http://www.openprocessing.org/
* http://jsfiddle.net/

#  creative coding framework 
* http://soulwire.github.io/sketch.js/

# webworkers
* http://www.w3schools.com/html/html5_webworkers.asp

# EcmaScript 6
Zeer interessant praatje over de toekomst van Javascript, de nieuwe versie: EcmaScript 6. Dingen als generators, arrow functions, template strings. Veel hiervan is nu al bruikbaar door compilers als Traceur.  
https://www.youtube.com/watch?v=mPq5S27qWW8

# Over ES6 compilers 
* http://stackoverflow.com/questions/6506519/ecmascriptharmony-es6-to-javascript-compiler
* https://github.com/google/traceur-compiler
# safely access nested properties in object
*http://stackoverflow.com/questions/18178406/access-javascript-nested-objects-safely

# assertType function
<code javascript>
function assertType(variable,typename) {
  if (typeof variable  typename) return true;
  var s = [].splice.call(arguments,2).join(' ');
  console.error(s+': Unexpected type: '+typeof variable+', expected: '+typename);
  return false;
}
```
This one is better:
<code javascript>
function assert(a,b) {
  var a = Array.prototype.slice.call(a);
  var c = Array.prototype.slice.call(arguments,2).join('.');
  for (var i in b) if (typeof a[i]!=b[i]) throw new TypeError(c+" argument["+i+"] should be "+b[i]+" ");
}
```


# Scaling down an image to fit in a element of a certain size
<code javascript>
  var iw = img[0].naturalWidth;
  var ih = img[0].naturalHeight;
  var scale = 1;

  //scale based on aspectratio
  if (iw>ih) {
    scale = canvas.width() / iw;
  } else {
    scale = canvas.height() / ih;
  }

  //scale further down if still too high
  if (ih*scale > canvas.height()) {
    scale = canvas.height() / ih;
  }

  img.width(iw*scale);
  img.height(ih*scale);
```

# lectures
* https://www.destroyallsoftware.com/talks

# JavaScript closure inside loops – simple practical example
* http://stackoverflow.com/questions/750486/javascript-closure-inside-loops-simple-practical-example


# Access-Control-Allow-Origin
```
header("Access-Control-Allow-Origin: *");
```

# more
* http://doodle3d.com/help/javascript-optimization
* http://msdn.microsoft.com/en-us/scriptjunkie/ee819093
* http://raphaeljs.com/
* paperjs

# classes (the old way)
http://doodle3d.com/help/simple-class-pattern
En de pagina's in het algemeen onder:
http://doodle3d.com/help/javascript

# disable dragging
<code javascript>
function disableDragging() {
  $(document).bind("dragstart", function(event) {
    console.log("dragstart");
    event.preventDefault();
  });
}
```

# getParameterByName
<code javascript>
function getParameterByName(name) {
    var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}
```
