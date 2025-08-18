# Omliggende Percelen
```sparql
prefix geo: <http://www.opengis.net/ont/geosparql#>
prefix geof: <http://www.opengis.net/def/function/geosparql/>
prefix id: <https://hisgis.hualab.nl/id/>
prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#>
prefix def: <https://hisgis.hualab.nl/def/>
prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
prefix uom: <http://www.opengis.net/def/uom/OGC/1.0/>

select ?g1 ?g2 ?g2Tooltip ?g1Tooltip ?g1Color ?g1Label where {
  ?p1 geo:asWKT ?g1 .
  ?p2 geo:asWKT ?g2 .
  ?p2 a id:perceel .
  optional { ?p2 def:naam/rdfs:label ?g2Tooltip . }
  optional { ?p1 def:naam/rdfs:label ?g1Tooltip . }
  bind ("green" as ?g1Color)
  bind (   strdt(concat("yy<a href='",str(?p1),"'>link</a>xx"),rdf:HTML) as ?g1Label )

  VALUES (?p1) { (id:perceel-14854) }
  # FILTER(geof:sfTouches(?g1, ?g2) )
  BIND( geof:distance(?g1, ?g2, uom:metre) AS ?afstand_m )
  FILTER( ?afstand_m <= 100 )
} limit 200
```

# URI's zonder inkomende relaties (met subselect ivm performance)
* let op: deze URI's moeten wel zelf een gedeelde property hebben zoals sdo:url
```sparql
PREFIX sdo: <https://schema.org/>

SELECT DISTINCT ?s WHERE {
  {
    SELECT DISTINCT ?s WHERE {
      ?s sdo:url ?o
    }
  }
  FILTER NOT EXISTS {
    ?x ?y ?s
  }
}
```

# per subject van een bepaalde class het aantal alle inkomende relaties
```sparql
SELECT ?bezitter (COUNT(*) AS ?aantalInkomendeRelaties) WHERE {
  ?bezitter a <https://hisgis.hualab.nl/id/bezitter> .
  ?subject ?predicate ?bezitter .
}
GROUP BY ?bezitter
ORDER BY DESC(?aantalInkomendeRelaties)
```

# aantal inkomende relaties per class
```sparql
SELECT ?class (COUNT(*) AS ?aantalInkomendeRelaties) WHERE {
  ?subject ?predicate ?object .
  ?object a ?class .
}
GROUP BY ?class
ORDER BY DESC(?aantalInkomendeRelaties)
```

# aantal uitgaande (?) relaties per class
```sparql
SELECT ?class (COUNT(*) AS ?aantalInkomendeRelaties) WHERE {
  ?subject ?predicate ?object .
  ?subject a ?class .
}
GROUP BY ?class
ORDER BY DESC(?aantalInkomendeRelaties)
```

# make 0, 1 or 2 hops to find item with geometry
```sparql
PREFIX def: <https://X.nl/def/>
PREFIX geo: <http://www.opengis.net/ont/geosparql#>

SELECT * WHERE {
  ?s ?p <https://X.nl/id/sub_bisschop_haanhaver> .

  {
    ?s (geo:hasGeometry/geo:asWKT) ?geo .
    BIND(STR(?s) AS ?geoLabel)
  }
  UNION
  {
    ?mid ?any ?s .
    ?mid (geo:hasGeometry/geo:asWKT) ?geo .
    BIND(STR(?mid) AS ?geoLabel)
  }
  UNION
  {
    ?mid1 ?any1 ?s .
    ?mid2 ?any2 ?mid1 .
    ?mid2 (geo:hasGeometry/geo:asWKT) ?geo .
    BIND(STR(?mid2) AS ?geoLabel)
  }
}
```

# count classes
```sparql
prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#>
prefix geo: <http://www.opengis.net/ont/geosparql#>
prefix id: <https://hisgis.hualab.nl/id/>

select ?o (min(?label) as ?voorbeeld) (count(?o) as ?count) where {
  ?s a ?o.
  optional { ?s rdfs:label ?label }
} group by ?o ?labels ?olabel
order by desc(?count)
```

