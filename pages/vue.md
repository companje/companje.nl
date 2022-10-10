---
title: Vue
---

# slots 
```<template v-slot:header>``` can be replaced with ```<template #header></template>```
```

# "export 'App' was not found in './App.vue'
make sure you don't have { } around App at import. It should be like this:
```js
import App from "./App.vue";
```

# quickstart
https://ej2.syncfusion.com/vue/documentation/treegrid/getting-started/
```bash
npm install -g @vue/cli
npm install -g @vue/cli-init
vue init webpack-simple quickstart
cd quickstart
npm install
#...
```
