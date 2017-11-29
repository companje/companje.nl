---
layout: default
title: Doodle3D Transform
---

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
