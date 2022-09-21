* https://www.easyrdf.org/converter

```bash
# first install jsonld and raptor: 
#   npm install -g jsonld-cli
#   npm install -g raptor

jsonld normalize INPUT.json > result.nq

rapper -i nquads result.nq -o turtle result.nq > result.ttl
```
