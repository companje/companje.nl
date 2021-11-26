---
title: Python
---

## dictreader
```
f = open(f"data/file.csv", "r", encoding="utf-8-sig")
reader = csv.DictReader(f, delimiter=',')
data = [dict(d) for d in reader]
f.close()
```
## defaultdict
```python
my_dict = defaultdict(lambda: 0)
```

## tuple
```python
from ast import literal_eval as make_tuple
(coords, text, conf) = make_tuple("[([[22, 15], [373, 15], [373, 89], [22, 89]], 'Aaldering =', 0.46745234890467213)]")
```

## tuples!
https://www.studytonight.com/python/tuples-in-python

## unpack values from csv row into variables
```python
#!/usr/bin/env python3

import json,csv,sys,glob
from sys import argv

with open("TESTSERVER-aktenummers-bestandsnamen.csv") as f:
  reader = csv.DictReader(f)   #"CODE","ID","WAARDE","BESTANDSNAAM"
  for row in reader:
    (code, id, aktenummer, bestandsnaam) = row.values()
    print(code,id,aktenummer,bestandsnaam)
```

## join
```python
' '.join(filter(None,[item["fname"],item["prefix"],item["sname"]])) + # filter(None,..) is to get rid of double spaces
```

## dictreader with custom delimiter and fieldnames
```python
reader = csv.DictReader(f, delimiter=";", fieldnames=["fname","prefix","sname","bdate","publish","file_id","remark"])
```

## filter
```python
filteredList = filter(lambda item: item["publish"] == "ja", list(reader))
```

## sort with 'itemgetter'
```python
from operator import itemgetter
#...
sortedList = sorted(filteredList, key=itemgetter('sname')) 
```

## map
```python
lines = map(lambda item: item["name"], sortedList)
```

## utf-8 with BOM
```python
print(u'\ufeff',end='') # write UTF8 BOM signature without linebreak
```

## recursive findall using xPath .//
```python
for textline in xml.findall('.//TextLine'):  
  #...
```

## BoundingBox
found here: https://techoverflow.net/2017/02/23/computing-bounding-box-for-a-list-of-coordinates-in-python/
and fixed a bug.

```python
class BoundingBox(object):
    def __init__(self, points):
        self.minx, self.miny = float("inf"), float("inf")
        self.maxx, self.maxy = float("-inf"), float("-inf")
        for x, y in points:
            self.minx = min(x,self.minx)
            self.maxx = max(x,self.maxx)
            self.miny = min(y,self.miny)
            self.maxy = max(y,self.maxy)
    @property
    def width(self):
        return self.maxx - self.minx
    @property
    def height(self):
        return self.maxy - self.miny
    def __repr__(self):
        return "BoundingBox(minX={}, minY={}, maxX={}, maxY={})".format(
            self.minx, self.miny, self.maxx, self.maxy)
```


## DictWriter
```python
writer = csv.DictWriter(sys.stdout, fieldnames=["id","image", "etc..."], delimiter=',', quoting=csv.QUOTE_ALL, dialect='excel')
writer.writeheader()
#...
writer.writerow(item)
```

## split / map / list comprehension
```python
# input: 2737,1248 2787,1256 ...

# split by space and comma
coords = [coord.split(",") for coord in coords.split(" ")]
# output: [['2737', '1248'], ['2787', '1256'],...]

# cast to int
coords = [(int(float(a)), int(float(b))) for a,b in coords]
# output: [(2737, 1248), (2787, 1256), ...]
```

## PageXML to CSV
* https://github.com/hetutrechtsarchief/pagexml2csv

## read from multiple files supplied as arguments
for example `./script.py *.JSON`
```python
#!/usr/bin/env python3
import json,csv,sys,glob
from sys import argv

for filename in argv[1:]:
  with open(filename) as json_file:
    data = json.load(json_file)
    #...
```

## get text after last /
```python
str.rpartition("/")[-1]
```

## from CSV to JSON without pandas
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
    
## enumerate for loop
```python
for i, col in enumerate(header):
   print(i,col)
```

## regex
```python
import re
s = re.sub(r"[\"\n\r\\]", "", s) # strip double quotes "
s = re.sub(r"<[^>]*>", "", s); # strip pseudo html tags
```

## join
```python
print("; ".join(item) + " .")
```

## Python 3 as default on Mac
See: https://opensource.com/article/19/5/python-3-default-mac
```bash
alias python=/usr/local/bin/python3
alias pip=/usr/local/bin/pip3
```

## Guide to Python function decorators=
http://thecodeship.com/patterns/guide-to-python-function-decorators/

## 4 interessante stukjes Python
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

## SimpleHTTPServer
  python -m SimpleHTTPServer 8000
  
## kivy
http://kivy.org/#home

## ImportError: No module named NK.gui.app
In NinjaKittens folder:
```bash
export PYTHONPATH=.
```

## gui
* http://kivy.org

## libxml / libxml2 / lxml 
untested:
```bash
brew install libxml2
```
or
```bash
sudo port install py25-lxml
```

## install easy_install
http://pypi.python.org/pypi/setuptools#downloads
```bash
sh setuptools-0.6c9-py2.4.egg --prefix=~
```

## Mogelijke oplossing voor problemen met Python op OSX Lion
* http://www.niconomicon.net/blog/2011/10/09/2120.wrestling.python.snow.leopard
* http://stackoverflow.com/questions/6886578/how-to-install-pycairo-1-10-on-mac-osx-with-default-python

## version
```bash
python --version
```

## location of python
```bash
type python
```
or
```bash
which python
```

## info about executable
```bash
file /usr/local/bin/python
```

# set python path (for macports?)
see also: [[macports]]
```bash
export PYTHONPATH=/opt/local/Library/Frameworks/Python.framework/Versions/2.7/lib/python2.7/site-packages
```
