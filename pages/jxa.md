---
title: JXA - Javascript for Automation
---

# update the 'date' of mediaItems by filename
```js
function run(input, parameters) {
    
  var Photos = Application("Photos")
  Photos.includeStandardAdditions = true

  var items = {
    "IMG_0200.MOV": "Fri Dec 25 2020 09:18:59 GMT+0100 (CET)",
    "IMG_0232.MOV": "Fri Dec 25 2020 10:08:21 GMT+0100 (CET)",
    "858bc5f9-724b-4ac9-a477-e6c55d63aef1.mp4": "Fri Dec 18 2020 20:40:37 GMT+0100 (CET)",
    "a36f965b-2cc0-47d1-ac17-7e52f0f05510.mp4": "Fri Dec 25 2020 12:19:37 GMT+0100 (CET)",
    "a673fe95-eef5-48cb-ad12-da3b8bfc764c.mp4": "Sat May 09 2020 22:28:02 GMT+0200 (CEST)"
  }

  //update the 'date' of mediaItems by filename
  for (const [key, value] of Object.entries(items)) {
    var mediaItems = Photos.search({for: key})
    if (mediaItems.length>0) {
        mediaItems[0].date = new Date(value);
        console.log(mediaItems[0].date());
    }
  }
}
```

# Apple Photos JXA script for organising photos into albums
* add this code to a new 'Quick Action' in 'Automator' and assign a keyboard shortcut to it for ideal workflow.
* Het kan handig zijn om niet in Automator het script te schrijven maar in de ScriptEditor. Dan kun je iets makkelijker loggen enzo. Met Cmd+R runnen. Comments verschijnen in grijs.
* Keyboard shortcut: 1) Maak nieuwe 'Service' in Automator -> no input -> Run Javascript. 2) Vervolgens bij Preferences -> Keyboard Shortcuts -> Services een shortcut toekennen.

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
