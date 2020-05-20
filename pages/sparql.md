# Lekker bezig
```
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX v: <https://archief.io/veld#>

SELECT * WHERE {
  GRAPH <https://data.netwerkdigitaalerfgoed.nl/MI2RDF/mi2rdf/graphs/HUA-beeldbank-april2020-9bestanden> {
    ?sub v:Deelcollectie "Nederlandse Spoorwegen" .	
    BIND(REPLACE(str(?sub), "https://archief.io/id/", "https://hetutrechtsarchief.nl/beeld/") AS ?shownAt)
    OPTIONAL { ?sub v:Catalogusnummer ?Catalogusnummer }
    OPTIONAL { ?sub v:Beschrijving ?Beschrijving }
    OPTIONAL { ?sub v:LBTWD ?LBTWD }
    OPTIONAL { ?sub v:THWTW ?THWTW }
    OPTIONAL { ?sub v:Afmeting ?Afmeting }
    OPTIONAL { ?sub v:Afmeting_2 ?Afmeting_2 }
    OPTIONAL { ?sub v:Opmerkingen ?Opmerkingen }
    OPTIONAL { ?sub v:Opschrift ?Opschrift }
    OPTIONAL { ?sub v:Datering_vroegst ?Datering_vroegst }
    OPTIONAL { ?sub v:Datering_laatst ?Datering_laatst }
    OPTIONAL { ?sub v:fnc_lic ?licentie }
    OPTIONAL { ?sub v:Auteursrechthouder ?Auteursrechthouder }
    OPTIONAL { ?sub v:CXTWD_VERVAARDIGER ?CXTWD_VERVAARDIGER }   
    OPTIONAL { ?sub v:cxtwd_uitgdruk ?cxtwd_uitgdruk }   
    OPTIONAL { ?sub v:Materiaal ?Materiaal }
    OPTIONAL { ?sub v:Techniek ?Techniek }
  } 
  FILTER regex(?licentie, "CC BY 4.0|CC0 1.0|Publiek Domein 1.0", "i")
}

#LIMIT 10000
#OFFSET 20000
```

# Alle verschillende licenties
```sparql
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX v: <https://archief.io/veld#>

SELECT distinct(?licentie) WHERE {
  ?sub ?pred ?obj .
  ?sub v:fnc_lic ?licentie 
} LIMIT 10
```

# Alle vervaardigers
```sparql
PREFIX v: <https://archief.io/veld#>
SELECT distinct ?obj WHERE {
  ?sub v:CXTWD_VERVAARDIGER ?obj .
} 
```

# Alle straatnamen met daarachter bijbehorende plaats
```sparql
PREFIX v: <https://archief.io/veld#>
SELECT distinct ?straat, ?plaats WHERE {
  ?sub v:CXTWD_STRAATNAAM ?straat .
  ?sub v:CXTWD_PLAATSNAAM ?plaats .
} 
```

# Alle trefwoorden
```sparql
PREFIX v: <https://archief.io/veld#>
SELECT distinct ?trefwoord WHERE {
  ?sub v:tr ?trefwoord
} 
```

# Alle veldnamen maar alleen binnen 1 specifieke GRAPH (en evt binnen Deelcollectie)
```sparql
PREFIX v: <https://archief.io/veld#>

SELECT distinct ?veldnaam WHERE {
  GRAPH <https://data.netwerkdigitaalerfgoed.nl/MI2RDF/mi2rdf/graphs/HUA-beeldbank-april2020-9bestanden> {
    # ?sub v:Deelcollectie "Nederlandse Spoorwegen" .	
    ?sub ?veldnaam ?obj
  }
} ORDER BY ?veldnaam

```

# Alle vervaardigers (van alleen Beeldbank Waterlands Archief) gesorteerd op aantal afbeeldingen
```sparql
PREFIX v: <https://archief.io/veld#>
SELECT count(*) as ?aantal, ?vervaardiger WHERE {
  GRAPH <https://data.netwerkdigitaalerfgoed.nl/MI2RDF/mi2rdf/graphs/BEELDBANK_14_131_MAISI_EXP-8066> {
   ?sub v:CXTWD_VERVAARDIGER ?vervaardiger
  }
} ORDER BY DESC(?aantal)
```

# Alle velden v:tr én v:TR met UNION
```sparql
PREFIX v: <https://archief.io/veld#>
SELECT count(*) as ?aantal, ?waarde WHERE {
  GRAPH <https://data.netwerkdigitaalerfgoed.nl/MI2RDF/mi2rdf/graphs/BEELDBANK_14_131_MAISI_EXP-8066> {
   
    { ?sub v:TR ?waarde } union { ?sub v:tr ?waarde } 

  }
} ORDER BY DESC(?aantal)
```

# Persoon op afbeelding gesorteerd op aantal
```sparql
PREFIX RiCo: <https://www.ica.org/standards/RiC/ontology#>
PREFIX v: <https://archief.io/veld#>
PREFIX soort: <https://archief.io/soort#>

SELECT count(*) as ?aantal, ?naam WHERE {
  GRAPH <https://data.netwerkdigitaalerfgoed.nl/MI2RDF/mi2rdf/graphs/BEELDBANK_14_131_MAISI_EXP-8066> {
   ?sub v:aet soort:psafb .
   ?sub RiCo:title ?naam
  }
} ORDER BY DESC(?aantal)
```
