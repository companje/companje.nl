# jsondiff met geojson (eerst platslaan op UNId)
```bash
jq '.features |= map(. + {__key: .properties.UNId})' oud.geojson > oud.norm.json
jq '.features |= map(. + {__key: .properties.UNId})' nieuw.geojson > nieuw.norm.json

jd -set -setkeys __key oud.norm.json nieuw.norm.json
```

# voorbeeld diff
```diff
^ "SET"
^ {"setkeys":["__key"]}
@ ["features",{"__key":196},"properties","gerecht"]
- "Houten en 't Goye"
+ "Utengoye, 't Goy"
@ ["features",{"__key":141},"properties","gerecht"]
- "Houten en 't Goye"
+ "Utengoye, 't Goy"
@ ["features",{"__key":60},"properties","gerecht"]
- "Houten en 't Goye"
+ "Utengoye, 't Goy"
@ ["features",{"__key":179},"properties","gerecht"]
- "Houten en 't Goye"
+ "Utengoye, 't Goy"
@ ["features",{"__key":21},"properties","gerecht"]
- "Houten en 't Goy"
+ "Utengoye, 't Goy"
@ ["features",{"__key":175},"properties","_1650"]
- ""
+ "1651: Cornelis Stevensz van Schaick, of Teunis Stevensz  (OSG)"
@ ["features",{"__key":175},"properties","_1680"]
- ""
+ "1686: Steven Cornelisz van Schaick (OSG)"
@ ["features",{"__key":175},"properties","_1710"]
- ""
+ "1716: Adriaan Cornelisz van Schaick"
@ ["features",{"__key":175},"properties","gerecht"]
- "Houten en 't Goye"
+ "Utengoye, 't Goy"
```