# group concat
```sparql
prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#>
select distinct ?s (group_concat(?label;separator=" / ") as ?labels) where {
  ?s a <https://hisgis.hualab.nl/id/kwartier> .
  ?s rdfs:label ?label .
}
group by ?s
order by ?s
```

# class frequency
```sparql
select (count(?o) as ?c) ?o where {
  ?s a ?o.
} 
group by ?o
order by desc(?c) 
```

# values & filter combined
```sparql
prefix id: <https://hisgis.hualab.nl/id/>
prefix def: <https://hisgis.hualab.nl/def/>
prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#>
prefix geo: <http://www.opengis.net/ont/geosparql#>
prefix hh: <https://hisgis.hualab.nl/id/hoogheemraadschap/>

select distinct ?perceel ?bezitter_naam ?geo ?geoColor where {
  ?perceel geo:asWKT ?geo .
  ?perceel def:hoogheemraadschap ?hoogheemraadschap .
  ?perceel def:bezitsregistratie ?bezitsregistratie .
  ?bezitsregistratie def:namen ?bezitter_naam .
  
  VALUES (?hoogheemraadschap) { 
    (hh:Lekdijk_Bovendams)
    (hh:Lekdijk_Benedendams) }
  
  FILTER (REGEX(?bezitter_naam, "Schaik|Schaick", "i")).
  
  bind(
    if(?hoogheemraadschap = hh:Lekdijk_Bovendams, "red",
    if(?hoogheemraadschap = hh:Lekdijk_Benedendams, "blue",
    "default")) as ?geoColor)
}
```

# LD-Frames
* https://docs.triply.cc/generics/JSON-LD-frames/
* * https://w3c.github.io/json-ld-framing/

