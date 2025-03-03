# difftastic (side by side)
```bash
brew install difftastic
difft fileA fileB
```

# wdiff (per word)
```bash
brew install wdiff
wdiff fileA fileB | colordiff
```

# git diff (per word)
```bash
git diff --no-index --color-words fileA fileB
```

