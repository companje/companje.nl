## CSV bestand met aantal keer dat een Wikidata item per jaar voorkomt in de dataset van HUA
Onderstaande loop voert meerdere queries uit
(in blokken van 10.000 resultaten) uit op de triplestore van Het Utrechts Archief.
De output kun je redirecten naar 1 groot csv bestand.
Er wordt geen rekening gehouden met tijdsperiodes of onzekere datums
```bash
echo > data.csv

for i in `seq 0 10000 200000`
do
 query='PREFIX%20wd%3A%20%3Chttp%3A%2F%2Fwww.wikidata.org%2Fentity%2F%3E%0APREFIX%20dct%3A%20%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%0APREFIX%20rdfs%3A%20%3Chttp%3A%2F%2Fwww.w3.org%2F2000%2F01%2Frdf-schema%23%3E%0A%0ASELECT%20%3Fwd%2C%20%3Fyear%2C%20count%28%3Fyear%29%20as%20%3Faantal%20WHERE%20%7B%0A%20%20%3Fsub%20dct%3Aspatial%20%3Fwd%20.%0A%20%20%3Fsub%20dct%3Adate%20%3Fyear%20.%0A%20%20filter%20regex%28%3Fwd%2C%20%22wikidata%22%29%0A%7D%20%0A%23order%20by%20desc%28%3Faantal%29%0Alimit%2010000%0Aoffset%20'$i

 echo $query
 echo

 curl https://data.netwerkdigitaalerfgoed.nl/_api/datasets/hetutrechtsarchief/Dataset/services/Dataset/sparql -X POST --data query=$query -H "Accept: text/csv" | awk 'FNR>1' >> data.csv  # awk zorgt dat de header telkens worden weggefilterd
done
```

## Ivar's SPARQL queries
* https://www.notion.so/SPARQL-queries-d771418872824af6842cfb27f89fd987#14c3cde3bad242f98497d52098787292

## Query via CURL als text/csv
```bash
# er zijn op het moment van schrijven +100.000 afbeeldingen in de beeldbank waarvan 
# een of meerdere 'oude nummers' bekend zijn. Onderstaande loop voert meerdere queries 
# (in blokken van 10.000 resultaten) uit op de triplestore van Het Utrechts Archief.
# de output kun je redirecten naar 1 groot csv bestand.

# Usage:
# ./download-csv.sh

output_base=output/HUA-catnr-oudnr

echo "Catalogusnummer,Oudnummer" > $output_base.csv

for i in `seq 0 10000 120000`
do 
  curl https://data.netwerkdigitaalerfgoed.nl/_api/datasets/hetutrechtsarchief/mi2rdf/services/mi2rdf/sparql -X POST --data 'query=PREFIX%20xsd%3A%20%3Chttp%3A%2F%2Fwww.w3.org%2F2001%2FXMLSchema%23%3E%0APREFIX%20rdf%3A%20%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0APREFIX%20rdfs%3A%20%3Chttp%3A%2F%2Fwww.w3.org%2F2000%2F01%2Frdf-schema%23%3E%0APREFIX%20soort%3A%20%3Chttps%3A%2F%2Farchief.io%2Fsoort%23%3E%0APREFIX%20v%3A%20%3Chttps%3A%2F%2Farchief.io%2Fveld%23%3E%0A%0ASELECT%20%20%3FCatalogusnummer%2C%20%3Foudnummer%20WHERE%20%7B%0A%0A%20%20SELECT%20%3FCatalogusnummer%2C%20%3Foudnummer%20%20%7B%0A%20%20%20%20%7B%20%3Fsub%20v%3Anummer%20%3FCatalogusnummer%20%3B%20v%3Aoudnummer_1%20%3F_oudnummer%7D%0A%20%20%20%20UNION%0A%20%20%20%20%7B%20%3Fsub%20v%3Anummer%20%3FCatalogusnummer%20%3B%20v%3Aoudnummer_2%20%3F_oudnummer%7D%20%0A%20%20%20%20UNION%0A%20%20%20%20%7B%20%3Fsub%20v%3Anummer%20%3FCatalogusnummer%20%3B%20v%3Aoudnummer_3%20%3F_oudnummer%7D%0A%20%20%20%20BIND%28REPLACE%28%3F_oudnummer%2C%22%5Cn%22%2C%22%22%29%20as%20%3Foudnummer%29%20%23%20remove%20linefeed%20from%20%3F_oudnumber%0A%20%20%7D%0A%20%20ORDER%20BY%20%3Foudnummer%0A%7D%0A%0ALIMIT%2010000%0AOFFSET%200'$i \
   -H "Accept: text/csv" | awk 'FNR>1' >> $output_base.csv # zorgt dat de header telkens worden weggefilterd
done

#convert to Markdown
csv2md $output_base.csv > $output_base.md  

#convert to HTML
marked $output_base.md > $output_base.html 

# Convert to PDF
markdown-pdf $output_base.md 
```

## Opvragen 'oudnummer_*' en via UNION als losse triples
```sparql
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX soort: <https://archief.io/soort#>
PREFIX v: <https://archief.io/veld#>

SELECT  ?Catalogusnummer, ?oudnummer WHERE {  # subselect voor ORDER BY icm OFFSET
  SELECT ?Catalogusnummer, ?oudnummer  {
    { ?sub v:nummer ?Catalogusnummer ; v:oudnummer_1 ?_oudnummer}
    UNION
    { ?sub v:nummer ?Catalogusnummer ; v:oudnummer_2 ?_oudnummer} 
    UNION
    { ?sub v:nummer ?Catalogusnummer ; v:oudnummer_3 ?_oudnummer}
    BIND(REPLACE(?_oudnummer,"\n","") as ?oudnummer) # remove linefeed from ?_oudnumber
  }
  ORDER BY ?oudnummer
}

LIMIT 10000
OFFSET 0
```

## Lekker bezig voor Wikimedia
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

## Alle verschillende licenties
```sparql
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX v: <https://archief.io/veld#>

SELECT distinct(?licentie) WHERE {
  ?sub ?pred ?obj .
  ?sub v:fnc_lic ?licentie 
} LIMIT 10
```

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
```

## Alle veldnamen maar alleen binnen 1 specifieke GRAPH (en evt binnen Deelcollectie)
```sparql
PREFIX v: <https://archief.io/veld#>

SELECT distinct ?veldnaam WHERE {
  GRAPH <https://data.netwerkdigitaalerfgoed.nl/MI2RDF/mi2rdf/graphs/HUA-beeldbank-april2020-9bestanden> {
    # ?sub v:Deelcollectie "Nederlandse Spoorwegen" .	
    ?sub ?veldnaam ?obj
  }
} ORDER BY ?veldnaam

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

## Persoon op afbeelding gesorteerd op aantal
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
