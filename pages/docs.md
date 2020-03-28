---
title: Google Docs
---

# join, unique, filter, countunique
```
=JOIN(", " ; (UNIQUE(FILTER(LOG!C:C;LOG!D:D=A2))))
=COUNTUNIQUE(filter(log!C:C;log!D:D=A2))
```

# polling spreadsheet with script
* http://stackoverflow.com/questions/30628894/how-do-i-make-a-sidebar-display-values-from-cells

# add dates
add 12 months to date
```
=EDATE(A11;12)
```

# unique / sort / proper
you can use this as a nice data validator
  =SORT(PROPER(UNIQUE(INKOMSTEN!D3:D)))

# UrlFetchApp.fetch
```js
  var text = UrlFetchApp.fetch(URL).getContentText();
```

```php
header("Content-type: text/plain");

define('DB_NAME', '...');
define('DB_USER', '...');
define('DB_PASSWORD', '...');
define('DB_HOST', '...');

$db = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysql_select_db(DB_NAME, $db);

$id = mysql_real_escape_string($_GET["id"]);
if (!is_numeric($id)) die();

if ($row = mysql_fetch_assoc(mysql_query("SELECT post_id,meta_value FROM wp_postmeta WHERE meta_key='_billing_country' AND post_id=$id"))) {
    echo $row['meta_value'];
}

if ($row = mysql_fetch_assoc(mysql_query("SELECT post_id,meta_value FROM wp_postmeta WHERE meta_key='VAT Number' AND post_id=$id"))) {
    echo " ".$row['meta_value'];
}
```

# tips
http://woorkup.com/2010/02/19/10-useful-google-spreadsheet-formulas-you-must-know/

# unique() and continue() functions
```
=UNIQUE(A:A)
=CONTINUE(B1; 2; 1)
...etc..
```

# append row
```sheet.appendRow(['hello',true,5.5,new Date()]);```

# fetch from url
```var text = UrlFetchApp.fetch("http://companje.nl").getContentText();```

# documentation
* https://developers.google.com/apps-script/

# spreadsheet as csv
* https://docs.google.com/spreadsheet/pub?key=0Ag0qaBCRDtdydEV2eHVIZWRwRkRRY0l0d2o0eWtzZ1E&output=csv

# scripting with csv
* https://developers.google.com/apps-script/articles/docslist_tutorial#section2

# advanced scripting
```js

function onOpen() {
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var entries = [ {name: "Layout voor kolommen instellen", functionName: "layoutKolommen"},
                  {name: "BTW berekening toevoegen", functionName: "btwBerekening"},
                  {name: "Kostensoort validator toevoegen", functionName: "soortValidator"}
                ];
  ss.addMenu("Administratie", entries);
}

function soortValidator() {
   var range = SpreadsheetApp.getActiveRange();
  if (range.getColumnIndex()!=10 || range.getNumColumns()!=1) {
    Browser.msgBox("Selecteer de kolom met kostensoorten aub.");
    return;
  }
  var overzicht = SpreadsheetApp.getActiveSpreadsheet().getSheetByName("Overzicht");
  var from = overzicht.getRange("Overzicht!A10"); //soort validator
  var to = SpreadsheetApp.getActiveRange();
  from.copyTo(to);
}

function btwBerekening() {
  var range = SpreadsheetApp.getActiveRange();

  if (range.getColumnIndex()# 6 && range.getNumColumns()# 1) range.setFormula("=(R[0]C[3]/(R[0]C[1]*100+100))*100"
  else if (range.getColumnIndex()# 8 && range.getNumColumns()# 1) range.setFormula("=R[0]C[-1]*R[0]C[-2]"
  else Browser.msgBox("Selecteer één van de volgende kolommen: excl. BTW óf BTW bedrag.");
}

function layoutKolommen() {
  if (SpreadsheetApp.getActiveSheet().getName()# "Overzicht")
    Browser.msgBox("'Layout verbeteren' is bedoeld voor Inkomsten en Uitgaven bladen");
    return;
  }
  
  var sheet = SpreadsheetApp.getActiveSheet();
  
  sheet.setFrozenRows(1);
  
  sheet.setColumnWidth(1,80); //datum
  sheet.setColumnWidth(2,50); //kwartaal
  sheet.setColumnWidth(3,80); //factuur nr
  sheet.setColumnWidth(4,250); //debiteur/crediteur
  sheet.setColumnWidth(5,270); //omschrijving
  sheet.setColumnWidth(6,70); //excl
  sheet.setColumnWidth(7,70); //%
  sheet.setColumnWidth(8,70); //btw bedrag
  sheet.setColumnWidth(9,70); //inc
  sheet.setColumnWidth(10,120); //soort
  sheet.setColumnWidth(11,270); //soort
  
  sheet.getRange("A:A").setNumberFormat("yyyy-MM-dd");
  sheet.getRange("F:F").setNumberFormat("€ #,##0.00");
  sheet.getRange("G:G").setNumberFormat("0.00%");
  sheet.getRange("H:H").setNumberFormat("€ #,##0.00");
  sheet.getRange("I:I").setNumberFormat("€ #,##0.00");
}
```