# HISGIS Utrecht <1832 Gerechten
* https://data.netwerkdigitaalerfgoed.nl/hetutrechtsarchief/-/queries/Gerechten-HISGIS/
![Screenshot 2024-11-25 at 21 23 50](https://github.com/user-attachments/assets/643b051b-ed56-4357-b7ec-3c0730eb4202)


# Wikimedia Commons images around a coordinate
https://commons-query.wikimedia.org/
```sparql
#Images taken 1km around a center
#defaultView:Map{"hide":["?coor"]}
# query by Jura1, 2020-11-12
SELECT ?fileLabel ?fileDescription ?image ?coor
WHERE 
{
  hint:Query hint:optimizer "None".
  SERVICE <https://query.wikidata.org/sparql> { wd:Q34928987 wdt:P625 ?center }  # Wikidata item with coordinates
  SERVICE wikibase:around {
      ?file wdt:P1259 ?coor.
      bd:serviceParam wikibase:center ?center .
      bd:serviceParam wikibase:radius ".2". # 1 kilometer around
  }  
  ?file schema:contentUrl ?url .
  bind(iri(concat("http://commons.wikimedia.org/wiki/Special:FilePath/", wikibase:decodeUri(substr(str(?url),53)))) AS ?image)  
  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en" }
}
```



# luchtfoto rgb layer in triply geo plugin
```sparql
prefix dct: <http://purl.org/dc/terms/>
prefix geo: <http://www.opengis.net/ont/geosparql#>
prefix pos: <http://www.w3.org/2003/01/geo/wgs84_pos#>
prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#>
prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

select * where {
  ?s dct:spatial ?p .
  ?p pos:lat ?lat .
  ?p pos:long ?long .
  ?p rdfs:label ?pointTooltip .
  bind(STRDT(CONCAT("Point(",?long," ",?lat,")"),geo:wktLiteral) AS ?point)
  # BIND("https://service.pdok.nl/kadaster/kadastralekaart/wms/v5_0?request=GetCapabilities&service=WMS" AS ?mapEndpoint)
  # bind("https://services.rce.geovoorziening.nl/wms?" as ?mapEndpoint)
  bind("https://service.pdok.nl/hwh/luchtfotorgb/wms/v1_0" as ?mapEndpoint)
  bind("test <b>...</b>"^^rdf:HTML as ?pointLabel)
  bind("yellow" as ?pointColor)  
} 
```
![Screenshot 2024-06-17 at 02 05 12](https://github.com/companje/companje.nl/assets/156066/14b28f1c-305b-418e-99e6-b962e0b1e07e)


# alle UDS trefwoorden met het aantal verwijzingen
```sparql
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>

SELECT distinct ?uri ?label (COUNT(?uri) AS ?count)
WHERE {
  {
    ?uri ?p ?o .
    ?uri rdfs:label ?label .
    FILTER(STRSTARTS(STR(?uri), "http://documentatie.org/id/trefwoord/"))
  }
  UNION
  {
    ?s ?p ?uri .
    ?uri rdfs:label ?label .
    FILTER(STRSTARTS(STR(?uri), "http://documentatie.org/id/trefwoord/"))
  }
}
GROUP BY ?uri ?label
order by desc(?count)
```

# personen afgeleid van een record waarbij er geen geboorte event aan het record is gekoppeld
```sparql
prefix schema: <http://schema.org/>
prefix prov: <http://www.w3.org/ns/prov#>

select (count(?persoon) as ?count) where {
  ?persoon a schema:Person .
  ?persoon prov:wasDerivedFrom ?record .
  
  FILTER NOT EXISTS { 
    ?event prov:wasDerivedFrom ?record .
    ?event a <https://data.niod.nl/WO2_Thesaurus/events/6360> .
  }
  
}
```

# aantal per type
```sparql
prefix schema: <http://schema.org/>

select ?type (count(?type) as ?count) where {
  ?s a ?type .
}
GROUP BY ?type
```

# md5 hash
```sparql
select * where {
  bind(md5("1444: St Pieter (MG))") AS ?hash)
}
```

# convert CSV with SPARQL CONSTRUCT result to Turtle
(instead you can download 'Response' from Triply which is already in .nt format).

```python
import csv
from rdflib import Graph, URIRef, Literal

csv_file = open("data/sparql-construct/Query 2.csv")
ttl_file = open("data/sparql-construct/Query 2.ttl", "w")

g = Graph()

for row in csv.DictReader(csv_file):
  sub = URIRef(row["subject"])
  pred = URIRef(row["predicate"])
  obj = URIRef(row["object"]) if row["object"].startswith(("http://", "https://")) else Literal(row["object"])
  g.add((sub, pred, obj))

ttl_file.write(g.serialize(format="turtle"))
```

# custom functions in RDFLIB to call from SPARQL
```python
#!/usr/bin/env python3

from rdflib import Graph, URIRef, Literal
from rdflib.plugins.sparql.operators import custom_function

g = Graph()

@custom_function(URIRef("http://example.org/myCustomFunction"))
def myCustomFunction(a,b):
    return Literal(a+b)

query = """
SELECT ?result WHERE {
    BIND(<http://example.org/myCustomFunction>(5,6) AS ?result)
}
"""

for row in g.query(query):
    print(f"Result: {row.result}")
```

# HackaLOD 2023 sparql query Utrecht Time Machine
```sparql
select distinct ?work ?workLabel ?depicts ?depictsLabel ?part ?partLabel ?coords ?heading ?image
where {
  {
    ?work wdt:P31/wdt:P279* wd:Q110304307 ;
          wdt:P180 wd:Q803 ;
          p:P180 ?statement .
    ?statement ps:P180 ?depicts.
    }

  optional { ?statement pq:P2677 ?part. }
  optional { ?work wdt:P18 ?image. }
  optional { ?work wdt:P1259 ?coords. }
  optional { ?work wdt:P7787 ?heading. }
  optional { ?work wdt:P170 ?creatorLabel. }
  optional { ?work wdt:P571 ?inceptionFull. }
  bind(year(?inceptionFull) as ?inception)
  filter (bound(?coords) && bound(?heading)) #alleen mét coords en heading
  
  service wikibase:label { bd:serviceParam wikibase:language "en" .}
}
```

# Parent Tree
```sparql
PREFIX schema: <https://schema.org/>
PREFIX id: <https://hetutrechtsarchief.nl/id/>
PREFIX rico: <https://www.ica.org/standards/RiC/ontology#>

SELECT ?node ?parent ?nodeLabel ?parentLabel { 
  values (?node) { (id:609C5B9947B74642E0534701000A17FD) }
  {
    select distinct ?node ?parent ?nodeLabel ?parentLabel where {
      ?node rico:isOrWasIncludedIn* ?parent .
	  ?parent rico:isOrWasIncludedIn/schema:name ?parentLabel .
      ?parent schema:name ?nodeLabel .
    }
  } 
}
```

# get properties as JSON data for a wikidataID
* https://www.wikidata.org/w/api.php?action=wbgetentities&ids=Q1&sitefilter=nlwiki&props=sitelinks/urls&origin=*&format=json

# grouping by rdf:type
```sparql
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
SELECT ?type ?label (COUNT(?type) AS ?count)
WHERE {
  ?subject a ?type .
  OPTIONAL { ?type rdfs:label ?label }
}
GROUP BY ?type ?label
ORDER BY DESC(?count)
```

# grouping by predicate
```sparql
SELECT ?predicate (COUNT(?predicate) AS ?count)
WHERE {
  ?subject ?predicate ?object .
}
GROUP BY ?predicate
ORDER BY DESC(?count)
```


# wikidata query using wikibase:around service to get items around a geopoint
```sparql
#defaultView:Map
SELECT DISTINCT ?img ?distance ?item ?itemLabel ?location WHERE {

   SERVICE wikibase:around { 
     ?item wdt:P625 ?location . 
     ?item wdt:P131 wd:Q803. # place is in Utrecht
     ?item wdt:P18 ?img . # must have image
     
     # That are in a circle with a centre of with a point
     bd:serviceParam wikibase:center "Point(5.104219854148524,52.10037982790537)"^^geo:wktLiteral   . 
     bd:serviceParam wikibase:radius ".2" .  # 200m
     bd:serviceParam wikibase:distance ?distance .
   } .
   SERVICE wikibase:label {   bd:serviceParam wikibase:language "en" . }
}
ORDER BY ?distance
```

# bind a single value to a variable
```sparql
BIND( wd:Q30 AS ?country )
```

# run multiple saved multipage sparql queries and write result as json per query
```python
#!/usr/bin/env python3
import requests, json, csv
api = "https://api.data.netwerkdigitaalerfgoed.nl/queries/hetutrechtsarchief/"

queries = ["wo2-documenten", "wo2-brontypes", "wo2-personen", "wo2-adressen-per-document", "wo2-adressen", "wo2-persoon-op-adres-per-document"]

for query in queries:
    rows = []
    for i in range(1,5): # max 5 pages... must be a better way
        url = api+query+"/run.json?pageSize=10000&page="+str(i)
        response = requests.get(url)
        data = response.json()
        rows = rows + data

    json.dump(rows, open(f"{query}.json","w"), indent=2)
```
    
## show map of all things that have a coordinate within 5km of Utrecht (Q803) using GEOSPARQL
```sparql
PREFIX geof: <http://www.opengis.net/def/geosparql/function/>
#defaultView:Map
SELECT ?place ?placeLabel (SAMPLE(?location) as ?localisation) WHERE {
  wd:Q803 wdt:P625 ?utrecht.
  ?place wdt:P17 wd:Q55 . #NL
  ?place wdt:P625 ?location.
  FILTER((geof:distance(?location, ?utrecht)) <5)
  SERVICE wikibase:label { bd:serviceParam wikibase:language "nl". }
}
group by ?place ?placeLabel
```
easier/faster way without GEOSPARQL is:
```sparql
#defaultView:Map
SELECT  ?item ?itemLabel (SAMPLE(?localisation) AS ?localisation) WHERE {
  ?item wdt:P131 wd:Q803.
  ?item wdt:P625 ?localisation.
  SERVICE wikibase:label { bd:serviceParam wikibase:language "nl". }
}
GROUP BY ?item ?itemLabel 
```

## VALUES
```sparql
...  
  values (?zoekterm) {
    ("Keijzerstraat") 
    ("Drift")
  }
```
 
## order by random
```sparql
...
BIND(SHA512(CONCAT(STR(RAND()), STR(?s))) AS ?random) . # order by random to get a varried sample set within the first 10.000 results
} ORDER BY ?random
```

## request saved query in jsonld form from triply with paging (limit/pageSize, offset/page)
zie ook: https://triply.cc/blog/2021-03-new-features

Supported RDF media types: application/n-triples, application/n-quads, application/trig, text/turtle, application/ld+json, application/json, application/x-triply+json. 

Supported RDF extensions: nt, nq, trig, ttl, jsonld, json, triply. Adding an extension to the end of the url overrides header-based content negotiation.

```python
import requests, json
url = "https://api.data.netwerkdigitaalerfgoed.nl/queries/hetutrechtsarchief/wo2-all-documents/run.json?pageSize=10000&page=2"

response = requests.get(url)
# response = requests.get(url, headers={"Accept":"application/ld+json"})

data = response.json()

print(json.dumps(data,indent=2))
```
https://api.data.netwerkdigitaalerfgoed.nl/queries/hetutrechtsarchief/wo2-all-documents/run.json?pageSize=5&page=2


## sparql construct test
```sparql
PREFIX wgs84: <http://www.w3.org/2003/01/geo/wgs84_pos#>
PREFIX sordef: <https://data.kkg.kadaster.nl/sor/model/def/>
PREFIX foaf: <http://xmlns.com/foaf/0.1/>
PREFIX def: <https://hetutrechtsarchief.nl/def/>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>

CONSTRUCT {
  ?doc rdf:type def:Document ;
     rdfs:label ?doc_label ;
     def:adres ?adres ;
     foaf:depiction ?depiction ;
     def:typeBron ?bron ;
     def:adres ?adres .
  ?adres rdf:type sordef:Nummeraanduiding ;
     rdfs:label ?adres_label ;
     wgs84:asWKT ?geo .
}
WHERE {
  ?doc a def:Document .
  OPTIONAL { ?doc rdfs:label ?doc_label }
  OPTIONAL { ?doc def:adresvermelding/sordef:Nummeraanduiding ?adres }
}
limit 10000 offset 1
```

## geof:sfWithin (filter results with a boundingbox)
data:
```turtle
@prefix geo: <http://www.opengis.net/ont/geosparql#> .
@prefix def: <https://hetutrechtsarchief.nl/def/> .
@prefix hua: <https://hetutrechtsarchief.nl/id/> .

hua:D8884A3B2E6CA8F6E0538F04000A374B
  a def:Adresvermelding ;
  geo:asWKT "POINT (9.1825624999999995 45.4652869999999965)"^^geo:wktLiteral .
```
query:
```sparql
PREFIX def: <https://hetutrechtsarchief.nl/def/>
prefix geo: <http://www.opengis.net/ont/geosparql#>
prefix geof: <http://www.opengis.net/def/function/geosparql/>

select ?zoekveld ?match ?x {
  
  ?x a def:Adresvermelding ; 
     geo:asWKT ?match.

  bind('Polygon((10 45, 10 46, 9 46, 9 45, 10 45))'^^geo:wktLiteral as ?zoekveld)

  filter(geof:sfWithin(?match, ?zoekveld))
}
limit 10
```
source: https://triplydb.com/academy/-/queries/geo-within-query/5


## everything that has as location a quadrangle instance EXCLUDE everything with a lunar coordinate
```sparql
SELECT distinct ?item ?itemLabel ?itemDescription ?typeLabel ?pic ?dt ?lq ?lqLabel WHERE {
  ?lq wdt:P31 wd:Q56551180 .  # instance of quadrangle on the moon
  ?item wdt:P276 ?lq  .       # anything that has as location a quadrangle instance 
  ?item wdt:P31 ?type .
  OPTIONAL { ?item wdt:P18 ?pic } .
  OPTIONAL { ?item wdt:P619 ?dt } .
  
  MINUS { SELECT ?item WHERE { ?item wdt:P8981 ?coord . } } # EXCLUDE everything with a lunar coordinate
  
  FILTER (?type NOT IN ( wd:Q13406463 ) )  # excude Wikipedia articles

  SERVICE wikibase:label { bd:serviceParam wikibase:language "en" . }
}
```

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
