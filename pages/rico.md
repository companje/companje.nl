# difference between identifier and hasIdentifier
```turtle
rico:identifier "1001" ;                                 # bij rico:identifier worden alleen literals verwacht
rico:hasIdentifier id:609C5B99657D4642E0534701000A17FD   # bij rico:hasIdentifier gebruik je altijd een URI
```

# run shacl validator
part of jena (install jena on mac with `brew install yena`)
```bash
shacl validate -s ../recordset.ttl -d test1001.ttl
```

# validation report
```bash
shacl validate -s ../recordset.ttl -d recordset/saa5075.ttl
```
```turtle
@prefix rdf:  <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .
@prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#> .
@prefix sh:   <http://www.w3.org/ns/shacl#> .
@prefix xsd:  <http://www.w3.org/2001/XMLSchema#> .

[ rdf:type     sh:ValidationReport ;
  sh:conforms  true
] .
```

# Xone has 0 conforming shapes at focusNode
?
