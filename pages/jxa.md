---
title: JXA - Javascript for Automation
---


# Apple Photos JXA script for organising photos into albums
* add this code to a new 'Quick Action' in 'Automator' and assign a keyboard shortcut to it for ideal workflow.

```js
var app = Application("Photos")
app.includeStandardAdditions = true
var sel = app.selection();

if (sel.length>0) {

  //show list with all album names ordered
  var names = app.albums.name(); //original order for index
  var namesWithIndex = names.map(function(el,index) { return el + " #" + index; });
  var namesSorted = namesWithIndex.sort();
  var result = app.chooseFromList(namesSorted, { withPrompt: sel.length + " foto's toevoegen aan een bestaand album?" })[0];
  
  if (result) { //if album was choosen then add selected photos to that album
    var index = parseInt(result.substr(result.lastIndexOf('#') + 1));
    var selectedAlbum = app.albums.at(index);
    app.add(sel, {to: selectedAlbum });

  } else { //else (on cancel/escape) ask to create a new album
  
    var result = app.displayDialog("Nieuw album maken?", { defaultAnswer: "",  withIcon: 1 })
    if (result) {
      var album = app.make({new: "album", named: result.textReturned, at: app.folders.byName("Overig") });
      app.add(sel, {to: album });
    }
  }
}
```
