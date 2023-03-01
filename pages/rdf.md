# Convert multiple JSONLD files (from Omeka) to one Turtle file
```python
from pathlib import Path
from rdflib import Graph, Literal, Namespace

input_files = list(Path("data/omeka").rglob("*.json"))

g = Graph()
g.bind("o", Namespace("http://omeka.org/s/vocabs/o#"))
g.bind("dcterms", Namespace("http://purl.org/dc/terms/"))
g.bind("dctype", Namespace("http://purl.org/dc/dcmitype/"))
g.bind("bibo", Namespace("http://purl.org/ontology/bibo/"))
g.bind("foaf", Namespace("http://xmlns.com/foaf/0.1/"))
g.bind("schema", Namespace("http://schema.org"))
g.bind("edm", Namespace("http://www.europeana.eu/schemas/edm/"))
g.bind("RiCo", Namespace("https://www.ica.org/standards/RiC/ontology"))
g.bind("o-cnt", Namespace("http://www.w3.org/2011/content#"))
g.bind("o-time", Namespace("http://www.w3.org/2006/time#"))
g.bind("o-module-mapping", Namespace("http://omeka.org/s/vocabs/module/mapping#"))

for input_file_path in input_files:
	print(input_file_path)
	g.parse(input_file_path)

ttl = g.serialize(format="ttl")

#somehow the prefix for schema gets messed up. fix it here
ttl = ttl.replace("<schema:>", "<http://schema.org/>")

print(ttl,file=open("result.ttl","w"))
```

# EasyRDF
* https://www.easyrdf.org/converter

# Validate Turtle file
```bash
npm install -g turtle-validator
ttl FILE.ttl
```

# tools
* https://prefix.cc
* https://prefix.zazuko.com/

# jsonld
```bash
npm install -g jsonld-cli
jsonld normalize INPUT.json > result.nq
```

# Raptor
```bash
npm install -g raptor
rapper -i rdfxml 1001.rdf -o turtle > 1001.ttl
```
