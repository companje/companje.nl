---
title: Homebrew
---

# Error: Cannot install in Homebrew on ARM processor in Intel default prefix (/usr/local)!
```
Error: Cannot install in Homebrew on ARM processor in Intel default prefix (/usr/local)!
Please create a new installation in /opt/homebrew using one of the
"Alternative Installs" from:
  https://docs.brew.sh/Installation
You can migrate your previously installed formula list with:
  brew bundle dump
```
https://stackoverflow.com/questions/64963370/error-cannot-install-in-homebrew-on-arm-processor-in-intel-default-prefix-usr

solution: (?)
```
arch -x86_64 /usr/local/homebrew/bin/brew install python-tk
```

# multi-user setup
<https://medium.com/@leifhanack/homebrew-multi-user-setup-e10cb5849d59>

