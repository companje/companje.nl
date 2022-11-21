---
title: Python
---

## pandas groupby to tabs/sheets in Excel
```
df = pd.read_csv("overzicht.csv") 

writer = pd.ExcelWriter('output.xlsx', engine='xlsxwriter')

for state, frame in df.groupby("AET"):
	frame.to_excel(writer, sheet_name=state)

writer.save()
```

## xlsxwriter with pandas
* https://xlsxwriter.readthedocs.io/working_with_pandas.html

## format with 'repr', 'str' or 'ascii'
```python
print(f"{state!r}")
print(f"{state!s}")
print(f"{state!a}")
```

## pandas groupby (count)
```python
df = pd.read_csv("overzicht.csv") # sep=',', engine='python', header=0, usecols=["tip", "sex", "time"]), index_col=["sex", "tip"], nrows=5, skiprows = [1,12], 
group_by_aet = df.groupby("AET")["CODE"].count()
print(group_by_aet)
```

## trim items when splitting
```python
input_string='ARP; CHU; KVP'
items = [x.strip() for x in input_string.split(';')]
# ['ARP', 'CHU', 'KVP'] instead of ['ARP', ' CHU', ' KVP']
```

## oracle sql through jdbc
```bash
pip install cx_oracle
```
* and download the [instant client](https://www.oracle.com/database/technologies/instant-client/macos-intel-x86-downloads.html)

```python
import cx_Oracle
lib_dir = os.path.join(os.environ.get("HOME"), "Downloads", "instantclient_19_8")
cx_Oracle.init_oracle_client(lib_dir=lib_dir)
dsn = cx_Oracle.makedsn(IP_OR_HOSTNAME,'1521',service_name=SERVICE_NAME)
connection = cx_Oracle.connect(os.environ["USER"], os.environ["PASS"], dsn)
cur = connection.cursor()
for row in cur.execute("select * from TABLE;"):
  print(row)
```

## list all valid xnummers recursively
```python
def listdir_valid_xnummers_to_csv():
	folderroot = "/Volumes/SHARE$/FOLDER"
	xnummers = []

	for path in Path(folderroot).rglob('*.jpg'):
		r = re.findall(r"(?:X)(\d+)",path.name)
		if r:
			path_without_root = str(path).replace(folderroot,'') # let op! str(path) !
			xnummers.append({"nummer":int(r[0]), "path":path_without_root})

	xnummers = sorted(xnummers, key=lambda item:item['nummer'])

	writer = csv.DictWriter(open("all-valid-xnummers.csv","w"), fieldnames=["nummer","path"])
	writer.writeheader()
	writer.writerows(xnummers)
```

## pathlib / os.path
```python
import os
from pathlib import Path

for path in Path(folderroot).rglob('*.jpg'):
	folder = os.path.basename(os.path.dirname(path))
	print(folder, path.name)
```

## try except
```python
try:  
    1 / 0 
except Exception as e: 
    print(e)
```

with traceback:
```python
import traceback
traceback.print_exc()
```

## named tuples
```python
from collections import namedtuple
Point = namedtuple('Point', ['x', 'y'])
#Rect = namedtuple('Rect', ['x', 'y', 'w', 'h'])
a = Point(1,2)
print(a.x)
```

## list to dict
```python
dict(zip(['x','y','w','h'], get_bounds(box_points)))
```

## cast array to namedTuple
"You can do Row(*A) which using argument unpacking." [source](https://stackoverflow.com/questions/15324547/convert-list-to-namedtuple)
```python
from collections import namedtuple
input_array = [1705, 155, 106, 38]
Rect = namedtuple('Rect', ['x', 'y', 'w', 'h'])
bounds = Rect(*input_array)
print(bounds)
# output: Rect(x=1705, y=155, w=106, h=38)
```


## ensure ascii=False
```python
print(json.dumps(data,indent=2,ensure_ascii=False))
```

## liquid templates
```python
from liquid import Liquid
tpl = Liquid("templates/template.xml", liquid_from_file=True) 
data = {
  "ID": item["index"],
  "ParentID": item["parentIndex"],
  "Text": item["text"]
}
result = tpl.render(**data)
print(result)
```

## parse macOCR result
```python
def read_ocr(filename): # txt macOCR format
    for raw_line in open(filename).readlines():
        line_tuple = make_tuple(raw_line)
        yield({
            "box": [tuple([int(coordinate) for coordinate in coordinates]) for coordinates in line_tuple[0]],
            "text": line_tuple[1],
            "confidence": line_tuple[2]
        })

ocr_items = list(read_ocr(INPUT_MACOCR))
```

## ignore 'illegal' unicode chars in string (?)
```python
str(data["Text"].encode("ascii", "ignore"))
```

## sort by integer without itemgetter but wit lambda function
```python
relaties = sorted(relaties, key=lambda item:int(item['aantal']), reverse=True)
```

## strip trailing numbers comma and whitespace
```python
s = s.strip().lower().rstrip(" 1234567890,")
```

## download and save image
```python
urllib.request.urlretrieve(URL, local_filename)
```

## markup html with BeautifulSoup
https://beautiful-soup-4.readthedocs.io/en/latest/
```python
from bs4 import BeautifulSoup
soup = BeautifulSoup(html, 'html.parser')
print(soup.prettify())
```

get all links:
```python
for link in soup.find_all('a'):
    print(link.get('href'))
```

get text of div with class:
```python
copyright = soup.find("div", class_="copyright-authoryear").get_text().strip()
```

## which python uses sublime?
```python
#!/usr/bin/env python3

import sys
print(sys.executable)
sys.exit()
```
in my case:
```
/usr/local/opt/python@3.10/bin/python3.10
```

## use pip for specific python version
```bash
/usr/local/opt/python@3.10/bin/python3.10 -m pip install bs4
```

## requests
```python
import requests, json

response = requests.get(URL)
# response = requests.get(URL, headers={"Accept":"application/ld+json"})

data = response.json()
print(json.dumps(data,indent=2))
```

## on ubuntu when pip python packages end up in ~/.local/lib/python3.6/site-packages/
change user to root before pip install
```
sudo su
```

## list used packages in Python script with versions
```python
import pkg_resources
for i in pkg_resources.working_set:
  print(i.key + "==" + i.version)
```

## list all files recursive
```python
#!/usr/bin/env python3

from pathlib import Path

folder = "/Volumes/archiefbestanden$/Kadaster/DVD's/"

for path in Path(folder).rglob('*'):
    print(path)
```

## join list to string ignore blanks
```python
' '.join(filter(None, strings))
```

## set label for tqdm progress bar
```python
pbar = tqdm(straten) # list

for i,straat in enumerate(pbar):
  straatnaam = straat["straatnaam"]
  pbar.set_description(f"{i} {straatnaam}")
```

## geo coordinaat opvragen van (het middelpunt van) een straat via BAG openbare_ruimte_ID op wikidata.
```python
query="""SELECT ?straat ?straatLabel ?punt ?woonplaats ?woonplaatsLabel WHERE {
?straat wdt:P625 ?punt .
?straat wdt:P131 ?woonplaats . 
?straat wdtn:P5207  <http://bag.basisregistraties.overheid.nl/bag/id/openbare-ruimte/"""+straat_id+"""> .
SERVICE wikibase:label { bd:serviceParam wikibase:language "nl". } }"""

url = "https://query.wikidata.org/sparql"
response = requests.get(url, params={'query' : query}, headers={'Accept' : 'application/sparql-results+json'})
data = response.json()
```

## read csv as dict etc
```python
def csv2dict(filename):
	return csv.DictReader(open(csv_folder+filename, encoding="utf-8-sig"), delimiter=";")

def csv2lut(filename):
	return { row["Unieke waarde (niet aanpassen)"]:row["Correctie (hier corrigeren)"] 
		for row in csv2dict(filename) }
```

## split geo point
```python
def split_geo_point(s):
	parts = s.lower().split(" ")
	lat = parts[1].replace(")","")
	lon = parts[0].replace("point(","")
	return (lat,lon)
```

## urlencode
```python
print(urllib.parse.quote_plus(query))
```

## write stderr to file
```python
sys.stderr = open("stderr.log","w")
```

## total seconds from time
```python
# must be a better way...
dt = datetime.strptime(film["STARTTIJD"][:8],"%H:%M:%S").time()
startOffset = dt.hour * 3600 + dt.minute * 60 + dt.second
```
## namedtuple
```python
Point = namedtuple('Point', 'x y')
center = Point(10,10) # center is a namedtuple, that can be accessed either using x and y or an index (0,1)
print(center.x)

```

## parse points and get bounds
```python
def get_points(points_string):
    return [tuple(map(int,coords.split(','))) for coords in points_string.split(" ")] 

def get_bounds(points):
    x = min(points)[0], max(points)[0]
    y = min(points)[1], max(points)[1]
    return ((x[0],y[0],x[1],y[1]))

def is_overlapping_1D(line1, line2): # line: (xmin, xmax)
    return line1[0] <= line2[1] and line2[0] <= line1[1] # box (xmin, ymin, xmax, ymax)

def is_overlapping_2d(box1, box2):
    return is_overlapping_1D([box1[0],box1[2]],[box2[0],box2[2]]) and is_overlapping_1D([box1[1],box1[3]],[box2[1],box2[3]])

points_string = "93,1349 93,1502 162,1502 162,1349"
print(get_points(points_string))
```

## get duration of films in folder
```python
#!/usr/bin/env python3
import json,csv,re,glob,os,tqdm
import datetime
from time import strftime
from time import gmtime
from os.path import exists
from pathlib import Path
from tqdm import tqdm

def get_duration_ffprobe(filename):
	import subprocess, json
	result = subprocess.check_output(
		f'ffprobe -v quiet -show_streams -select_streams v:0 -of json "{filename}"',
		shell=True).decode()
	fields = json.loads(result)['streams'][0]
	return fields['duration']

# GET DURATION OF ALL FILMS
movie_folder = "MOVIE_FOLDER/"

files = glob.glob(movie_folder+"*.mp4", recursive=False)

writer = csv.DictWriter(open("film-duration.csv","w"), fieldnames=["name","duration"]) #, delimiter=',', quoting=csv.QUOTE_ALL, dialect='excel')
writer.writeheader()

for filename in tqdm(files):
	row = {}
	row["name"] = os.path.basename(filename)
	row["duration"] = strftime("%H:%M:%S:00", gmtime(float(get_duration_ffprobe(filename))))
	writer.writerow(row)
```

## difflib
https://docs.python.org/3/library/difflib.html (tip van Lars)
## fuzzy matching in strings
https://gist.github.com/companje/93f6061629ac27a2027a77888effd6ad

## yield
The yield statement suspends function’s execution and sends a value back to the caller, but retains enough state to enable function to resume where it is left off. When resumed, the function continues execution immediately after the last yield run. This allows its code to produce a series of values over time, rather than computing them at once and sending them back like a list. https://www.geeksforgeeks.org/use-yield-keyword-instead-return-keyword-python/

```python
def x():
    for i in [5,4,2]:
        yield(i)

for i in x():
    print(i)
```

## uuid / guid
```python
import uuid
print(uuid.uuid4().hex)
```

## string padding
```python
mystring.rjust(10, '0')) 
```

## any
```python
contains_thans = any(thans_spelling in raw_country_str for thans_spelling in ["thans", "th.", "th "])
```

## usage
```python
import sys,os
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

## maak spreadsheet met velden als kolommen per eenheid
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
