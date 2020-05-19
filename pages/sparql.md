# SPARQL

## Alle vervaardigers
```sparql
PREFIX v: <https://archief.io/veld#>
SELECT distinct ?obj WHERE {
  ?sub v:CXTWD_VERVAARDIGER ?obj .
} 
```

## Alle straatnamen met daarachter bijbehorende plaats
```sparql
PREFIX v: <https://archief.io/veld#>
SELECT distinct ?straat, ?plaats WHERE {
  ?sub v:CXTWD_STRAATNAAM ?straat .
  ?sub v:CXTWD_PLAATSNAAM ?plaats .
} 
```

## Alle trefwoorden
```sparql
PREFIX v: <https://archief.io/veld#>
SELECT distinct ?trefwoord WHERE {
  ?sub v:tr ?trefwoord
} 

## Alle veldnamen maar alleen binnen 1 specifieke GRAPH
PREFIX RiCo: <https://www.ica.org/standards/RiC/ontology#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX v: <https://archief.io/veld#>

SELECT distinct ?veldnaam WHERE {
  GRAPH <https://data.netwerkdigitaalerfgoed.nl/MI2RDF/mi2rdf/graphs/BEELDBANK_14_131_MAISI_EXP-8066> {
    ?sub ?veldnaam ?obj
  }
} 
```

## Alle vervaardigers (van alleen Beeldbank Waterlands Archief) gesorteerd op aantal afbeeldingen
```sparql
PREFIX v: <https://archief.io/veld#>
SELECT count(*) as ?aantal, ?vervaardiger WHERE {
  GRAPH <https://data.netwerkdigitaalerfgoed.nl/MI2RDF/mi2rdf/graphs/BEELDBANK_14_131_MAISI_EXP-8066> {
 	 ?sub v:CXTWD_VERVAARDIGER ?vervaardiger
  }
} ORDER BY DESC(?aantal)
```

## Alle velden v:tr én v:TR met UNION
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
