---
title: Vue
---

# dynamic components with keep-alive 
when using `keep-alive` vue keeps data and won't destroy it when changing component:is
```xml
<keep-alive>
  <component :is="selectedComponent">hoi</component>
</keep-alive>
```

# slot props
slot props pass data from a component to the parent of the component. this way you can have a v-for in a component and have the parent use the data and pass in custom styling/data.

the component:
```xml
<template>
  <ul>
    <li v-for="goal in goals" :key="goal">
      <slot :myItem="goal" another-prop="..."></slot>
    </li>
  </ul>
</template>
```

the parent:
```xml
<the-component>
  <template #default="slotProps">
    <h2>{{slotProps.myItem}}
  </template
</the-component>
```

# slots 
```<template v-slot:header>``` can be replaced with ```<template #header></template>```

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
