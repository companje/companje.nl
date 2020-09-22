---
title: NodeJS
layout: default
---

# documentatie.org resolver (set documentatie.org in /etc/hosts)
```javascript
const express = require('express')
const app     = express()

app.get('*', (req, res) => {
  res.redirect('https://data.netwerkdigitaalerfgoed.nl/hetutrechtsarchief/UDS/browser?resource='
    + encodeURIComponent("http://" + req.host + req.url))
})

const server = app.listen(80, () => {})
```


# import many JSON files to a new CouchDB database
see [couchdb]

# serve textfile as paginated array
```javascript
var cors = require('cors');
var fs = require('fs');
const express = require('express')
const app = express()
var ids;
var pageSize = 10;

app.use(cors());

app.get('/', (req, res) => res.send('Usage: /1'));

app.get('/:page', function(req, res) {
  var index = req.params["page"] * pageSize;
  
  res.send(ids.slice(index, index + pageSize).map(id => {
    return {
      id: id
    }
  })); 
});

fs.readFile('data/uids-no-download-niet-compleet.txt', 'utf8', function (err,data) {
  if (err) return console.log(err);
  ids = data.split("\n");
  app.listen(3000, () => console.log('Serve API on port 3000 with '+ids.length+' items'));
});

```

result:
```
[
"f7daecfe-c684-5c18-9374-14005ceb992c",
"189bdd36-0fb2-51cf-a2a2-e49d55688f1f",
"32237189-2792-55f8-8765-ef3ddf1c8fc5",
"0e9a560b-11b2-59af-9717-f6058d3412ac",
"8ea7eedf-facc-5e38-bbc2-9f003f59fe46",
"6945af22-a81f-5f2b-bf38-0658847d8a33",
"e45e1be6-7d03-596a-a95b-4281480958d3",
"1a15e70d-ac5d-5e92-9d56-e82d3311b8e6",
"d6d64213-9a8f-5a50-9e41-6b59b0a58766",
"849ca4e2-d158-552d-8622-1234ac3664ed"
]
```

# test2
## Debuggen via Chrome Dev Console
```bash
node --inspect-brk index.js
```

## Session JAR for 302 redirects
```javascript
var request = require('request').defaults({
	followRedirect: true,
	followAllRedirects: true,
	jar: true // this creates session 'jar'
});

var url = "http://URL";

request(url, function (error, response, body) {
	console.log(body);
});
```

## express params
```javascript
app.get('/detail/:id/:index', function(req, res) {
  res.send(req.params);
});
```

## get all links from file using cheerio
```javascript
var fs = require('fs')
var cheerio = require('cheerio');

fs.readFile('index.html', 'utf8', function (err,data) {
    if (err) return console.log(err);

    var $ = cheerio.load(data);

    $('a').each(function(i, elem) {
        console.log($(this).attr('href'));
    });

});
```

## Redis is ready
generate by: `Doodle3D-App/node_modules/superlogin/lib/sessionAdapters/RedisAdapter.js`

## parse HTML jQuery style
https://github.com/cheeriojs/cheerio

```javascript
var request = require('request');
var cheerio = require('cheerio');
var url = 'http://www.marktplaats.nl/s/749032247370.html';

request(url, function (error, response, body) {
    if (!error) {
        var $ = cheerio.load(body);
        var url = $('.search-result span').attr('data-url');
        var title = $('.search-result img').attr('title');
        var img = $('.search-result img').attr('src').replace("_82","_83");
        console.log("url:	",url);
        console.log("title:	",title);
        console.log("img:	",img);
    } else {
        console.log(error);
    }
});
```

## app.use & app.get/post combined
```javascript
var express = require('express');
var app = express();
var http = require('http').Server(app);

app.use(express.static(__dirname + '/www'));

app.post('/api/v1/call/:roomName', function(req, res) {
  res.send("Hoi: " + req.params.roomName);
});

http.listen(8081, function(){
  console.log('listening on *:8081');
});
```


