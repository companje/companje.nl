---
title: Python
---

## usage
```python
from sys import argv

if len(argv)!=3:
  sys.exit("Usage: "+os.path.basename(argv[0])+" {INPUT_CSV} {OUTPUT_FILE}")
input_filename = argv[1]
output_filename = argv[2]
```

## unieke waarden per kolom in csv
* zie gist: https://gist.github.com/companje/72ddf8f4ddba271580af2a55f62bcfad
 
## parallel processing
```python
from joblib import Parallel, delayed

def DoSomething(filename, param2, param2):
  #...

results = Parallel(n_jobs=8)(
  delayed(DoSomething)(filename, param2, param3)
  for filename in tqdm(filenames)
)
```

## get number of processor cores
```python
import psutil as psutil
print(psutil.cpu_count(logical=True))
```

## progress bar
```python
from tqdm import tqdm
#...
for filename in tqdm(filenames)
  #...
```

## request
```python
from urllib.request import urlopen
import urllib.parse
#...
request = urlopen(url)
data = json.load(request)
json.dump(data, open(cache_filename,"w"), indent=4)
```

## defaultdict with numbers
```python
aets = defaultdict(lambda: 0)
for ....
  aets[row["CODE"]] += 1
```

## serial
```python
#!/usr/bin/env python3

# on Sanyo: type file.asm > aux

import serial

ser = serial.Serial('/dev/tty.usbmodem1301',1200)

while True:
    x = ser.read()
    print(x.decode('ascii'), end="")

ser.close()
```

## parse and format date
```python
import datetime
try:
  isodate = datetime.datetime.strptime(datum, '%d-%m-%Y').strftime('%Y-%m-%d')
except ValueError:
  pass # just skip invalid/incomplete dates
```

## maak spreadsheet met flexvelden als kolommen per archiefeenheid
```python
#!/usr/bin/env python3
import csv,re
from collections import defaultdict

filename = "alle-personen.csv"
output_filename = "output.csv"
fixed = ["ID","GUID","CODE","BESTANDSNAAM"]
flex_key = "PROMPT"
flex_value = "WAARDE"

header = fixed.copy()
items = defaultdict(dict)

for row in csv.DictReader(open(filename)):

	row["WAARDE"] = row["WAARDE"].replace("\n"," ") # replace line breaks by spaces

	# create or get item
	item = items[row["ID"]]

	# add fixed fields
	for k,v in row.items():
		if k in fixed:
			item[k] = v

	# add flex fields
	item[row[flex_key]] = row[flex_value]
		
	# update header
	header.append(row[flex_key]) if row[flex_key] not in header else None

# output to csv
writer = csv.DictWriter(open(output_filename,"w"), fieldnames=header) #, delimiter=',', quoting=csv.QUOTE_ALL, dialect='excel')
writer.writeheader()
writer.writerows(items.values())
```

## replace broken words based on lookup table
```python
#!/usr/bin/env python3

import re,csv
from collections import defaultdict
import os.path

reader = csv.DictReader(open("Gekke tekens.csv"), delimiter=',')
lijst = [dict(d) for d in reader]

for line in open("vreemde_tekens2-gemaakt-via-grep-commando.txt").readlines():

    filepath = "../test.documentatie.org-met-lfs/test.documentatie.org/data/wp/"+line.split(':')[0].strip()
    basename = os.path.basename(filepath)

    if not os.path.isfile(filepath):
        print("NOT FOUND",filepath)
        continue

    with open(filepath) as infile:
        data = infile.read()
        
        for li in lijst:
            data = data.replace(li["fout"],li["gecorrigeerd"])        

        with open("tmp/"+basename,'w',encoding="utf-8") as outfile:
            outfile.write(data)
```
	
## find broken characters with context
```python
#!/usr/bin/env python3

import re,csv
from collections import defaultdict
from operator import itemgetter

results = defaultdict(list)

for line in open("vreemde_tekens2-gemaakt-via-grep-commando.txt").readlines():

    filepath = line.split(':')[0].strip()
    data = "".join(line.split(':')[1:])

    matches = re.findall(r'([a-zA-Z�]+)(�)([a-zA-Z�]+)',data)

    for m in matches:
        s = "".join(m)
        results[s].append("http://test.documentatie.org/data/wp/"+filepath)

writer = csv.writer(open("result.csv","w"))

for k,v in results.items():
    k2 = re.sub(r"�","",k)
    writer.writerow([k,k2,v[0],len(v)])
```

## find hexstring in files recursively
```python
#!/usr/bin/env python3

import glob, re

files = glob.glob('../test.documentatie.org-met-lfs/test.documentatie.org/data/wp/**/*.htm*', recursive=True)

for filename in files:
    
    with open(filename, "rb") as f:

        f1 = re.search(b'\xEF\xBF\xBD', f.read())

        if f1:
            print(filename,"\t",f1)
```

