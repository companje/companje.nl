---
title: JSPM
---
* see [[react]]
* `jspm install`
* [[https://github.com/jspm/jspm-cli/blob/master/docs/registries.md#private-github|Setup private github access]] 
* my exercises are in the 'Doodle3D/react-jspm' folder 
* `jspm dl-loader --latest`

=====Error 'Spawn EMFILE'=====
command: `jspm install react`.
error: 'Spawn EMFILE'
solution:
  ulimit -n        #returns 256 on my Mac
  ulimit -n 1024   #increased the max number of open files.
  
=====steps=====
  npm install
  jspm install
  
