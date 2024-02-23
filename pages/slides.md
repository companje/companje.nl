# Extract images per slide using a name based on year (from upperleft textbox) + blob hash
```javascript
function parseDutchDateWithFullMonths(dutchDate) {
  const monthMap = {
    jan: 0, januari: 0, feb: 1, februari: 1, mrt: 2, maart: 2, apr: 3, april: 3, mei: 4, jun: 5, juni: 5, 
    jul: 6, juli: 6, aug: 7, augustus: 7, sep: 8, september: 8, okt: 9, oktober: 9, nov: 10, november: 10, 
    dec: 11, december: };
  const parts = dutchDate.match(/(\d+)\s+(\w+)\s+(\d+)/);
  if (parts) {
    const day = parseInt(parts[1], 10);
    const month = monthMap[parts[2].toLowerCase()];
    const year = parseInt(parts[3], 10);
    if (month === undefined) {
      throw new Error('Invalid Dutch month name');
    }
    const date = new Date(year, month, day);
    return date;
  } else {
    return throw new Error('Invalid Dutch date format');
  }
}

function formatDate(s) {
  s = s.trim();
  var timeZone = Session.getScriptTimeZone();

  if (s.match(/^\d{4}/)) {
    return s;
  } else {
    try {
      return Utilities.formatDate(parseDutchDateWithFullMonths(s), timeZone, "yyyy-MM-dd")
    } catch {
      parts = s.match(/\b\d{4}/)
      if (parts) {
        return parts[0] + " (" + s + ")"
      } else {
        throw new Error('geen jaartal gevonden: ' + s);
      }
    }
  }
}

function createFolderByPath(path) {
  var parts = path.split('/');
  var folder = DriveApp.getRootFolder();
  for (var i = 0; i < parts.length; i++) {
    var part = parts[i];
    var folders = folder.getFoldersByName(part);
    if (folders.hasNext()) {
      folder = folders.next();
    } else {
      folder = folder.createFolder(part);
    }
  }
  return folder;
}

function makeSafeFilename(formattedDate) {
  return formattedDate.replace(/[\s\/\\?%*:|"<>]/g, '_');
}

function saveAllImagesPerSlide() {
  var folder = createFolderByPath("MijnAfbeeldingen")
  var presentation = SlidesApp.getActivePresentation();
  var slides = presentation.getSlides();
  
  slides.forEach(function(slide, index) {
    var images = slide.getImages();
    var shapes = slides[index].getShapes();
    var formattedDate;

    for (var j = 0; j<shapes.length; j++) {
      if (shapes[j].getShapeType() === SlidesApp.ShapeType.TEXT_BOX) {
        
        if (shapes[j].getTop()<10 && shapes[j].getLeft()<10) {
          let txt = shapes[j].getText().asString();
          formattedDate = formatDate(txt);
          console.log("found date",formattedDate)
          break; //found date!
        }
      }
    }

    //nu het jaartal is opgezocht nogmaals loopen maar nu om afbeeldingen weg te schrijven.
    var pageElements = slide.getPageElements();
    pageElements.forEach(function(element) {
      if (element.getPageElementType() === SlidesApp.PageElementType.IMAGE) {
        var image = element.asImage();
        var blob = image.getBlob();
        // var altText = image.getTitle() || image.getDescription(); 
        var hash = Utilities.computeDigest(Utilities.DigestAlgorithm.SHA_256, blob.getBytes());
        var hashString = hash.reduce(function(str, byte) {
          var value = (byte < 0 ? byte + 256 : byte).toString(16);
          return str + (value.length === 1 ? '0' + value : value);
        }, '');
        var shortHash = hashString.substring(0, 16);
        var filename = makeSafeFilename(formattedDate) + "_" + shortHash + ".jpg";
        var file = folder.createFile(blob.setName(filename));
      }
    });
  });
}
```
