---
title: Python
---

# from CSV to JSON without pandas
```python
#!/usr/bin/env python 

import csv
import json

data = []

with open("INPUT.csv") as f:
    reader = csv.DictReader(f, delimiter=";")

    for row in reader:
        data.append(row)

with open("OUTPUT.json", "w") as f:
    json.dump(data, f, indent=4)
```
    
# enumerate for loop
```python
for i, col in enumerate(header):
   print(i,col)
```

# regex
```python
import re
s = re.sub(r"[\"\n\r\\]", "", s) # strip double quotes "
s = re.sub(r"<[^>]*>", "", s); # strip pseudo html tags
```

# join
```python
print("; ".join(item) + " .")
```

# Python 3 as default on Mac
See: https://opensource.com/article/19/5/python-3-default-mac
```bash
alias python=/usr/local/bin/python3
alias pip=/usr/local/bin/pip3
```

# Guide to Python function decorators=
http://thecodeship.com/patterns/guide-to-python-function-decorators/

# 4 interessante stukjes Python
```python
def logUser(name, age, length):
    print('name: ', name)
    print('age: ', age)
    print('length: ', length)

#logUser('alex', 29, 1.75)
user = ('alex', 29, 1.75)
logUser(*user)
```

```python
def logUser(name, age, length):
    print('name: ', name)
    print('age: ', age)
    print('length: ', length)

# logUser(age=28, length=1.75, name='alex')
user = {'age':28, 'length':1.75, 'name':'alex'}
logUser(**user)
```

```python
def log(*args):
    print(args)
    message = ""
    for item in args:
        message += item+' ';
    print(message)

log('hallo', '2134', 'fsfdsf')
```

```python
def logUser(**kwargs):
    print('name: ', kwargs['name'])
    print('age: ', kwargs['age'])
    print('length: ', kwargs['length'])

logUser(age=28, length=1.75, name='alex')
```

# SimpleHTTPServer
  python -m SimpleHTTPServer 8000
  
# kivy
http://kivy.org/#home

# ImportError: No module named NK.gui.app
In NinjaKittens folder:
```bash
export PYTHONPATH=.
```

# gui
* http://kivy.org

# libxml / libxml2 / lxml 
untested:
```bash
brew install libxml2
```
or
```bash
sudo port install py25-lxml
```

# install easy_install
http://pypi.python.org/pypi/setuptools#downloads
```bash
sh setuptools-0.6c9-py2.4.egg --prefix=~
```

# Mogelijke oplossing voor problemen met Python op OSX Lion
* http://www.niconomicon.net/blog/2011/10/09/2120.wrestling.python.snow.leopard
* http://stackoverflow.com/questions/6886578/how-to-install-pycairo-1-10-on-mac-osx-with-default-python

# version
```bash
python --version
```

# location of python
```bash
type python
```
or
```bash
which python
```

# info about executable
```bash
file /usr/local/bin/python
```

# set python path (for macports?)
see also: [[macports]]
```bash
export PYTHONPATH=/opt/local/Library/Frameworks/Python.framework/Versions/2.7/lib/python2.7/site-packages
```
