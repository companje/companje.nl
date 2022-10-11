---
title: Vue
---

# redirect route
```javascript
routes: [
    { path: '/', redirect: '/teams'  },
    { path: '/teams', component: TeamsList },
```
or with alias (but url doesn't change)
```javascript
routes: [
    { path: '/teams', component: TeamsList, alias:'/' },
```

# watch the route updating
```javascript
watch: {
    $route(newRoute) {
      console.log(newRoute)
      this.teamId=newRoute.params.teamId
    },
    teamId(newId) {
      console.log('teamId changed?',newId)
      this.loadTeamMembers(newId);
    },
},
```

# vue-router
```bash
npm i --save vue-router
```

# v-model on own component
add this to the component
```javascript
{
  props: [ "modelValue"],
  emits: [ "update:modelValue" ],
  methods: {
    activateButton(opt) {
      this.$emit('update:modelValue', opt);
    },
  },
}
```
usage:
```xml
<your-component v-model="rating"></your-component>
```
  
# TheHeader and other components prefixed with The
when you have a component that is only used once it is recommemended (see vue styleguide) to prefix it with 'The'

# multiple elements in directly in template
in vue3 (not in vue2) you don't need a root element in a template. you can use a `<h2>` and a `<p>` directly in the `<template>` without wrapping them with a `<div>` or something. this is a feature called <em>fragments</em>.

# teleport
wrap a custom dialog with `<teleport to='body'>` to teleport for the dialog from a component to the body.

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