## combine 2 cvs (database tables) to 1 json file with hierarchy
```python
#!/usr/bin/env python3

import csv, json

uitvoergegevens = { row["ID"]:row for row in csv.DictReader(open("uitvoergegevens.csv", encoding="cp1252")) }
uitvoervelden = { row["ID"]:row for row in csv.DictReader(open("uitvoervelden.csv", encoding="cp1252")) }

for row in uitvoervelden.values():

	ugn = uitvoergegevens[row["UGN_ID"]]

	if "uitvoervelden" not in ugn:
		ugn["uitvoervelden"] = []
	else:
		ugn["uitvoervelden"].append(row)

json.dump(uitvoergegevens, open("result.json", "w"), indent=2)
```


## read CSV file as dictionary with primary key
```python
uitvoergegevens = { row["ID"]:row for row in csv.DictReader(open("uitvoergegevens.csv", encoding="cp1252")) }
uitvoervelden = { row["ID"]:row for row in csv.DictReader(open("uitvoervelden.csv", encoding="cp1252")) }
```

## csv2xlsx - csv to excel
```python
#!/usr/bin/env python3
# source: https://stackoverflow.com/questions/17684610/python-convert-csv-to-xlsx


import os
import glob
import csv
from xlsxwriter.workbook import Workbook

for csvfile in glob.glob(os.path.join('.', '*.csv')):
    workbook = Workbook(csvfile[:-4] + '.xlsx')
    worksheet = workbook.add_worksheet()
    with open(csvfile, 'rt', encoding='utf8') as f:
        reader = csv.reader(f)
        for r, row in enumerate(reader):
            for c, col in enumerate(row):
                worksheet.write(r, c, col)
    workbook.close()
```
    
## python csv 'list' reader
```python
file = open("file.csv")
reader = csv.reader(file)
```

## inline print for each result
```python
[ print(result) for result in results ]
```

## get key and value for the items in a dict
```python
for k,v in scores.items():
  print(k,v)
```

## get the object with the max value in a dict
```python
model = max(scores, key = scores.get) 
# equivalent to: 
model = max(scores, key = lambda k : scores.get(k))  
```

## get filename from path
```python
file_name = os.path.basename(file_path)
```

## recursive filelist by type
```python
file_paths = glob.glob(folder + '/**/*.jpg', recursive=True)
```

## semi transparent rectangle opencv python
```python
def rechthoek(img, leftTop, rightBottom, color=(255,255,0), opacity=.2):
	(x1, y1) = leftTop
	(x2, y2) = rightBottom
	sub_img = img[y1:y2, x1:x2]
	white_rect = np.ones(sub_img.shape, dtype=np.uint8)
	white_rect[:,:] = color
	res = cv2.addWeighted(sub_img, 1-opacity, white_rect, opacity, 1.0)
	img[y1:y2, x1:x2] = res
```

## problem with scipy dependency when installing easyocr through pip
it helped to install scipy using brew instead of pip
```
brew install scipy
pip install easyocr
```


## which python?
```
ls -l /usr/local/bin/python3
ls -l /usr/local/bin/python
```

## create image tiles / sprite sheet with python, opencv and numpy
```python
import cv2
import numpy as np

margin=5
cols=5
rows=10

stamp = cv2.imread("50p.jpeg")
stamp_h,stamp_w = stamp.shape[:2]

height=stamp_h*rows + (margin*rows+1)
width=stamp_w*cols + (margin*cols+1)

img = np.ones((height,width,3), np.uint8) * 255 # white

for row in range(0,rows):
  for col in range(0,cols):
    print(row,col)

    x=col*stamp_w + (col+1)*margin
    y=row*stamp_h + (row+1)*margin

    img[y:y+stamp_h,x:x+stamp_w] = stamp

cv2.imwrite("sheet.jpg", img)


cv2.namedWindow("test",cv2.WINDOW_NORMAL)
cv2.setWindowProperty("test",cv2.WND_PROP_FULLSCREEN,cv2.WINDOW_FULLSCREEN)
cv2.setWindowProperty("test",cv2.WND_PROP_FULLSCREEN,cv2.WINDOW_NORMAL)
cv2.imshow("test", img)
cv2.waitKey()
```

## create an empty image
```python
import cv
import numpy as np

height=1000
width=1000

img = np.zeros((height,width,3), np.uint8)

img[:,0:width//2] = (255,0,0)      # (B, G, R)
img[:,width//2:width] = (0,255,0)

cv2.imshow("test", img)
cv2.waitKey()
```

## info about current python and system settings
```python
import sys
print(sys.version)
print(sys.executable)
print(sys.path)
```

## enumerate
```python
for img_index, img_file_name in enumerate(img_file_names):
```

## dictreader
```python
f = open(f"data/file.csv", "r", encoding="utf-8-sig")
reader = csv.DictReader(f, delimiter=',')
data = [dict(d) for d in reader]
f.close()
```

## defaultdict
```python
from collections import defaultdict
my_dict = defaultdict(lambda: 0)   # maakt een dict met default waarde 0 aan voor items
other_dict = defaultdict(list)     # maakt een dict met default waarde een list []
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
