---
title: Webpack
---

## Snowpack
tip v Sylvain: snowpack alternatief webpack en parcel

##  dev server toegang buiten localhost 
```js
"scripts": {
    "start": "webpack-dev-server -w --host 0.0.0.0",
...
```

##  webpack-cli 
De nieuwe `webpack-cli` maakt starteb met webpack makkelijk met een init commando: `webpack-cli init`
https://github.com/webpack/webpack-cli

##  init ...peter... nieuw bash scriptje toegevoegd aan `/usr/local/bin` met:
```bash
atom -a . # add project folder to atom
mkdir src
touch src/index.js
npm init -y
npm install -D webpack-dev-server
# add scripts to package.json using json cli
json -I -f package.json -e 'this.scripts.start = "webpack-dev-server -w --content-base dist/"'

webpack-cli init
```

Vereist: 
`npm install -g webpack-dev-server json webpack-cli `

##  Webpack and Rollup: the same but different =
 https://medium.com/webpack/webpack-and-rollup-the-same-but-different-a41ad427058c
Medium
Webpack and Rollup: the same but different – webpack – Medium
This week, Facebook merged a monster pull request into React that replaced its existing build process with one based on Rollup, prompting…
