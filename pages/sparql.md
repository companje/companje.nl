# Query via CURL als text/csv
```bash
# er zijn op het moment van schrijven +100.000 afbeeldingen in de beeldbank waarvan 
# een of meerdere 'oude nummers' bekend zijn. Onderstaande loop voert meerdere queries 
# (in blokken van 10.000 resultaten) uit op de triplestore van Het Utrechts Archief.
# de output kun je redirecten naar 1 groot csv bestand.

# Usage:
# $ ./download-csv.sh > output.csv
# of
# $ echo "Catalogusnummer,Oudnummer" > output.csv 
# $ ./download-csv.sh >> output.csv

for i in `seq 0 10000 120000`
do 
  curl -H "Accept: text/csv" https://data.netwerkdigitaalerfgoed.nl/_api/datasets/hetutrechtsarchief/mi2rdf/services/mi2rdf/sparql -X POST --data 'query=PREFIX%20xsd%3A%20%3Chttp%3A%2F%2Fwww.w3.org%2F2001%2FXMLSchema%23%3E%0APREFIX%20rdf%3A%20%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0APREFIX%20rdfs%3A%20%3Chttp%3A%2F%2Fwww.w3.org%2F2000%2F01%2Frdf-schema%23%3E%0APREFIX%20soort%3A%20%3Chttps%3A%2F%2Farchief.io%2Fsoort%23%3E%0APREFIX%20v%3A%20%3Chttps%3A%2F%2Farchief.io%2Fveld%23%3E%0A%0ASELECT%20%3FCatalogusnummer%2C%20%3Foudnummer%20%7B%0A%20%20%7B%20%3Fsub%20v%3Anummer%20%3FCatalogusnummer%20%3B%20v%3Aoudnummer_1%20%3Foudnummer%7D%0A%20%20UNION%0A%20%20%7B%20%3Fsub%20v%3Anummer%20%3FCatalogusnummer%20%3B%20v%3Aoudnummer_2%20%3Foudnummer%7D%20%0A%20%20UNION%0A%20%20%7B%20%3Fsub%20v%3Anummer%20%3FCatalogusnummer%20%3B%20v%3Aoudnummer_3%20%3Foudnummer%7D%20%0A%7D%0ALIMIT%2010000%0AOFFSET%200'$i \
   | awk 'FNR>1'  # zorgt dat de header telkens worden weggefilterd
done
```

# Opvragen 'oudnummer_*' en via UNION als losse triples
```sparql
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX soort: <https://archief.io/soort#>
PREFIX v: <https://archief.io/veld#>

SELECT ?Catalogusnummer, ?oudnummer {
  { ?sub v:nummer ?Catalogusnummer ; v:oudnummer_1 ?oudnummer}
  UNION
  { ?sub v:nummer ?Catalogusnummer ; v:oudnummer_2 ?oudnummer} 
  UNION
  { ?sub v:nummer ?Catalogusnummer ; v:oudnummer_3 ?oudnummer} 
}
LIMIT 10000
OFFSET 0
```

# Lekker bezig voor Wikimedia
```sparql
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
