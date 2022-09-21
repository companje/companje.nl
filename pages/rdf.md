* https://www.easyrdf.org/converter

# jsonld
```bash
npm install -g jsonld-cli
jsonld normalize INPUT.json > result.nq
```

# Raptor
```bash
npm install -g raptor
rapper -i nquads result.nq -o turtle result.nq > result.ttl
```