## Long polling
* [[http://book.mixu.net/node/ch3.html|Long polling example in NodeJS]]

## Install log
```
Node.js was installed at

   /usr/local/bin/node

npm was installed at

   /usr/local/bin/npm

Make sure that /usr/local/bin is in your $PATH.
```

## node-webkit
* What is nw.js (previously node-webkit) ?

## Node.js CMS: Keystone
Mail van Peter: Hey Rick, Dat op Node.js CMS waar ik het over had was Keystone. http://keystonejs.com/
Interessante slides: Moving from PHP to a nodejs full stack CMS https://s3.amazonaws.com/mnb_keystone/slidedecks/keystonejs.pdf

Een aantal toffe dingen:
* Je genereert de basis met Yeoman http://yeoman.io wat je dan vervolgens kan gaan customizen. Dit is eigenlijk bijna de hele frontend.
* De daadwerkelijke Keystone CMS is gewoon een dependency (npm package)
* Doordat het veel bekende bouwstenen gebruikt zoals Express en Mongoose
(MongoDB) is het sneller te leren en zijn er stiekem al veel resources.
* Doordat het Express gebruikt kun je allerlei template talen https://github.com/strongloop/express/wiki#template-engines gebruiken.
* Content types, routes (url's) en overzicht pagina's (denk aan de fabmoments pagina)) beschrijf je met overzichtelijke stukjes javascript.
* Doordat bijna alles met bestanden werkt (behalve de content) kun je normaal versie beheer doen. (Voor content hebben ze csv export mogelijkheden.)
* Doordat je werkt met Mongoose en template talen kun je heel makkelijk overzicht pagina's (views) maken.
* Doordat de flow achter het generen van pagina's heel overzichtelijk is heb ik gisteren al fabpublication pagina's kunnen maken.
* Stiekem zijn er al veel field types, Relations (verwijzingen naar andere soorten content types), TextField met eventueel wysiwyg editor, intern of extern gehoste afbeeldingen of bestanden, datum.
* Het lijkt een hele actieve community.
* Prima docs http://keystonejs.com/docs/.

Nadelen:
* Vereist Node.js en MongoDB (wat nog altijd minder makkelijk is dan php en mySQL)
* Admin UI is nog erg beperkt. Je kan eigenlijk alleen content beheren. Zelfs het menu is nu nog in javascript beschreven.
* Nog geen permissions / rollen systeem. Je kan bijv nog geen verschillen maken tussen lid, moderator en admin. Wat bijv bij fablab amersfoort erg belangrijk is. [[https://github.com/keystonejs/keystone/issues/803|#803]]
* Er is nog geen goed support voor meertaligheid, zijn ze wel mee bezig. [[https://github.com/keystonejs/keystone/issues/802|#802]]
* Je kan eigenlijk nog niet de admin ui tweaken, maar hier zijn ze hard mee bezig.
* Je kan nog geen tags gebruiken, zijn ze mee bezig. [[https://github.com/keystonejs/keystone/issues/153|#153]] [[https://github.com/keystonejs/keystone/issues/282|#282]]
* Nog geen themes, al kun je met bootstrap tweaks (Bootswatch http://bootswatch.com/) heel ver komen: [[https://github.com/keystonejs/keystone/issues/548#issuecomment-69845660|#548]]

Voor puur blogging/publishing is Ghost waarschijnlijk interessanter, maar daar heb ik nog niet echt naar gekeken.
https://ghost.org/

Peter

## Express extensions + templates
* https://github.com/strongloop/express/wiki#template-engines

## doorbell
* https://gist.github.com/companje/b3de8f5028c1dd1eb0ca

## gpio's on Raspberry Pi with nodejs
```javascript
var Gpio = require('onoff').Gpio;

var cols = [
  new Gpio(7, 'in', 'both'),
  new Gpio(8, 'in', 'both'),
  new Gpio(25, 'in', 'both'),
  new Gpio(24, 'in', 'both'),
  new Gpio(23, 'in', 'both')
];

var rows = [
  new Gpio(14, 'out'),
  new Gpio(15, 'out'),
  new Gpio(18, 'out')
];

setInterval(function() {
  rows[0].writeSync(0);
  rows[1].writeSync(0);
  rows[2].writeSync(0);
  var s = '';
  for (var i=0; i<5; i++) {
    s+=cols[i].readSync();
  }
  console.log(s);
},100);
```

## info about semantic versions
http://semver.org/

## olimex through serial monitor
  screen /dev/tty.NoZAP-PL2303-00001014 115200
  pm2 logs

## pm2 log
  cd ~/......./.....
  DEBUG=cloud* pm2 start —name=cloud -o /tmp/cloud.log -e /tmp/cloud.error.log index.js

  pm2 logs cloud

## nodejs http client example
```javascript
var http = require('http');

http.get("........", function(res) {
  console.log("Got response: " + res.statusCode);

  res.on('data', function (chunk) {
    console.log('BODY length: ' + chunk.length);
  });

  res.on('end', function (chunk) {
    console.log('end');
  });


}).on('error', function(e) {
  console.log("Got error: " + e.message);
});
```

## http client using node request module
```javascript
var request = require('request');
var fs = require('fs');

request("http://10.0.0.71:8080/?action=snapshot").pipe(fs.createWriteStream('doodle.png'))
```

## nodejs webcam proxy
* http://stackoverflow.com/questions/14035864/pipe-an-mjpeg-stream-through-a-node-js-proxy
* https://www.npmjs.org/package/mjpeg-proxy
* see [[mjpgstreamer]]

## nodejs webcam stream with EventSource
https://github.com/koajs/webcam-mjpeg-stream

## nvm
  curl https://raw.githubusercontent.com/creationix/nvm/v0.11.1/install.sh | bash
=> Close and reopen your terminal to start using NVM
  nvm install 0.11

## socket.io-redis module
https://www.npmjs.org/package/socket.io-redis

## Access-Control-Allow-Origin
  res.header('Access-Control-Allow-Origin', '*')
  res.status(200).send(user.toObject());

## create a NodeJS client
```javascript
var io = require('socket.io-client');
var socket = io.connect('http://cloud.doodle3d.com:5000');

socket.on("error", function(data) {
  console.log("error",data);
})

socket.on("connect_error", function() {
  console.log("connect_error");
})

socket.on("connect", function() {
  console.log("connected");
})
```

## pm2
https://github.com/Unitech/pm2
  npm install pm2
  node_modules/pm2/bin/pm2 start server.js
  pm2 list
  pm2 describe ...

## install module from github
  npm install --save git+https://github.com/nkzawa/socket.io-stream.git

## stream handbook
https://github.com/substack/stream-handbook

## serve
```javascript
var express = require('express');
var app = express();
var http = require('http').Server(app);

app.use(express.static(__dirname, '/'));

http.listen(3000, function(){
  console.log('listening on *:3000');
});
```

## nodejs & ajax image loading
dit nog lezen: http://blog.marcon.me/post/31143865164/send-images-through-websockets


```javascript
//server

var app = require('express')();
var http = require('http').Server(app);
var fs = require('fs');


app.get('/', function(req, res){
  res.sendFile(__dirname+'/index.html');
})

app.get('/image', function(req, res){
  //res.sendFile(__dirname+"/image.png");
  //return;

  fs.readFile('image.png', function(err, data) {
    res.writeHead(200, {'Content-Type': 'text/plain'});
    res.end("data:image/png;base64,"+data.toString("base64"));
  });
})

http.listen(3000, function(){
  console.log('listening on *:3000');
});

```

```html
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>

<script>
$(document).ready(function() {

  $.get("/image", function(data) {
    $("#img").attr("src",data); //"data:image/jpeg;base64,"+b64;
  });

})
</script>

<img id="img">
```

## binaries over socket.io (wouter)
* https://github.com/Doodle3D/round-trip-test/blob/master/printer/index.js
* binaryjs (arne)
* https://github.com/liamks/Delivery.js
* http://socket.io/blog/introducing-socket-io-1-0/#binary

## send an image
```javascript
fs.readFile('test.png', function(err, data) {
  if (err) throw err; // Fail if the file can't be read.
    res.writeHead(200, {'Content-Type': 'image/png'});
    res.end(data); // Send the file data to the browser.
});
```

## body-parser
```javascript
var bodyParser = require('body-parser');
//....
app.use(bodyParser.json());
//....
app.post('/stl', function(req, res){
  res.send('<h1>Hello world</h1>');
  console.log(req.body);
})
```

## stljs
  https://github.com/cubehero/stljs/blob/master/template/povray.tmpl
needs [[povray]] and needs a change in '''node_modules/stljs/lib/to/image.js''': remove '-s' in command.


## debug
  DEBUG=socket.io* nodemon

## webcam stream via websockets
* http://phoboslab.org/log/2013/09/html5-live-video-streaming-via-websockets

## RPC using socket.io (with angular example)
* https://www.npmjs.org/package/socket.io-rpc

## bower
  sudo npm install -g bower

## webserver example with express
```javascript
var app = require('express')();
var http = require('http').Server(app);

app.get('/', function(req, res){
  //res.send('<h1>Hello world</h1>');
  res.sendfile('index.html');
})

http.listen(3000, function(){
  console.log('listening on *:3000');
});
```

## socket.io example with express
```javascript
var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

//default namespace
io.on('connection', function(socket) {
  console.log('a user connected');

  socket.on('disconnect', function(){
    console.log('user disconnected');
  });

  socket.on('chat message', function(msg){
    console.log(msg);
    io.emit('chat message', msg);
  });
});

///////// OTHER NAMESPACE
io.of("/printers").on('connection', function(socket) {

  socket.on('list', function(msg){
    console.log("printers.list")
    socket.emit("list",[
      {id:1, name: "test1",network:"network 1"},
      {id:2, name: "test2",network:"network 2"}
    ]);
  });

});

http.listen(3000, function(){
  console.log('listening on *:3000');
});
```


## nodejs socket.io with SSL
* http://stackoverflow.com/questions/6599470/node-js-socket-io-with-ssl

## websocket chat
* http://socket.io/get-started/chat/

## install via package managers
* https://github.com/joyent/node/wiki/Installing-Node.js-via-package-manager

## install
[https://github.com/joyent/node/wiki/backports.debian.org]()

1. Install Node and NPM
2. Run the following (as root):
```bash
  sudo echo "deb http://ftp.us.debian.org/debian wheezy-backports main" >> /etc/apt/sources.list
  sudo apt-get update
  sudo apt-get install nodejs-legacy
  #curl --insecure https://www.npmjs.org/install.sh | bash
  curl --insecure https://www.npmjs.org/install.sh > install.sh
  chmod +x install.sh
  sudo ./install.sh
```

## edit nodejs files remotely with sublimetext (and sftp package) and use nodemon for restarting node server on file change
note: nodemon likes to be installed globally with -g but it also works in your local folder
```bash
  npm install  nodemon
  export PATH=$PATH:`pwd`/node_modules/nodemon/bin
  nodemon.js
```

## make nodemon only watch single file
```nodemon -w server.js server.js```

## nodejs socket.io with angular
* http://stackoverflow.com/questions/14700865/node-js-angularjs-socket-io-connect-state-and-manually-disconnect

## socket.io with token authentication
* http://wlkns.co/node-js/socket-io-authentication-tutorial-server-and-client/