# new insights
```js
function onOpen() {
  var sheet = SpreadsheetApp.getActiveSpreadsheet();
  var entries = [ {name: "Upload Rabobank bestand...", functionName: "upload"} ];
  sheet.addMenu("Administratie", entries);
}

function upload() {
  var app = UiApp.createApplication().setTitle("Upload Rabobank mut.txt");
  var form = app.createFormPanel().setId("frm").setEncoding("multipart/form-data");
  var formContent = app.createVerticalPanel();
  form.add(formContent);  
  formContent.add(app.createLabel("Ga naar www.rabobank.nl en download mut.txt voor het juiste kwartaal."));
  formContent.add(app.createLabel("Bijvoorbeeld voor K3: 01-07-2012 t/m 30-09-2012."));
  formContent.add(app.createLabel("."));
  formContent.add(app.createFileUpload().setName("thefile"));
  formContent.add(app.createSubmitButton("Upload"));
  app.add(form);
  SpreadsheetApp.getActiveSpreadsheet().show(app);
}

function doPost(e) {
  var fileBlob = e.parameter.thefile;
  var doc = DocsList.createFile(fileBlob);
  var app = UiApp.getActiveApplication();
  app.add(app.createLabel("file uploaded successfully"));
  importCsv(doc.getName());
  return app;
}

function importCsv(filename) {
  if (!filename) filename = Browser.inputBox("Welk bestand wil je importeren?");
  if (!filename) return;
  
  var files = DocsList.find(filename);
  var csv = CSVToArray(files[0].getContentAsString());

  var inkomsten = SpreadsheetApp.getActiveSpreadsheet().getSheetByName("Inkomsten");
  var uitgaven = SpreadsheetApp.getActiveSpreadsheet().getSheetByName("Uitgaven");

  
  for (var i=0; i<csv.length; i++) {
    if (csv[i].length!=16) continue;
    
    var year = csv[i][2].substr(0,4);
    var month = csv[i][2].substr(4,2);
    var day = csv[i][2].substr(6,2);
    var isodate= year+"-"+month+"-"+day;
    var quarter = "K"+Math.ceil(month/3);        
    var credit=(csv[i][3]# "C"
    var amount=csv[i][4].replace(".",",");
    var name=csv[i][6];
    var invoicenr="";
    var exvat="=R[0]C[3]/(R[0]C[1]+1)";
    var vatpct=0;
    var vatamt="=R[0]C[-2]*R[0]C[-1]";
    
    var category="";
        
    var description = csv[i][10];
    if (csv[i][11]) description=description.trim()+" "+csv[i][11];
    if (csv[i][12]) description=description.trim()+" "+csv[i][12];
    if (csv[i][13]) description=description.trim()+" "+csv[i][13];
    
    var sheet = credit ? inkomsten : uitgaven;
    var nextRow = sheet.getLastRow()+1;
    
      
    sheet.appendRow([isodate,quarter,invoicenr,name,description,exvat,vatpct,vatamt,amount,category]);
  }

  layoutSheet(SpreadsheetApp.getActiveSpreadsheet().getSheetByName("Inkomsten"));
  layoutSheet(SpreadsheetApp.getActiveSpreadsheet().getSheetByName("Uitgaven"));
}

function layoutSheet(sheet) {  
  Logger.log(sheet.getName());
  sheet.setFrozenRows(1);
  sheet.setColumnWidth(1,80); //datum
  sheet.setColumnWidth(2,50); //kwartaal
  sheet.setColumnWidth(3,80); //factuur nr
  sheet.setColumnWidth(4,250); //debiteur/crediteur
  sheet.setColumnWidth(5,270); //omschrijving
  sheet.setColumnWidth(6,70); //excl
  sheet.setColumnWidth(7,70); //%
  sheet.setColumnWidth(8,70); //btw bedrag
  sheet.setColumnWidth(9,70); //inc
  sheet.setColumnWidth(10,120); //soort
  sheet.setColumnWidth(11,270); //soort
  sheet.getRange("A:A").setNumberFormat("yyyy-MM-dd");
  sheet.getRange("F:F").setNumberFormat("€ #,##0.00");
  sheet.getRange("G:G").setNumberFormat("0.00%");
  sheet.getRange("H:H").setNumberFormat("€ #,##0.00");
  sheet.getRange("I:I").setNumberFormat("€ #,##0.00");
}

function CSVToArray(strData, strDelimiter) {
  strDelimiter = (strDelimiter || ",");
  var arrData = [[]];
  var arrMatches = null;
  var objPattern = new RegExp((
      "(\" + strDelimiter + "|\r?\n|\r|^)" +    // Delimiters.
      "(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +           // Quoted fields.
      "([^\"\" + strDelimiter + "\r\n]*))"       // Standard fields.
    ),"gi");
  while (arrMatches = objPattern.exec(strData)) {
    var strMatchedDelimiter = arrMatches[1];
    if (strMatchedDelimiter.length && (strMatchedDelimiter != strDelimiter)) arrData.push([]);
    var strMatchedValue = arrMatches[2] ? arrMatches[2].replace(new RegExp( "\"\"", "g" ), "\"") : arrMatches[3];
    arrData[ arrData.length - 1 ].push( strMatchedValue );
  }
  return arrData;
}


/*function soortValidator() {
   var range = SpreadsheetApp.getActiveRange();
  if (range.getColumnIndex()!=10 || range.getNumColumns()!=1) {
    Browser.msgBox("Selecteer de kolom met kostensoorten aub.");
    return;
  }
  var overzicht = SpreadsheetApp.getActiveSpreadsheet().getSheetByName("Overzicht");
  var from = overzicht.getRange("Overzicht!A10"); //soort validator
  var to = SpreadsheetApp.getActiveRange();
  from.copyTo(to);
}*/
```

