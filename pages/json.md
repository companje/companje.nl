---
title: JSON
---

# brutal javascript convert json to csv
```js 
$(document).ready(function () {
  var csv = [];
  csv.push(["date","time","status","country","amount","method","username","name","mollie_customer"]);

  $.getJSON("json/data.json", function(data) {
      
    $.each(data.data, function(i, row) {
      var cols = [];
      cols.push((row.createdDatetime ? row.createdDatetime.substr(0,10) : "") || "");
      cols.push((row.createdDatetime ? row.createdDatetime.substr(11,8) : "") || "");
      cols.push(row.status || "");
      cols.push(row.countryCode || "");
      cols.push(row.amount.replace(".",",") || "");
      cols.push(row.method || "");
      cols.push((row.metadata ? row.metadata.userID : "") || "");
      cols.push((row.transactions[0] ? row.transactions[0].details.consumerName : "") || "");
      cols.push(row.customerId || "");
      var r=JSON.stringify(cols).substr(1);
      csv.push(r.substr(0,r.length-1));
    });

    var text = csv.join("\n");

    //download
    var element = document.createElement('a');
    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
    element.setAttribute('download', "payments.csv");
    element.style.display = 'none';
    document.body.appendChild(element);
    element.click();
    document.body.removeChild(element);
    });
});
```

# jq
```
brew install jq
```

# online json editor
<http://www.jsoneditoronline.org/>
```js
var url = "http://192.168.0.165/?jsoncallback=?";
```

# json formatter
* <http://www.bodurov.com/JsonFormatter/>

# validate json
* <http://jsonlint.com/>

# online yaml to json converter with colors
* <http://nodeca.github.io/js-yaml/>
