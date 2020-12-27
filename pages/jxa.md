---
title: JXA - Javascript for Automation
---


# Apple Photos JXA script for organising photos into albums
* add this code to a new 'Quick Action' in 'Automator' and assign a keyboard shortcut to it for ideal workflow.
* Het kan handig zijn om niet in Automator het script te schrijven maar in de ScriptEditor. Dan kun je iets makkelijker loggen enzo. Met Cmd+R runnen. Comments verschijnen in grijs.

```js
//Create a new ‘Quick Action’ in ‘Automator’, then 'Add JavaScript' and assign a keyboard shortcut to it for ideal workflow.
//by Rick Companje, May 2019 - update december 2020

var Photos = Application("Photos")
Photos.includeStandardAdditions = true
var sel = Photos.selection();
var folder = Photos.folders.byName("Overig");

if (sel.length>0) {
  
  if (!canAccess(sel)) {
    Photos.displayAlert("Error",{message: "Items in Smart Albums are currently not accessible by this script.", as: "critical" });
  } else {

    //show list with all album names ordered
    var names = folder.albums.name(); //original order for index
    var namesWithIndex = names.map(function(el,index) { return el }); // + " #" + index; });
    var namesSorted = namesWithIndex.sort();
    
    var result = Photos.chooseFromList(namesSorted, { withPrompt: sel.length + " foto's toevoegen aan een bestaand album?" })[0];

    if (result) {
      var selectedAlbum = folder.albums.byName(result);
      Photos.add(sel, {to: selectedAlbum });
    } else {
      var result = Photos.displayDialog("Nieuw album maken?", { defaultAnswer: "",  withIcon: 1 })
      if (result) {
        var album = Photos.make({new: "album", named: result.textReturned, at: folder });
        Photos.add(sel, {to: album });
      }
    }
  }
}

function canAccess(sel) {
  try {
    sel[0].id();
    return true;
  } catch (e) {
    return false;
  }
}

```
