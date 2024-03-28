# voorbeeld inventarisnummer bnode
```sparql
  CONSTRUCT {
  ?b_inventarisnummer a rico:Identifier ;
    rico:hasIdentifierType def:Inventarisnummer ;
    rdfs:label ?lbl_inventarisnummer ;
    rico:textualValue ?inventarisnummer ;
  .
  
} WHERE { 
     OPTIONAL { 
      ?doc hasc:inventarisnummer ?inventarisnummer ;
      BIND(fn:bnode1(?doc,"inventarisnummer") AS ?b_inventarisnummer) 
			BIND(CONCAT("Inventarisnummer: ",?inventarisnummer) AS ?lbl_inventarisnummer)
    }
```
# voorbeeld met bnode met alleen naam van het veld
```sparql
  # construct
  ?b_vertaling a sdo:Text ;
    sdo:text ?vertaling ;
    sdo:additionalType def:Vertaling ;
  .
  # where
  OPTIONAL { ?doc tsc:vertaling ?vertaling }
  BIND(fn:bnode1(?doc,"vertaling") AS ?b_vertaling)
```


# voorbeeld bnode en label
```sparql
BIND(fn:bnode(?doc,"inventarisnummer",?inventarisnummer) AS ?b_inventarisnummer)  
BIND(CONCAT("Inventarisnummer: ",?inventarisnummer) AS ?lbl_inventarisnummer)
```

# BINDs voor bnodes en labels
```sparql
BIND(fn:bnode(?doc,"dossiernr_kvk",?dossiernr_kvk) AS ?b_dossiernr_kvk)  
BIND(fn:bnode(?doc,"inventarisnummer",?inventarisnummer) AS ?b_inventarisnummer)  
BIND(fn:bnode(?doc,"jaar_sluiting",?jaar_sluiting) AS ?b_jaar_sluiting)  
BIND(fn:bnode(?doc,"standplaats_kvk",?standplaats_kvk) AS ?b_standplaats_kvk)
BIND(fn:bnode(?doc,"vestigingsplaats",?vestigingsplaats) AS ?b_vestigingsplaats)

BIND(CONCAT("Inventarisnummer: ",?inventarisnummer) AS ?lbl_inventarisnummer)
BIND(CONCAT("Jaar van sluiting: ",?jaar_sluiting) AS ?lbl_jaar_sluiting)
BIND(CONCAT("Dossiernr KvK: ",?dossiernr_kvk) AS ?lbl_dossiernr_kvk)  
BIND(CONCAT("Standplaats KvK: ",?standplaats_kvk) AS ?lbl_standplaats_kvk)
BIND(CONCAT("Vestigingsplaats: ",?vestigingsplaats) AS ?lbl_vestigingsplaats)

BIND (STRDT(?jaar_sluiting, xsd:year) AS ?jaar_sluiting) # waarom kan dit niet met xsd:year?
```


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
  ?doc sdo:additionalType aet:tscni .

} WHERE { 
  ?doc a aet:tscni .
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