# 2015 versie
```javascript
function onOpen() {
  var sheet = SpreadsheetApp.getActiveSpreadsheet();
  var entries = [ {name: "Upload Rabobank bestand...", functionName: "upload"} ];
  sheet.addMenu("Administratie", entries);
}

function upload() {
  app = UiApp.createApplication().setTitle("Upload Rabobank transactions.txt");
  form = app.createFormPanel().setId("frm").setEncoding("multipart/form-data");
  panel = app.createVerticalPanel();
  panel.add(app.createLabel("Ga naar www.rabobank.nl en download transactions.txt (nieuwe formaat) voor het juiste kwartaal. Bijvoorbeeld voor K3: 01-07-2012 t/m 30-09-2012."));
  panel.add(app.createFileUpload().setName("thefile"));
  panel.add(app.createSubmitButton("Upload"));
  form.add(panel);  
  app.add(form);
  SpreadsheetApp.getActiveSpreadsheet().show(app);
}

function doPost(e) {
  var fileBlob = e.parameter.thefile;
  
  csv = CSVToArray(fileBlob.contents);  
  
  Browser.msgBox("Klik op OK om te beginnen met het verwerken van " + csv.length + " transacties. Dit kan enkele minuten duren en geeft geen tussentijdse feedback.");

  addTransactions(csv);
}

function addTransactions(csv) {
  var inkomsten = SpreadsheetApp.getActiveSpreadsheet().getSheetByName("Inkomsten"); ///getSheets()[2]; //getSheetByName("Inkomsten");
  var uitgaven = SpreadsheetApp.getActiveSpreadsheet().getSheetByName("Uitgaven"); //getSheets()[1]; //getSheetByName("Uitgaven");

  for (var i=0; i<csv.length; i++) {
    
    if (csv[i].length!=19) continue;
            
    var year = csv[i][2].substr(0,4);
    var month = csv[i][2].substr(4,2);
    var day = csv[i][2].substr(6,2);   
    var isodate= year+"-"+month+"-"+day;
    var quarter = "K"+Math.ceil(month/3);        
    var credit=(csv[i][3]=="C");
    
    var amount=csv[i][4].replace(".",",");
    var name=csv[i][6];
    
    if (name==undefined) name="";
    
    var invoicenr="";
    var exvat="=R[0]C[3]/(R[0]C[1]+1)";
    var vatpct=0;
    var vatamt="=R[0]C[-2]*R[0]C[-1]";
    
    var category="";
        
    var description = csv[i][10];
    
    if (csv[i][11]) description=description.trim()+" "+csv[i][11];
    if (csv[i][12]) description=description.trim()+" "+csv[i][12];
    if (csv[i][13]) description=description.trim()+" "+csv[i][13];
    
    description = description.toLowerCase();
    
    var sheet = credit ? inkomsten : uitgaven;
    var nextRow = sheet.getLastRow()+1;
      
    Logger.log(description);
    
    sheet.appendRow([isodate,quarter,invoicenr,name,description,exvat,vatpct,vatamt,amount,category]);
  }

  layoutSheet(inkomsten);
  layoutSheet(uitgaven);
  
  Browser.msgBox("Klaar met verwerken van " + csv.length + " transacties");
}

function layoutSheet(sheet) {  
  Logger.log(sheet.getName());
  sheet.setFrozenRows(1);
  sheet.setColumnWidth(1,80); //datum
  sheet.setColumnWidth(2,50); //kwartaal
  sheet.setColumnWidth(3,80); //factuur nr
  sheet.setColumnWidth(4,250); //debiteur/crediteur
  sheet.setColumnWidth(5,270); //omschrijving
  sheet.setColumnWidth(6,70); //excl
  sheet.setColumnWidth(7,70); //%
  sheet.setColumnWidth(8,70); //btw bedrag
  sheet.setColumnWidth(9,70); //inc
  sheet.setColumnWidth(10,120); //soort
  sheet.setColumnWidth(11,270); //soort
  sheet.getRange("A:A").setNumberFormat("yyyy-MM-dd");
  sheet.getRange("F:F").setNumberFormat("€ #,##0.00");
  sheet.getRange("G:G").setNumberFormat("0.00%");
  sheet.getRange("H:H").setNumberFormat("€ #,##0.00");
  sheet.getRange("I:I").setNumberFormat("€ #,##0.00");
}

function CSVToArray(strData, strDelimiter) {
  strDelimiter = (strDelimiter || ",");
  var arrData = [[]];
  var arrMatches = null;
  var objPattern = new RegExp((
      "(\" + strDelimiter + "|\r?\n|\r|^)" +    // Delimiters.
      "(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +           // Quoted fields.
      "([^\"\" + strDelimiter + "\r\n]*))"       // Standard fields.
    ),"gi");
  while (arrMatches = objPattern.exec(strData)) {
    var strMatchedDelimiter = arrMatches[1];
    if (strMatchedDelimiter.length && (strMatchedDelimiter != strDelimiter)) arrData.push([]);
    var strMatchedValue = arrMatches[2] ? arrMatches[2].replace(new RegExp( "\"\"", "g" ), "\"") : arrMatches[3];
    arrData[ arrData.length - 1 ].push( strMatchedValue );
  }
  return arrData;
}
```

