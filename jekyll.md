---
title: jekyll
---

# bundle install
```bash
bundle install
```

# Liquid Exception: no implicit conversion of Fixnum into String.
caused by 'escape' liquid tag in `_layouts/x.html`.

# get version
```bash
bundle exec jekyll -v
```

# serve locally
```bash
bundle exec jekyll serve --profile --incremental --verbose
```


# defaults
add this to _config.xml to give a the default page template
```yaml
defaults:
  -
    scope:
      path: "" # an empty string here means all files in the project
    values:
      layout: "default"
```