---
title: jq (sed for json)
---

interesting post: <http://www.compciv.org/recipes/cli/jq-for-parsing-json>

# format json
```bash
cat input.json | jq -r . > formatted.json
```

# get all id's from array and display as raw list
```bash
cat docs.json | jq -r ".rows[] .id"
```
