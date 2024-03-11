# boilerplate
```sparql
CONSTRUCT {
  ?doc a sdo:ArchiveComponent .
  ?doc sdo:isPartOf ?isPartOf .
  ?doc rdfs:label ?label .
       
} WHERE { 
	?doc a aet:scnni .
  ?doc mf:is_part_of ?isPartOf .
  ?doc mf:beschrijving ?label .
}
```

# COALESCE
```sparql
BIND(CONCAT(COALESCE(?nummer,""),COALESCE(?code,"")) AS ?nummer_en_code)
```


