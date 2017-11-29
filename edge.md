---
title: Edge Animate
---

===== $ does not refer to jQuery in newer versions of Edge Animate=====
In newer versions of Adobe Edge, the $ symbol is a **limited** implementation of jQuery. If you want full jQuery add an external script to your library. (ie. from https://code.jquery.com/jquery-2.1.1.min.js)
[[http://www.adobe.com/devnet-docs/edgeanimate/api/current/index.html#edge_jquery|Read more]]

===== audio =====
  sym.$("oinkbeest-intro")[0].play();

=====show/hide/composition/stage/symbol====
<code javascript>
// hide this symbol
sym.getSymbolElement().hide();

// show the next symbol and play it
sym.getComposition().getStage().$("Symbol_2").show();
sym.getComposition().getStage().getSymbol("Symbol_2").play();
```

=====upload======
* http://stackoverflow.com/questions/793014/jquery-trigger-file-input

=====Javascript error in event handler! Event Type = element====
in case of these kind of unhelpful errors use an exception handler to get the actual error.
<code javascript>
try {
  sym.$("upload").trigger("click");
} catch (e) {
  console.log(e);
}
```

===== functions in symbols =====
<code javascript>
//on stage timeline:
sym.getSymbol("Pinguins").zingen();

//on symbol timeline
sym.zingen = function() {
  console.log("zingen");
}
```

=====javascript API=====
 * http://www.adobe.com/devnet-docs/edgeanimate/api/current/index.html

=====access nested symbols from action=====
<code javascript>
sym.getSymbol("Pinguins").getSymbol("Pinguin1").getSymbol("Snavel").play("zingen");
``` 
  
=====use html5 webcam video in edge animate====
<code javascript>
console.log('enable webcam');

var getUserMedia = function(t, onsuccess, onerror) {
  if (navigator.getUserMedia) {
    return navigator.getUserMedia(t, onsuccess, onerror);
  } else if (navigator.webkitGetUserMedia) {
    return navigator.webkitGetUserMedia(t, onsuccess, onerror);
  } else if (navigator.mozGetUserMedia) {
    return navigator.mozGetUserMedia(t, onsuccess, onerror);
  } else if (navigator.msGetUserMedia) {
    return navigator.msGetUserMedia(t, onsuccess, onerror);
  } else {
    onerror(new Error("No getUserMedia implementation found."));
  }
};

var vid = sym.$("Rectangle2");
vid.html('<video id="video" width= "640" height= "480" type= "video/mp4" autoplay loop</video><canvas id="cv" width="640" height="480"></canvas>'); 

var URL = window.URL || window.webkitURL;
var createObjectURL = URL.createObjectURL || webkitURL.createObjectURL;
if (!createObjectURL) {
  throw new Error("URL.createObjectURL not found.");
}

getUserMedia({'video': true},
  function(stream) {
    var url = createObjectURL(stream);
    video.src = url;
  },
  function(error) {
    alert("Couldn't access webcam.");
  }
);

//////////

var canvas = document.getElementById('cv');
var context = canvas.getContext("2d");
context.drawImage(video, 0, 0, 640, 480);
saveImage();

function saveImage(){
    var baseURL = "http://SERVER/post.php"; //see http://wiki.companje.nl/php for samples

    var xmlhttp;
    xmlhttp=((window.XMLHttpRequest)?new XMLHttpRequest():new ActiveXObject("Microsoft.XMLHTTP"));
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4){
            if(xmlhttp.status==200){
                console.log('success');             
            }
        }
    }
    xmlhttp.open("POST",baseURL,true);
    var oldCanvas = canvas.toDataURL("image/png");
    var params=oldCanvas;
    xmlhttp.send(params);
}
```


=====edge animate=====
* http://tv.adobe.com/watch/adobe-edge-preview/what-is-edge-animate/

===== cheat sheet =====
* http://download.macromedia.com/pub/learn/start/start_animate_cheatsheet.pdf

===== edge inspect =====
* https://creative.adobe.com/nl/products/inspect

===== Edge Commons =====
Dirty Little Helpers for Edge Animate
* http://www.edgedocks.com/edgecommons

===== Edge Reflow =====
http://html.adobe.com/edge/reflow/photoshop-plug-in.html

===== Edge Animate =====
* all files in the 'media' folder of your project will be automatically visible in the library
* filmpje: http://www.edgedocks.com/content/2014/03/adding-audio-scripts-and-responsive-scaling-edge-animate
* ''\'' key: scale timeline to fit

===== Random stars =====
<code java>
for (var i=0; i<40; i++) {
  var x = Math.floor(Math.random()*1920)+"px";
  var y = Math.floor(Math.random()*700)+"px";
  var ms = Math.floor(Math.random()*5000);

  var star = sym.createChildSymbol('Star',sym.getSymbolElement());
  star.getSymbolElement().css({position:"absolute",left:x,top:y});  
  star.play(ms);
}
```
