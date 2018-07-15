---
title: CouchDB
---

# import many JSON files to a new CouchDB database
```javascript
var fs = require('fs');
var walk = require('walk');

const NodeCouchDb = require('node-couchdb');

const dbName = "DB_NAME";

const couch = new NodeCouchDb({
  auth: {
      user: 'USER',
      pass: 'PASS'
  }
});

//drop database
// couch.dropDatabase(dbName).then(() => {

//create database
couch.createDatabase(dbName).then(() => {
  console.log("OK: createDatabase");

 //walk json files
  const folder = "FOLDER_WITH_JSON_FILES";
  const walker = walk.walk(folder);

  var i=0; 
  walker.on("file", function (root, fileStats, next) {
    fs.readFile(folder + "/" + fileStats.name, 'utf8', function (err,data) {

      var id = fileStats.name.substr(0,fileStats.name.indexOf('?'));

      couch.insert(dbName, {
          _id: id,
          media: JSON.parse(data).media
      }).then(({data, headers, status}) => {
        console.log(Math.round((i++)/1950)+"%",id);
      }, err => {
        console.log("ERR: insert",err);
      });

      next();
    });
  });

}, err => {
  // request error occured
  console.log("ERR: createDatabase",err);
});

// }, err => {
//   console.log("ERR: dropDatabase",err);
// });
```



