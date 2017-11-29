---
layout: default
title: Doodle3D Transform
---

# download sketch from console
(for example when due to an error the interface is gone)
```js
actions.files.downloadSketch()
```

# see all actions
type this in the console:
```js
actions
```

# actions and keyboard shortcuts
```js
actions.duplicateSelection()

actions.align(horizontal, vertical);
horizontal = vertical = -1 || 0 || 1 || false;
```

in hotkeys.js
```js
case 'd':
  if (commandKey) {
    dispatch(actions.duplicateSelection());
  }
  break;
```

# testje:
```js
radi=[2.6,4,5,6.5,8]; for (var i in radi) actions.addObject({ type: 'CIRCLE', transform: new CAL.Matrix({x:i*10}), circle: { radius:radi[i]/2*10, segment: Math.PI * 2 } });
```

# 4 cirkels:
```js
radi=[2.6,4,5,6.5,8]; for (var i in radi) actions.addObject({ type: 'CIRCLE', transform: new CAL.Matrix({x:i*10}), circle: { radius:radi[i]/2*10, segment: Math.PI * 2 } }); 
```

# gelijkzijdige driehoek
```js
actions.addObject({ type: 'POLY_POINTS', polyPoints: { radius:6.5/2*10, numPoints:3 } });
```

# combi:
```js
radi=[2.6,4,5,6.5,8]; for (var i in radi) actions.addObject({ type: 'CIRCLE', circle: { radius:radi[i]/2*10, segment: Math.PI * 2 } }); actions.addObject({ type: 'POLY_POINTS', polyPoints: { radius:6.5/2*10, numPoints:3 } });
```