#  Bugfix when description is undefined (May 27 2015)
```javascript
function onOpen() {
  var sheet = SpreadsheetApp.getActiveSpreadsheet();
  var entries = [ {name: "Upload Rabobank bestand...", functionName: "upload"} ];
  sheet.addMenu("Administratie", entries);
}

function upload() {
  app = UiApp.createApplication().setTitle("Upload Rabobank transactions.txt");
  form = app.createFormPanel().setId("frm").setEncoding("multipart/form-data");
  panel = app.createVerticalPanel();
  panel.add(app.createLabel("Ga naar www.rabobank.nl en download transactions.txt (nieuwe formaat) voor het juiste kwartaal. Bijvoorbeeld voor K3: 01-07-2012 t/m 30-09-2012."));
  panel.add(app.createFileUpload().setName("thefile"));
  panel.add(app.createSubmitButton("Upload"));
  form.add(panel);  
  app.add(form);
  SpreadsheetApp.getActiveSpreadsheet().show(app);
}

function doPost(e) {
  /*Logger.log("TEST");

  var files = DriveApp.getFilesByName("transactions.txt");
  if (files.hasNext()) {
    var file = files.next();
    var blob = file.getAs(MimeType.PLAIN_TEXT);
    csv = CSVToArray(blob.getDataAsString());
    Logger.log(csv.length);
    addTransactions(csv);
    return;
  } else {
    Logger.log("no file");
    return
  }*/
  
  var fileBlob = e.parameter.thefile;
  csv = CSVToArray(fileBlob.contents);  
  Browser.msgBox("Klik op OK om te beginnen met het verwerken van " + csv.length + " transacties. Dit kan enkele minuten duren en geeft geen tussentijdse feedback.");
  addTransactions(csv);
}

function addTransactions(csv) {
  var inkomsten = SpreadsheetApp.getActiveSpreadsheet().getSheetByName("Inkomsten"); ///getSheets()[2]; //getSheetByName("Inkomsten");
  var uitgaven = SpreadsheetApp.getActiveSpreadsheet().getSheetByName("Uitgaven"); //getSheets()[1]; //getSheetByName("Uitgaven");

  for (var i=0; i<csv.length; i++) {
    
    Logger.log("Bezig met regel " + (i+1));

    if (csv[i].length!=19) {
      Logger.log("Fout op regel " + (i+1));
      continue;
    }
                
    var year = csv[i][2].substr(0,4);
    var month = csv[i][2].substr(4,2);
    var day = csv[i][2].substr(6,2);   
    var isodate= year+"-"+month+"-"+day;
    var quarter = "K"+Math.ceil(month/3);        
    var credit=(csv[i][3]=="C");
    
    var amount=csv[i][4].replace(".",",");
    var name=csv[i][6];
    
    if (name==undefined) name="";
    
    var invoicenr="";
    var exvat=""; //=R[0]C[3]/(R[0]C[1]+1)";
    var vatpct=""; //0;
    var vatamt=""; //=R[0]C[-2]*R[0]C[-1]";
    
    var category="";
        
    var description = csv[i][10] || "";
    
    if (csv[i][11]) description=description.trim()+" "+csv[i][11];
    if (csv[i][12]) description=description.trim()+" "+csv[i][12];
    if (csv[i][13]) description=description.trim()+" "+csv[i][13];
    
    description = description.toLowerCase();
    
    var sheet = credit ? inkomsten : uitgaven;
    var nextRow = sheet.getLastRow()+1;
      
//    Logger.log(description);
    
    sheet.appendRow([isodate,quarter,invoicenr,name,description,exvat,vatpct,vatamt,amount,category]);
  }

  layoutSheet(inkomsten);
  layoutSheet(uitgaven);
  
  Browser.msgBox("Klaar met verwerken van " + csv.length + " transacties");
}

function layoutSheet(sheet) {  
  Logger.log(sheet.getName());
  sheet.setFrozenRows(1);
  sheet.setColumnWidth(1,80); //datum
  sheet.setColumnWidth(2,50); //kwartaal
  sheet.setColumnWidth(3,80); //factuur nr
  sheet.setColumnWidth(4,250); //debiteur/crediteur
  sheet.setColumnWidth(5,270); //omschrijving
  sheet.setColumnWidth(6,70); //excl
  sheet.setColumnWidth(7,70); //%
  sheet.setColumnWidth(8,70); //btw bedrag
  sheet.setColumnWidth(9,70); //inc
  sheet.setColumnWidth(10,120); //soort
  sheet.setColumnWidth(11,270); //soort
  sheet.getRange("A:A").setNumberFormat("yyyy-MM-dd");
  sheet.getRange("F:F").setNumberFormat("€ #,##0.00");
  sheet.getRange("G:G").setNumberFormat("0.00%");
  sheet.getRange("H:H").setNumberFormat("€ #,##0.00");
  sheet.getRange("I:I").setNumberFormat("€ #,##0.00");
}

function CSVToArray(strData, strDelimiter) {
  strDelimiter = (strDelimiter || ",");
  var arrData = [[]];
  var arrMatches = null;
  var objPattern = new RegExp((
      "(\" + strDelimiter + "|\r?\n|\r|^)" +    // Delimiters.
      "(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +           // Quoted fields.
      "([^\"\" + strDelimiter + "\r\n]*))"       // Standard fields.
    ),"gi");
  while (arrMatches = objPattern.exec(strData)) {
    var strMatchedDelimiter = arrMatches[1];
    if (strMatchedDelimiter.length && (strMatchedDelimiter != strDelimiter)) arrData.push([]);
    var strMatchedValue = arrMatches[2] ? arrMatches[2].replace(new RegExp( "\"\"", "g" ), "\"") : arrMatches[3];
    arrData[ arrData.length - 1 ].push( strMatchedValue );
  }
  return arrData;
}
```
