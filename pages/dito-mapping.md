# identifier
```sparql
?b_rubriek a rico:Identifier ;
  rico:hasIdentifierType def:RubriekCode ;
  rico:textualValue ?nummer_code;
.
...
BIND(IRI(CONCAT(STR(?doc), "/rubriek_",fn:escape(?nummer_code))) AS ?b_rubriek)
```

# inventarisnummer
```sparql
    BIND(IRI(CONCAT(STR(?doc), "/inventarisnummer",fn:escape(?inventarisnummer))) AS ?b_inventarisnummer)
...
    ?b_inventarisnummer a rico:Identifier ;
      rico:hasIdentifierType def:Inventarisnummer ;
      rico:textualValue ?inventarisnummer ;
    .
```
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

# Imageserver
```sparql
BIND(fn:image_server(?doc,'thumb') AS ?thumbnail)
```

# COALESCE
```sparql
BIND(CONCAT(COALESCE(?nummer,""),COALESCE(?code,"")) AS ?nummer_en_code)
```

# Custom functions
```sparql
fn:datering_bj_ej(a,b)  # creates a date period from two separate days. It also formats it as ISO 8601
fn:datering(a)          # takes a date or date period and formats it as ISO 8601
fn:get_first_year(a)    # get first year from a date range: 1800-1900 returns 1800
fn:get_last_year(a)     # get last year from a date range: 1800-1900 returns 1900
fn:date(a)              # formats a single date to ISO 8601
fn:fullname(?voornaam, ?tussenvoegsel, ?achternaam)
```
