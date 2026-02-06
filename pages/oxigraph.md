
# version 0.5
```bash
curl https://sh.rustup.rs -sSf | sh # install rust if needed
cargo install --git https://github.com/oxigraph/oxigraph.git --tag v0.5.4 oxigraph_server --force
oxigraph serve --location ./databases/rick
```
