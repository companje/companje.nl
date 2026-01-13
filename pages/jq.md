---
title: jq (sed for json)
---

# zie ook 'jd'
* [jd](/jd)

# count items in array
```bash
jq --raw-output '.["aktes"] | length' 2437.json
```

# join array
```bash
jq --raw-output '.["@graph"][] | [.id, .orde, .vleu_huisnummer, (try .relaties|join("|")) ] | @csv' vleu-ab-bouwvergunning_1_2299_flexis.json > bouwvergunningen.txt
```

# unusually named fields at top-level (use [""])
```bash
jq --raw-output '.["@graph"][].straatnaam' vleu-con_strn_2299_flexis.json > straatnamen.txt
```

# 2 columns
```bash
jq --raw-output '.results.bindings[] | .sub.value + "," + .label.value' < rce-cht.json > rce-cht.csv```
```

# live testing jq queries
https://jqplay.org/

# interesting post
<http://www.compciv.org/recipes/cli/jq-for-parsing-json>

# format json
```bash
cat input.json | jq -r . > formatted.json
```

# get all id's from array and display as raw list
```bash
cat docs.json | jq -r ".rows[] .id"
```
