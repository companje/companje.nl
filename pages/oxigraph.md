# select from multiple named graphs
by default only the default graph is queried. Use FROM to query from multiple graphs at the same time. see also [sparql](/sparql)
```
SELECT ?s ?p ?o
  FROM <https://kvan-todb.hualab.nl/graph/folder_summary>
  FROM <https://kvan-todb.hualab.nl/graph/macocr>
  FROM <https://kvan-todb.hualab.nl/graph/records>
  FROM <https://kvan-todb.hualab.nl/graph/visuele_beschrijving>
WHERE {
  ?s ?p ?o
}
```

# version 0.5 (instead of 0.3)
```bash
curl https://sh.rustup.rs -sSf | sh # install rust if needed
cargo install --git https://github.com/oxigraph/oxigraph.git --tag v0.5.4 oxigraph_server --force
oxigraph serve --location ./databases/rick
```
