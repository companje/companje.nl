# difference between identifier and hasIdentifier
```turtle
rico:identifier "1001" ;                                 # bij rico:identifier worden alleen literals verwacht
rico:hasIdentifier id:609C5B99657D4642E0534701000A17FD   # bij rico:hasIdentifier gebruik je altijd een URI
```

# run shacl validator
```bash
shacl validate -s ../recordset.ttl -d example-RiC-O-maart2022.ttl
```
