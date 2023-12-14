---
title: Python
---

# read PageXML without metadata using regex
```python
def get_pagexml_without_metadata(xml_file):
    return re.sub("<Metadata>.*?</Metadata>", "", open(xml_file).read(), flags=re.DOTALL)
```

# tip van Stan
[NLTK - Natural Language Toolkit](https://www.nltk.org/)

# SMF SimpleMachines Forum Export / Backup to JSON
```python
import mysql.connector, json

def get_column_names(db_name, table_name):
    query = f"SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '{db_name}' AND TABLE_NAME = '{table_name}'"
    cursor.execute(query)
    return [row["COLUMN_NAME"] for row in cursor]

db_name = 'your_db'
db_user = 'root'
db_pass = ''
conn = mysql.connector.connect(user=db_user, password=db_pass, host='127.0.0.1', database=db_name)
cursor = conn.cursor(dictionary=True)

board_columns = get_column_names(db_name,'smf_boards')
topic_columns = get_column_names(db_name,'smf_topics')
message_columns = get_column_names(db_name,'smf_messages')

cursor.execute("""
SELECT * FROM smf_messages m
JOIN smf_topics t ON t.id_topic = m.id_topic
JOIN smf_boards b ON b.id_board = t.id_board
ORDER BY b.id_board, t.id_topic, m.id_msg
""")

structure = {}

for row in cursor:
    board_id = row['id_board']
    topic_id = row['id_topic']

    # make board data
    board_data = {col: row[col] for col in board_columns}

    # make topic data
    topic_data = {col: row[col] for col in topic_columns}

    # add board toe if not exits
    if board_id not in structure:
        structure[board_id] = {'board_data': board_data, 'topics': {}}

    # add topic if not exists
    if topic_id not in structure[board_id]['topics']:
        structure[board_id]['topics'][topic_id] = {'topic_data': topic_data, 'messages': []}

    # make message data and add tot topics
    message_data = {col: row[col] for col in message_columns}
    structure[board_id]['topics'][topic_id]['messages'].append(message_data)

cursor.close()
conn.close()

json.dump(structure, open("result.json","w"), indent=2, ensure_ascii=False)
```

# lookup hierarchy from id's in CSV file and output new CSV
```python
#!/usr/bin/env python3
import csv

items = list(csv.DictReader(open("beschrijvingen-met-parent-guids.csv")))
items_by_guid = {}

#build lut
for row in items:
    row["parents"] = row["parents"].split(" ")
    items_by_guid[row["GUID"]] = row

#solve refs
for item in items_by_guid.values():
    item["parents"] = [ items_by_guid[p_guid] for p_guid in item["parents"] if not item==items_by_guid[p_guid]]

# als er een inleiding gevonden wordt direct onder een rubriek
# dan deze inleiding toevoegen aan de rubriek omschrijving
for item in items:
    if item["aet"]=="inl" and item["parents"][-1]["aet"]=="rub":
        item["parents"][-1]["omschr"] += f"\n{item['omschr']}"

# clean up
for item in items:
    item["omschr"] = item["omschr"].replace(">tr.",">getrouwd met: ")
    item["omschr"] = item["omschr"].replace("<BR>","\n")
    
header = ["guid","text"]
writer = csv.DictWriter(open("items-with-context.csv","w",encoding="utf-8"), fieldnames=header, delimiter=';', quoting=csv.QUOTE_ALL, dialect='excel')
writer.writeheader()

for item in items:
    writer.writerow({
        "guid": item["GUID"], 
        "text": "\n".join([p["omschr"] for p in item["parents"]] + [item["omschr"]])
    })
```

# Count entities (in many json-files) grouped by entity_type
```python
#!/usr/bin/env python3
import json
from pathlib import Path
from collections import defaultdict

entities_by_type = { }

for filename in list(Path(f"data/").rglob("*.json")):
    try:
       data = json.load(open(filename))
       for item in data["gpt-result"]:
            for value in data["gpt-result"][item]:
                value = str(value).strip()

                if not item in entities_by_type:
                    entities_by_type[item] = defaultdict(int)

                entities_by_type[item][value] += 1

    except Exception as e:
       print(e,filename)

# sort by count within entity_type
for t in entities_by_type:
   entities_by_type[t] = dict(sorted(entities_by_type[t].items(), key=lambda x:x[1], reverse=True))

json.dump(entities_by_type, open("entities.json","w"),indent=2,ensure_ascii=False)
```

# Call GPT-4 API for Namd Entity Recognition (NER)
```python
#!/usr/bin/env python3

import json,csv,os,openai,sys

openai.api_key = os.getenv("OPENAI_API_KEY")

for row in list(csv.DictReader(open("beschrijvingen-platte-lijst.csv")))[0:1000]:
    filename = "data/" + row["GUID"] + ".json"
    if os.path.exists(filename):
        continue

    response = openai.ChatCompletion.create(
        # model="gpt-3.5-turbo",
        model="gpt-4-1106-preview",
        response_format={ "type": "json_object" },
        messages=[ {"role": "system", "content": "You can recognize named-entities and return these in JSON as strings grouped by entity-type: { 'persons':[], 'organisations':[], 'locations':[], 'topics':[], 'events':[], 'dates':[]"},
                   {"role": "user", "content": row["context"] } ]
    )

    try:
        if response:
            response["text"] = json.loads(response["choices"][0]["message"]["content"])
    except Exception as e:
        print(e,response)
        pass

    try:
        json.dump({"item":row, "gpt-result":response["text"]},open(filename,"w"), indent=2, ensure_ascii=False)
        print(filename, response["text"])
    except Exception as e:
        print(e,row["GUID"],response)

    print(filename)
```
# escape URI part
```python
def escape_URI_part(s):
  # spreadsheet: [–’&|,\.() ""$/':;]"; "-") ;"-+";"-"); "[.-]$"; ""))
  s = re.sub(r"[_–’+?&=|,\.() \"$/']", "-", s) # replace different characters by a dash
  s = re.sub(r"-+", "-", s) # replace multiple dashes by 1 dash
  s = re.sub(r"[^a-zA-Z0-9\-]", "", s) # strip anything else now that is not a alpha or numeric character or a dash
  s = re.sub(r"^-|-$", "", s) # prevent starting or ending with . or -
  if len(s)==0:
    # raise ValueError("makeSafeURIPart results in empty string")
    # log.warning("makeSafeURIPart results in empty string")
    # fix this by replacing by 'x' for example
    import random
    s="unknown"+str(int(random.randint(10000, 99999)))
  return s.lower()
```

# pretty print
```python
from pprint import pprint 
print(data)
```

# get all <link rel='next'> pages
```python
from bs4 import BeautifulSoup
import requests

url = "https://www.website.org/topics/"

while url:
    print(url)
    response = requests.get(url)
    soup = BeautifulSoup(response.text, 'html.parser')
    url = soup.find("link", rel="next")
    if url:
        url = url.get("href")
```

# get item by id+value in json array
find item with code=="FT"
```python
item = next((item for item in data if item.get("code") == "FT"), None)
```
as function:
```python
def get_item(items, key, value):
    return next((item for item in items if item.get(key) == value), None)
```

find multiple values:
```python
def get_items(items, key, values):
    return [ next((item for item in items if item.get(key) == value), None) for value in values]
```

# merge list with z_values with 2D nparray
```python
z_values = [img[int(y), int(x), 2] for x, y in points]
points = np.column_stack((points[:, 0], points[:, 1], z_values))
```
shorter:
```python
points = np.column_stack((points, img[points[:, 1].astype(int), points[:, 0].astype(int), 2]))
```

# pretty print
```python
from pprint import pprint
pprint(data)
```

# merge files from 2 folders to an output folder
```python
#!/usr/bin/env python3

from pathlib import Path
import os,shutil

alto_folder = "XX/input/"
alto_files_by_filename = {os.path.basename(path): path for path in Path(alto_folder).rglob("*.xml")}
image_filepaths = list(Path(f"1001/").rglob("*.jpg"))

for image_path in image_filepaths :
    # recreate folder structure from images
    image_folder = os.path.dirname(image_path)
    image_filename = os.path.basename(image_path)
    dst_folder =  Path("output/" + image_folder)
    dst_folder.mkdir(parents=True, exist_ok=True) 
    dst_image_path = os.path.join(dst_folder, image_filename)

    # copy images
    if not os.path.exists(dst_image_path):
        print("copy",image_path,"to",dst_image_path)
        shutil.copy2(image_path, dst_folder)

    # copy and rename alto files
    alto_filename = image_filename.replace(".jpg",".xml")
    alto_filepath = alto_files_by_filename.get(alto_filename)
    if alto_filepath: # skip non-existing alto files
        new_alto_filename = alto_filename.replace(".xml","_alto.xml")
        dst_alto_filepath = os.path.join(dst_folder, new_alto_filename)
        shutil.copy2(alto_filepath, dst_alto_filepath)
```

# store filepaths (recursively) in a dict by filename
```python
alto_files_by_filename = {os.path.basename(path): path for path in Path(alto_folder).rglob("*.xml")}
```

# maak lookup table
```python
lut_technieken = { item["INHOUD"]:item["ID"] for item in csv.DictReader(open("con_technieken.csv")) }
```

# image areas in html to json
```python
from bs4 import BeautifulSoup
import json

with open("index.html", "r", encoding="utf-8") as file:
    html = file.read()

soup = BeautifulSoup(html, 'html.parser')
area_tags = soup.find_all('area')

json_output = []

for area in area_tags:
    area_dict = {}
    area_dict['TITLE'] = area.get('title')
    area_dict['ID'] = area.get('id')
    area_dict['REL'] = area.get('rel')
    area_dict['HREF'] = area.get('href')
    area_dict['DATA_X_ABS'] = area.get('data-x-abs')
    area_dict['DATA_Y_ABS'] = area.get('data-y-abs')
    area_dict['TARGET'] = area.get('target')
    area_dict['CLASS'] = area.get('class')
    area_dict['SHAPE'] = area.get('shape')
    
    coords = area.get('coords')
    if coords:
        coords_list = list(map(int, coords.split(',')))
        area_dict['COORDS'] = coords_list

    json_output.append(area_dict)

json_str = json.dumps(json_output, indent=4)
print(json_str)
```

# build exe file
```bash
pip install pyinstaller
pyinstaller --add-data 'data;data' .\YourScript.py   # with data folder
```

# random integer
```python
random.randint(1, 10)
```

# draw centered fullscreen chessboard
```python
import cv2
import numpy as np

image_width = 3840
image_height = 2400

img = np.zeros((image_height, image_width, 3), dtype=np.uint8)

square_size = image_height // 8
for y in range(8):
    for x in range(8):
         if (x + y) % 2 == 0:              
            x1 = (image_width//2-image_height//2) + x*square_size
            y1 = y*square_size
            x2 = x1+square_size
            y2 = y1+square_size
            img[y1:y2, x1:x2] = (255,255,255)

cv2.namedWindow('img', cv2.WINDOW_NORMAL)
cv2.setWindowProperty('img', cv2.WND_PROP_FULLSCREEN, cv2.WINDOW_FULLSCREEN)
cv2.imshow('img', img)
cv2.waitKey(0)
cv2.destroyAllWindows()
```

# opencv - mask, crop, channels, stretch, colorize with lut
```python
import cv2
import numpy as np

palette_image = cv2.imread('palette256.png')
palette_lut = palette_image[0,:,:]
frame = cv2.imread("frame.png")
height,width = frame.shape[:2]
(cx,cy),r = (321, 279),226 # for frame.png
names="red","green","blue",
g=[202,129,185] # range start per channel
a,b,c = [None]*3, [None]*3, [None]*3 # maak 3 lege lists met ruimte voor 3 items

def stretch(img,minv,maxv):
    alpha = 255/(maxv-minv)
    return cv2.convertScaleAbs(img, alpha=alpha, beta = -minv*alpha)  

while(True):
    mask = np.zeros((height, width), dtype=np.uint8)
    cv2.circle(mask, (cx,cy), r, 255, -1)
    masked = cv2.bitwise_and(frame, frame, mask=mask)
    cropped = masked[cy-r:cy+r, cx-r:cx+r]
    h,w,_ = cropped.shape

    for i in range(3): # 3 channels
        a[i] = cropped[:,:,i]
        b[i] = stretch(a[i],g[i],255)
        c[i] = palette_lut[b[i].astype(int)]

        cv2.imshow(f"{names[i]}", a[i]); cv2.moveWindow(f"{names[i]}", i*w, 0)
        cv2.imshow(f"{names[i]}_stretched", b[i]); cv2.moveWindow(f"{names[i]}_stretched", i*w, h)
        cv2.imshow(f"{names[i]}_colored", c[i]); cv2.moveWindow(f"{names[i]}_colored", i*w, 2*h)

    key = cv2.waitKey(1) & 0xFF
    if key == 27:
        break
    elif key==ord('-'):
        radius -= 1;
    elif key==ord('='):
        radius += 1;
    elif key==ord('x'):
        center[0] += 1;
    elif key==ord('X'):
        center[0] -= 1;
    elif key==ord('y'):
        center[1] += 1;
    elif key==ord('Y'):
        center[1] -= 1;
    elif key==ord('r'):
        g[0] += 1;
    elif key==ord('R'):
        g[0] -= 1;
    elif key==ord('g'):
        g[1] += 1;
    elif key==ord('G'):
        g[1] -= 1;
    elif key==ord('b'):
        g[2] += 1;
    elif key==ord('B'):
        g[2] -= 1;
    elif key == ord(' '):
        for i in range(3):
            print(f"{names[i]}={g[i]}")
        print("center",center,"radius",radius)

cv2.destroyAllWindows()
```

# various pip install libraries
```bash
pip install opencv-python
```

# jinja2
```python
#!/usr/bin/env python3

import json,re,sys
from jinja2 import Environment, FileSystemLoader

env = Environment(loader=FileSystemLoader("templates"))

def ref(id):
    return items_by_id[id] 

def guid_from_id(id):
    if not id:
        return ""
    return id[4:]

def dump(item):
    if not item:
        return ""
    return "#"+json.dumps(item,indent=2).replace("\n","\n#")

def escape_key_name(s): # for ninja2/Liquid templates
    s = re.sub(r"@","",s) # @id @type etc
    s = re.sub(r".*:","",s) # remove prefix:
    return s

def escape_key_names(item):
  try:
    result = { escape_key_name(x): v for x, v in item.items() } # renames keynames to use in template
  except Exception as e:
    print(item)
    sys.exit(1)
  return result

env.filters['guid'] = guid_from_id
env.filters['ref'] = ref
env.filters['dump'] = dump

data = json.load(open("data.json"))
items = data["@graph"][:4]
items = [escape_key_names(item) for item in items]
items_by_id = { item["id"]:item for item in items }

for item in items:
    # print(item)
    tmpl = env.get_template(item["type"].replace("aet:","")+".ttl")
    print(tmpl.render(**item))
```

# copy 1000 random files from a csv
```python
#!/usr/bin/env python3

import shutil,os
import pandas as pd

df = pd.read_csv("../test-omgekeerde-kaartjes/output.csv") 

# filter alle kaarten weg van waar niet zeker van is dat ze goed zijn
df = df[df['verkeerd_om'] == 'False'] 

# neem een random sample van 1000 stuks
# altijd dezelfde sample door de 'random_state=42'. 
# hierdoor hoeft de output map niet eerst leeg gemaakt te worden
# mocht je dat wel willen dan kan dat met # shutil.rmtree("output/")
random_items = df.sample(n=1000, random_state=42)

# kopiëer 1000 eerder gecropte bestanden ('crops/boven') naar de output/ map
for index,item in random_items.iterrows():
    input_path = item["filename"].replace("../","../crops/boven/")
    rel_path = item["filename"].replace("../","")
    output_folder = "output/" + os.path.dirname(rel_path)
    basename = os.path.basename(rel_path)

    # maak map aan indien nodig
    if not os.path.exists(output_folder):
        os.makedirs(output_folder)

    # kopiëren indien nodig
    output_file_path = os.path.join(output_folder,basename)
    print(input_path," >>> ", output_folder)
    if not os.path.exists(output_file_path):
        shutil.copy2(input_path, output_file_path)
```

# incremental update of sqlite database from CSV file
```python
#!/usr/bin/env python3
import csv,sqlite3
from tqdm import tqdm
import argparse

parser=argparse.ArgumentParser()
parser.add_argument("--csv", help="input csv file with format: ID,GUID", required=True)
args=parser.parse_args()

csv_file = args.csv
db_file = 'database.db'
table_name = 'guids'

conn = sqlite3.connect(db_file)
cursor = conn.cursor()
cursor.execute(f"CREATE TABLE IF NOT EXISTS {table_name} (ID INTEGER, GUID TEXT)")
cursor.execute(f"CREATE INDEX IF NOT EXISTS idx_id ON {table_name} (ID)")
cursor.execute(f"CREATE INDEX IF NOT EXISTS idx_guid ON {table_name} (GUID)")

num_rows = sum(1 for _ in open(csv_file))

with open(csv_file, 'r') as file:
    csv_data = csv.reader(file)
    next(csv_data) # skip header
    for i, row in tqdm(enumerate(csv_data, start=1), total=num_rows):
        cursor.execute(f"REPLACE INTO {table_name} (ID, GUID) VALUES (?, ?)", (int(row[0]), row[1]))

conn.commit()
conn.close()
```

# read csv with DictReader
```python
rows = list(csv.DictReader(open("urls-lijst-van-picturae-verrijkt-met-sql.csv"), delimiter=","))
```

# write (filtered) rows to Excel with pandas
```python
#write result as Excel
excel_writer = pd.ExcelWriter("probleem-bestandskoppelingen.xlsx", engine='xlsxwriter')
df = pd.DataFrame(results)
df = df[df['info'] != 'ok'] # filter alles weg met 'ok' in de kolom 'info'
df.to_excel(excel_writer, sheet_name="Sheet1", startrow=1, header=False, index=False)
(max_row, max_col) = df.shape
sheet = excel_writer.sheets['Sheet1']
[sheet.set_column(i,i,w) for i,w in enumerate([20,10,50,10,12,50,10,10,50])] # set column widths
sheet.add_table(0, 0, max_row, max_col-1, {'columns': [{'header': col} for col in df.columns]})
excel_writer.close()
```

# copy file and rebuild structure
```python
def check(row): #check/process single file
    rel_path = re.sub(r"X:[\\/]yyy[\\/]","",row["path"]).replace("\\","/")
    abs_path = "/Volumes/xxx$/yyy/" + rel_path
    file_exists = os.path.exists(abs_path) and os.path.isfile(abs_path)
    if file_exists:
        # kopieer bestand naar lokaal en maak directories aan
        output_folder = "output/" + os.path.dirname(rel_path)
        basename = os.path.basename(rel_path)
        if not os.path.exists(output_folder):
            os.makedirs(output_folder)
        output_file_path = os.path.join(output_folder,basename)
        if not os.path.exists(output_file_path):
            shutil.copy2(abs_path, output_file_path)
```

# parallel
```python
from joblib import Parallel, delayed

def check(row):
  return row

results = Parallel(n_jobs=-1)(delayed(check)(row) for row in tqdm(rows))
print(results)
```

# image check
```python
from PIL import Image

# do image check
w = h = ""
if not file_exists:
    image_info = "bestand niet gevonden"
elif os.path.getsize(abs_path)==0:
    image_info = "bestand is leeg"
else:
    try:
        im = Image.open(output_file_path)            
        im.verify() #I perform also verify, don't know if he sees other types o defects
        im.close() #reload is necessary in my case
        im = Image.open(output_file_path) 
        im.transpose(Image.FLIP_LEFT_RIGHT)
        im.close()
        image_info = "ok"
        w, h = im.size
    except Exception as e: 
        image_info = f"fout in afbeelding: {e}"
        passass
```

# crop images
```python
#!/usr/bin/env python3

import sys,os
from tqdm import tqdm
from pathlib import Path
from PIL import Image

folder_names = tqdm(["a","b","c"])

for folder in folder_names:
folder_names.set_description(f"{folder}")
images = sorted(list(Path(f"../xx/{folder}/").rglob("*.jpg")))
file_names = tqdm(images,leave=False)

for image in file_names:
    filename = str(image)
    file_names.set_description(f"{filename}")

    img = Image.open(filename)
    w, h = img.size

    for naam,y1,y2 in [ ("boven",0,1500), ("onder", 1500,h)]:
	output_filename = filename.replace("..", naam)
	output_folder = os.path.dirname(output_filename)
	if not os.path.exists(output_filename):
	    if y2>y1: 
		crop = img.crop((0, y1, w, y2))
	    else:
		crop = img
	    os.makedirs(output_folder, exist_ok=True)
	    crop.save(output_filename)
```

# load json
```python
with open(input_file_path,"r") as file:    
  data = json.load(file)
```

<a name='mac-ocr'></a>
# ocr on Mac
```python
#!/Applications/Xcode.app/Contents/Developer/usr/bin/python3

import config,json,os,subprocess,ocr2json
from tqdm import tqdm
from joblib import Parallel, delayed

import Quartz,Vision
from Cocoa import NSURL
from Foundation import NSDictionary
from wurlitzer import pipes # needed to capture system-level stderr

def ocr(image_filename):
    input_url = NSURL.fileURLWithPath_(image_filename)
    with pipes() as (out, err):
        input_image = Quartz.CIImage.imageWithContentsOfURL_(input_url)
    (width,height) = input_image.extent().size
    vision_options = NSDictionary.dictionaryWithDictionary_({})
    vision_handler = Vision.VNImageRequestHandler.alloc().initWithCIImage_options_(
        input_image, vision_options
    )

    request = Vision.VNRecognizeTextRequest.alloc().init().autorelease()
    request.setRecognitionLevel_(Vision.VNRequestTextRecognitionLevelAccurate) #VNRequestTextRecognitionLevelFast
    request.setRecognitionLanguages_(["nl-NL"])
    error = vision_handler.performRequests_error_([request], None)

    results = []
    for item in request.results():
        bbox = item.boundingBox()
        w, h = bbox.size.width, bbox.size.height
        x, y = bbox.origin.x, bbox.origin.y
        results.append({
            "x":int(x*width),
            "y":int(height - y*height - h*height),
            "w":int(w*width),
            "h":int(h*height),
            "conf":item.confidence(),
            "text":item.text()
        })
    return results

def do_ocr(image_file_path, progress):
    file_id = config.get_id_from_path(image_file_path)
    json_file_path = config.make_path(base_folder=config.DEEDS_OCR_JSON_FOLDER, id=file_id, suffix=".json")

    if not os.path.exists(json_file_path):
        data = ocr(str(image_file_path))
        if data and len(data)>0:
            json.dump(data, open(json_file_path,"w"), indent=2)
        print(json_file_path,str(int(progress*1000)/10.)+"%")


def run():
    print("run ocr_per_akte")

    if not os.path.exists(config.DEEDS_OCR_JSON_FOLDER):
        os.mkdir(config.DEEDS_OCR_JSON_FOLDER)

    file_paths = config.get_deeds_image_file_paths()
    results = Parallel(n_jobs=1, prefer="threads")(
      delayed(do_ocr)(image_file_path,i/len(file_paths))
      for i,image_file_path in enumerate(file_paths)
    )


if __name__ == '__main__':
    run()

```

# show all sqlite tables
```python
res = cursor.execute("SELECT name FROM sqlite_master")
result = res.fetchone()
print(result)
```

# parse number sequence
```python
#40630837,"1939","165-172 : ill."
#40630838,"1996","4-8 : ill., plgr., tek."
#40630839,"1996","10-13 : portr."
#40630843,"1996","18"
#40631828,"1969","41-49"
#40631829,"1969","51-60"
#40631836,"1969","99-119"
#40631846,"1970","68-70"
#40631847,"1970","93-102 : ill."
#40632861,"1931","38-40"
#40632864,"1931","42-44; 57-59"
#40633811,"1943","1-3"
#40633813,"1943","4-7"
#40633814,"1943","9-13; 18"

import csv,re,sys

def get_number_list(last_column):
    numbers = []
    last_column = last_column.replace(", ","; ") # ,->;
    last_column = last_column.replace(": ","; ") # :->;
    for range_str in last_column.split(';'):     # multiple numbers/sequences separated by ;
        range_str = range_str.strip()            # trim
        range_str = range_str.replace("[","")    # [num] means manually determined page number 
        range_str = range_str.replace("]","") 
        
        if '-' in range_str: # sequence
            m = re.findall("(\d+)\s*-+\s*(\d+)",range_str)
            if m and len(m)==1:
                m=m[0]
                if len(m)==2:
                    start = int(m[0])
                    end = int(m[1])
                    numbers.extend(range(start, end + 1))
                else:
                    print("WARNING 1",last_column)
            else:
                print("WARNING 2",last_column)
        else:
            range_str = re.sub(r" .*","",range_str) # remove everything after first space (CHECKME!)
            
            if range_str.isdigit():  # single number
                numbers.append(int(range_str))
            else:
                print("WARNING 3",last_column, "HUIDIGE STUK:",range_str)
    return numbers

with open('artikelen.csv', 'r') as file:
    reader = csv.reader(file)
    next(reader)  # skip header

    for row in reader:
        last_column = row[-1]
        try:
            numbers = get_number_list(last_column)
        except Exception as e:
            print(e,row);
        print(row, numbers)
```
# copy all files from textfile to folder without hierarchy / folder structure
```python
import os
import shutil
from tqdm import tqdm

def copy_files_to_destination(file_path, destination):
    with open(file_path, 'r') as file:
        files = list(file.readlines())
        for line in tqdm(files):
            absolute_path = line.strip()
            if os.path.isfile(absolute_path):
                file_name = os.path.basename(absolute_path)
                dest_path = os.path.join(destination, file_name)
                shutil.copy(absolute_path, dest_path)
                #print(f"Bestand gekopieerd: {absolute_path} naar {dest_path}")

source_file = 'xmls.txt'
destination_folder = 'xml/'

copy_files_to_destination(source_file, destination_folder)
```

# Render AltoXML data to JPG for quality check
```python
from pathlib import Path
import os,cv2
from alto import parse_file, String
import numpy as np
from PIL import Image, ImageDraw, ImageFont
from tqdm import tqdm

# BASE_FOLDER = "xxxx 2010-2022 50119/"
BASE_FOLDER = "xxxx 2010-2022 14215/"
IMAGE_FOLDER = BASE_FOLDER+"JPG"
ALTO_FOLDER = BASE_FOLDER+"Alto-XML"
OUTPUT_FOLDER = BASE_FOLDER+"output"

def list_files(folder, scheme): 
    return list(Path(folder).rglob(scheme))

def list_image_files():
    return list_files(IMAGE_FOLDER,"*.jpg")

def list_alto_files():
    return list_files(ALTO_FOLDER,"*.xml")

def get_alto_file(file_id):
    return Path(str(make_path(ALTO_FOLDER, file_id, ".xml")).replace(".xml","_alto.xml"))

def get_image_file(file_id):
    return make_path(ALTO_FOLDER, file_id, ".jpg")

def get_output_file(file_id):
    return make_path(OUTPUT_FOLDER, file_id, ".jpg")

def get_id_from_path(path):
    return Path(os.path.basename(os.path.dirname(path))).joinpath(path.stem)

def make_path(base_folder, id, suffix):
    file = Path(base_folder).joinpath(id).with_suffix(suffix)
    file_folder = file.parent
    file_folder.mkdir(parents=True, exist_ok=True)
    return file

#######################

font = ImageFont.truetype("arial.ttf", 24)
for image_file in tqdm(list_image_files()[:5]):
    img = Image.open(str(image_file))
    draw = ImageDraw.Draw(img)
    file_id = get_id_from_path(image_file)
    alto_file = get_alto_file(file_id)
    output_file = get_output_file(file_id)
    alto = parse_file(str(alto_file))
    for line in alto.extract_text_lines():
        for word in line.strings:
            if isinstance(word, String):
                (x,y) = p1 = tuple(int(num) for num in (word.hpos,word.vpos))
                (w,h) = tuple(int(num) for num in (word.width,word.height))
                p2 = (x+w,y+h)
                draw.rectangle((x, y, x+w, y+h), outline="blue")
                draw.text((x, y+h-10), word.content, font=font, fill="red")

    nieuwe_breedte = int(img.width * 0.5)
    nieuwe_hoogte = int(img.height * 0.5)
    img = img.resize((nieuwe_breedte, nieuwe_hoogte))
    img.save(str(output_file))
```

# Groeperen met Pandas
```python
import pandas as pd
df = pd.read_excel('data/all-rows.xlsx')

df = df[df['CODE'].isin(["1202.xx","1202.xx",........])]

groepen = df.groupby(['Straatnaam', 'Huisnummer'])
groepen = groepen.filter(lambda x: len(x) > 1)
groepen = groepen.groupby(['Straatnaam', 'Huisnummer'])

with open("tmp.tsv","w") as out:
    for naam, groep in groepen:
        print(" ".join(naam),file=out)
        for index, rij in groep.iterrows():
            rij = rij.fillna('')
            print(rij["ID"], rij["CODE"], " ", rij['Achternaam'],  rij['Voorna(a)m(en)'], rij['Geboortedatum'], rij["xxxx"], rij["Overslaan in uitvoer"], rij["Externe Identifier"], rij["Bron overlijden"], rij["GUID"], sep='\t',file=out )
        print("\n",file=out)
```

# Download all prismic documents and images
```python
#!/usr/bin/env python3

import os,re,requests,json,urllib,hashlib
from urllib.parse import urlparse
from collections import defaultdict

url = "https://XXXXXX.cdn.prismic.io/api/v2/documents/search?ref=XXXXXXXX"

while url:
    response = requests.get(url)
    data = response.json()
    page = data.get("page")
    results = data.get("results")
    json.dump(data, open(f"page{page}.json","w"), indent=4)

    for result in results:
        for field in result["data"]:
            
            if result["data"][field] and type(result["data"][field])==dict and "url" in result["data"][field]:
                url = result["data"][field]["url"]
            
                parsed_url = urlparse(url)
                path = parsed_url.path
                ext = os.path.splitext(path)[1]

                filename = "images/" + hashlib.md5(url.encode()).hexdigest() + ext

                print(field, filename)
                
                if not os.path.exists(filename):
                    urllib.request.urlretrieve(url, filename) # save 

    url = data.get("next_page")

    # break
```

# file exists
```python
if os.path.exists(filename):
```

# voeg dashes toe aan GUID 
```python
def guid_dashed(s):
  return "{" + (f"{s[:8]}-{s[8:12]}-{s[12:16]}-{s[16:20]}-{s[20:]}") + "}"
```

# splits achternaam op tussenvoegsel en achternaam
```python
    # split achternaam / tussenvoegsel
    for rol in ["overledene","vader","moeder","partner"]:
        achternaam = item.get(f"achternaam {rol}")

        patroon = re.compile(r"^(van der|van den|van de|van het|van 't|van|der|de)", re.IGNORECASE)
        tussenvoegsel = patroon.findall(achternaam)
        achternaam = patroon.sub("", achternaam)

        if tussenvoegsel:
            item[f"tussenvoegsel {rol}"] = (" ".join(tussenvoegsel)).lower()
        item[f"achternaam {rol}"] = achternaam.strip()
```

# re-create items based on expected keys with specific field order using list comprehension
```python
expected_keys = ["filename", "x", "y" ] # etc
items = [{key: item[key] for key in expected_keys if key in item} for item in items]

# without list comprehension:
# new_items = []
# for item in items:
#     new_item = {}
#     for key in expected_keys:
#         if key in item:
#             new_item[key] = item[key]
#     new_items.append(new_item)
```

# zorg dat de letter A of B altijd gevolgd wordt door een spatie wanneer het direct gevolgd wordt door een cijfer.
```python
re.sub(r"(A|B)(\d)", r"\1 \2", input_string)
```

# fuzzy lookup for dates in different Dutch formats
```python
dates = get_all_dates(datetime(1800,1,1))

lut  = { get_dutch_date_with_dashes(date):date for date in dates }
lut |= { get_dutch_date_with_written_month(date):date for date in dates }
lut |= { get_dutch_date_fully_written(date):date for date in dates }

a = fuzzy_extract("5-10_1950t", lut.keys())
b = fuzzy_extract("5 oktber 1950", lut.keys())
c = fuzzy_extract("vijftn tober negentsnhonerd viftg", lut.keys())

print(lut[a[0]], a)
print(lut[b[0]], b)
print(lut[c[0]], c)
```

# get all dates in a range as a list
with list comprehension and typed
```python
def get_all_dates(start_date: datetime = datetime(1900, 1, 1), end_date: datetime = datetime.now()) -> List[datetime]:
    return [start_date + timedelta(days=d) for d in range((end_date - start_date).days+1)]
```
without:
```python
def get_all_dates(start_date=datetime(1900, 1, 1), end_date=datetime.now()):
    dates = []
    date = start_date
    while date < end_date:
        dates.append(date)
        date += timedelta(days=1)
    return dates  
```

# format Dutch dates
```python
def get_dutch_date_with_dashes(date): # 05-12-1979  met voorloop nul
    return date.strftime("%d-%m-%Y")

def get_dutch_date_written_month(date): # 5 december 1979  zonder voorloop nul bij dag
    locale.setlocale(locale.LC_TIME, "nl_NL")
    return date.strftime("%-d %B %Y")

def get_dutch_date_fully_written(date): #vijftien december negentienhonderd negenzeventig
    locale.setlocale(locale.LC_TIME, "nl_NL")
    numbers_str = ["één","twee","drie","vier","vijf","zes","zeven","acht","negen","tien","elf","twaalf","dertien","veertien","vijftien","zestien","zeventien","achttien","negentien","twintig","eenentwintig","tweeëntwintig","drieëntwintig","vierentwintig","vijfentwintig","zesentwintig","zevenentwintig","achtentwintig","negenentwintig","dertig","eenendertig","tweeëndertig","drieëndertig","vierendertig","vijfendertig","zesendertig","zevenendertig","achtendertig","negenendertig","veertig","eenenveertig","tweeënveertig","drieënveertig","vierenveertig","vijfenveertig","zesenveertig","zevenenveertig","achtenveertig","negenenveertig","vijftig","eenenvijftig","tweeënvijftig","drieënvijftig","vierenvijftig","vijfenvijftig","zesenvijftig","zevenenvijftig","achtenvijftig","negenenvijftig","zestig","eenenzestig","tweeënzestig","drieënzestig","vierenzestig","vijfenzestig","zesenzestig","zevenenzestig","achtenzestig","negenenzestig","zeventig","eenenzeventig","tweeënzeventig","drieënzeventig","vierenzeventig","vijfenzeventig","zesenzeventig","zevenenzeventig","achtenzeventig","negenenzeventig","tachtig","eenentachtig","tweeëntachtig","drieëntachtig","vierentachtig","vijfentachtig","zesentachtig","zevenentachtig","achtentachtig","negenentachtig","negentig","eenennegentig","tweeënnegentig","drieënnegentig","vierennegentig","vijfennegentig","zesennegentig","zevenennegentig","achtennegentig","negenennegentig"]
    day = numbers_str[date.day-1]
    century = int(date.year/100)
    year_within_century = int(date.year%100)
    century_str = numbers_str[century-1]
    year_within_century_str = numbers_str[year_within_century-1] if year_within_century else ""
    return (f"{day} {date.strftime('%B')} {century_str}honderd {year_within_century_str}").strip()
```

# merge two dicts with '|' operator
```python
datums = get_alle_datums_als_tekst(datetime(1800,1,1))
datums |= get_alle_datums(datetime(1800,1,1))
```

# filter out keys that are not in expected_keys
```python
data = {"filename": "x", "aktenummer": "y", "overlijdensdatum": "z", "achternaam overledene": "FILTERED OUT"}
expected_keys = ["filename", "aktenummer", "overlijdensdatum"]
data = dict(filter(lambda x: x[0] in expected_keys, data.items()))
```
or for multiple items:
```python
items = [ dict(filter(lambda x: x[0] in expected_keys, item.items())) for item in items ]
```

# call a remote function running with Flask
```bash
URL=http://URL
INPUT_FILE=INPUT.txt
OUTPUT_FILE=OUTPUT.json
curl -J -X POST -F "file=@$INPUT_FILE" $URL > $OUTPUT_FILE
```

# remote function implementation on server with Flask
```python
#!/usr/bin/env python3
from flask import Flask,request,send_file
import json

app = Flask(__name__)

@app.route("/")
def index():
    return "ok"

@app.route('/run', methods=['POST'])
def run():
    input_file_path = '/tmp/INPUT.txt'
    output_file_path = '/tmp/OUTPUT.json'
    f = request.files['file']
    f.save(input_file_path)

    with \
        open(input_file_path, 'r', encoding="utf-8") as input_file, \
        open(output_file_path, 'w') as output_file:
            output = CONVERT_FILE_FUNCTION(input_file.read())
            json.dump(output, output_file, indent=2, ensure_ascii=False)

    result_file = open(output_file_path,'rb')
    return send_file(result_file, as_attachment=True, download_name='result.json')

if __name__ == "__main__":
    from waitress import serve
    print("URL:PORT")
    serve(app, host=URL, port=PORT)
```

# harvest OAI-PMH and save as xml and json (using resumptionToken)
```python
#!/usr/bin/env python3
import requests,json,xmltodict

base_url = "...........?verb=ListRecords"
url = base_url + "&metadataPrefix=oai_a2a&set=............"
page = 0

while url:
    print(url)
    response = requests.get(url)

    with open(f"data/xml/page-{page}.xml","w") as f:
        f.write(response.text)

    d = xmltodict.parse(response.text)
    json.dump(d,open(f"data/json/page-{page}.json","w"),indent=2)

    resumption_token = d["OAI-PMH"]["ListRecords"]["resumptionToken"]["#text"]

    if resumption_token:
        url = base_url + "&resumptionToken=" + resumption_token
        page += 1
    else:
        print("end?")
        break

```

# selected python version...
`/Applications/Xcode.app/Contents/Developer/usr/bin/python3.9`

# print SSL version
```python
import ssl; print(ssl.OPENSSL_VERSION)
```

# get version of installed modules with pip
```bash
pip list
```

# update module with pip
```bash
pip install --upgrade openai
```

# list amount of each 'Soort' across different json files
```python
from collections import defaultdict
from pathlib import Path
import json

soort = defaultdict(int)
input_files = list(Path("data/json").rglob("*.json"))

for input_file_path in input_files:
    with open(input_file_path,"r") as file:    
        for item in json.load(file):
            soort[item.get("Soort")] += 1

soort = dict(sorted(soort.items(), key=lambda x:x[1], reverse=True))

print(json.dumps(soort,indent=2,ensure_ascii=False))
```
according to ChatGPT this code can written shorter as, thanks:
```python
import json
import pandas as pd
from pathlib import Path

input_files = list(Path("data/json").rglob("*.json"))
df = pd.concat([pd.read_json(file) for file in input_files])
soort = df["Soort"].value_counts().to_dict()
print(json.dumps(soort,indent=2,ensure_ascii=False))
```

# sort dict by value descending
```python
soort = dict(sorted(soort.items(), key=lambda x:x[1], reverse=True))
```

# default dict with int instead of lambda:0
```python
soort = defaultdict(int)
```

# RDF / turtle / jsonLD
see [RDF](/rdf)

# Download multiple pages of JSON from (omeka-s) API
```python
page = 1
url = f"{API_URL}&page={page}"
while url:
    print(url)
    response = requests.get(url)
    json.dump(response.json(), open(f"data/page{page}.json","w"), indent=2)
    next = response.links.get("next")
    url = next["url"] if next else ""
    page += 1
```

# get next link url from http response header 
```python
response = requests.get(url)
next = response.links.get("next")
if next:
	print(next["url"])
```


# json dumps http request response header
```python
import requests, json
response = requests.get(url)
print(json.dumps(dict(response.headers),indent=2))
```

# item get
```python
if "Achternaam" in item and item["Achternaam"]=="Bicker":
```
vs.
```python
if item.get("Achternaam")=="Bicker":
````


# remove double line breaks with optional whitespace in between
```python
s = re.sub(r"\n\s*\n", "\n", s)
```

# replace spaces by '_' from all keys in a dict
```python
item = { x.replace(' ', '_'): v for x, v in item.items() }
```

# pipx
pipx is a tool to help you install and run end-user applications written in Python. It's roughly similar to macOS's brew, JavaScript's npx, and Linux's apt.

It's closely related to pip. In fact, it uses pip, but is focused on installing and managing Python packages that can be run from the command line directly as applications.
https://pypa.github.io/pipx/

# tweepy
```python
import tweepy,json
client = tweepy.Client("Bearer Token")
user_id=123123123
tweet_fields = ["attachments","author_id","context_annotations","conversation_id","created_at","entities","geo","id","in_reply_to_user_id","lang","referenced_tweets","reply_settings","source","text","withheld"]
tweets = []

# user tweets
#   similar for client.get_liked_tweets
for tweet in tweepy.Paginator(client.get_users_tweets,id=user_id, tweet_fields=tweet_fields, max_results=100).flatten(limit=1500):
    tweets.append(tweet.data)
with open('tweets.json', 'w') as f:
    json.dump(tweets, f, indent=2)
```

# parse and format date
```python
# from: 2023-01-24T10:32:01+01:00
# to: 2023-01-24 10:32
datetime.datetime.strptime(date_str, "%d/%b/%Y:%H:%M:%S %z").strftime("%Y-%m-%d %H:%M")
```

# parse access.log files to csv
see: https://coderwall.com/p/snn1ag/regex-to-parse-your-default-nginx-access-logs
```python
#!/usr/bin/env python3
import json,re,csv,datetime

result = []

for line in open('all.log').readlines():
    # if not re.findall(r'GET /\d{1,2} ', line): # filter on GET /1, GET /2 etc.
    #     continue

    r = re.match(r'(?P<ipaddress>\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}) - - \[(?P<dateandtime>.*)\] \"(?P<httpstatus>(GET|POST) .+ HTTP\/1\.1)\" (?P<returnstatus>\d{3} \d+) (\".*\")(?P<browserinfo>.*)\"',line)
    if r != None:
        result.append({'IP address': r.group('ipaddress'), 'Time Stamp': 
            datetime.datetime.strptime(r.group('dateandtime'), "%d/%b/%Y:%H:%M:%S %z").strftime("%Y-%m-%d %H:%M"),
            'HTTP status': r.group('httpstatus'), 'Return status': 
            r.group('returnstatus'), 'Browser Info': r.group('browserinfo')})

# print(result)
with open('output.csv', 'w', encoding='utf8') as file:
    writer = csv.DictWriter(file, result[0].keys())
    writer.writeheader()
    writer.writerows(result)
    
#with open('data.json', 'w') as fp:
#    json.dump(result, fp, indent=2) 
```

# concat two lists
```python
result = list1 + list2
```

# json2csv (where json is an array of 'flat' objects)
```python
#!/usr/bin/env python3

import sys,csv,json

if len(sys.argv)<3:
    sys.exit(f"Usage: {sys.argv[0]} input.json output.csv")

with open(sys.argv[1]) as json_file, open(sys.argv[2],'w') as csv_file:
    data = json.load(json_file)
    all_keys = set(key for row in data for key in row.keys())
    writer = csv.DictWriter(csv_file, all_keys)
    writer.writeheader()
    writer.writerows(data)
```

# get all keys used in a list of dicts
```python
all_keys = set()
for row in data:
	for key in row.keys():
	    all_keys.add(key)
print(all_keys)    
```

# how to retrieve in Python multiple json files from a REST server when the next_page attribute is supplied by the server?
```python
import requests, json
url = "YOUR_API_URL"
while url:
    response = requests.get(url)
    data = response.json()
    page = data.get("page")
    json.dump(data, open(f"page{page}.json","w"), indent=4)
    url = data.get("next_page")
```

# read formula from cells in Excel
```python
from openpyxl import load_workbook
wb = load_workbook(filename = 'HUA-Sabine-Fuzzy-Matches-score-vanaf-50pct-19-april-2022.xlsx')
sheet = wb['Sheet1']
for row in range(1,10):
	status = sheet.cell(row=row, column=1).value
```
# install package with sudo as root
this way also for example the wwwdata user can use the package. not sure if it's safe.
```bash
sudo su
sudo pip3 install mypackage
```

# fuzzy regex
```python
match = regex.search(f"(negentienhonderd){{e<=3}}", item["text"], regex.BESTMATCH)
```

# point finding functions
```python
def get_points_left(point, points):
    if points:
        return list(filter(lambda p: p[0]<point[0], points))

def get_points_below(point, points):
    if points:
        return list(filter(lambda p: p[1]>point[1], points))

def get_points_left_below(point, points):
    if points:
        return get_points_left(point, get_points_below(point, points))

def closest_point(point, points):
    if points:
        return points[distance.cdist([point], points).argmin()]

#example:
p2 = closest_point(p1, get_points_left_below(p, points))
```

# template matching with opencv
```python
def match(img, template):
  result = cv2.matchTemplate(img, template, cv2.TM_CCORR_NORMED)
  min_val, max_val, min_loc, max_loc = cv2.minMaxLoc(result)
  lt = (x1,y1) = max_loc
  rb = (x2,y2) = (x1+tw,y1+th)
  return (lt,rb)
  
lt,rb = match(img, template)
cv2.rectangle(img, lt, rb, color=0, thickness=-1)
```

# cv2.rectangle
```
cv2.rectangle(img, lt, rb, color=(b,g,r), thickness=10)
```

# make a list unique
```python
result = list(set(['a','b','a']))
# result = ['a','b']
```

# create csv from a list of tuples
```python
plaatsnamen = [("Utrecht",5), ("Amersfoort",2)]
writer = csv.writer(open("output.csv","w"))
writer.writerow(['plaatsnaam','aantal'])
writer.writerows(plaatsnamen)
```

# sort dict
```python
plaatsnamen = dict(sorted(plaatsnamen.items(), key=lambda item: item[1], reverse=True))
```

# fuzzy match and convert a Dutch written/spelled date from the 20th century:
```python
from datetime import datetime, timedelta
import sys
from rapidfuzz import fuzz
import rapidfuzz.process as fuzzy

def alle_datums_20e_eeuw_als_tekst():  # returns a dict {string:datetime}
    maanden = ["januari","februari","maart","april","mei","juni","juli","augustus","september","oktober","november","december"]

    getallen_1tm99 = ["één","twee","drie","vier","vijf","zes","zeven","acht","negen","tien","elf","twaalf","dertien","veertien","vijftien","zestien","zeventien","achttien","negentien","twintig","eenentwintig","tweeëntwintig","drieëntwintig","vierentwintig","vijfentwintig","zesentwintig","zevenentwintig","achtentwintig","negenentwintig","dertig","eenendertig","tweeëndertig","drieëndertig","vierendertig","vijfendertig","zesendertig","zevenendertig","achtendertig","negenendertig","veertig","eenenveertig","tweeënveertig","drieënveertig","vierenveertig","vijfenveertig","zesenveertig","zevenenveertig","achtenveertig","negenenveertig","vijftig","eenenvijftig","tweeënvijftig","drieënvijftig","vierenvijftig","vijfenvijftig","zesenvijftig","zevenenvijftig","achtenvijftig","negenenvijftig","zestig","eenenzestig","tweeënzestig","drieënzestig","vierenzestig","vijfenzestig","zesenzestig","zevenenzestig","achtenzestig","negenenzestig","zeventig","eenenzeventig","tweeënzeventig","drieënzeventig","vierenzeventig","vijfenzeventig","zesenzeventig","zevenenzeventig","achtenzeventig","negenenzeventig","tachtig","eenentachtig","tweeëntachtig","drieëntachtig","vierentachtig","vijfentachtig","zesentachtig","zevenentachtig","achtentachtig","negenentachtig","negentig","eenennegentig","tweeënnegentig","drieënnegentig","vierennegentig","vijfennegentig","zesennegentig","zevenennegentig","achtennegentig","negenennegentig"]

    current_date = datetime(1900, 1, 1)
    dagen = {}
    while current_date.year < 2000:
        dag = getallen_1tm99[current_date.day-1]
        maand = maanden[current_date.month-1]
        jaar = "negentienhonderd "+getallen_1tm99[current_date.year-1900-1]

        dagen[f"{dag} {maand} {jaar}"] = current_date
        current_date += timedelta(days=1)
    return dagen

def fuzzy_extract(input_str, compare_strs): #(result, match_pct, idx)
    return fuzzy.extractOne(input_str, compare_strs, scorer=fuzz.ratio)

datums = alle_datums_20e_eeuw_als_tekst()
#result,_,_ = fuzzy_extract("dertien januari negentienhonderd negenennegentig", datums.keys())
result,_,_ = fuzzy_extract("dertasdfien januaasdfri negeasdfntienahonderd negenenneasdfgentig", datums.keys())

print(result)
print(datums[result])
```

# defaultdict in defaultdict (test)
```python
all_first_names_and_occurences_dict = defaultdict(lambda: defaultdict(lambda: 0))
```

# output_rows to excel using pandas
```python
print("writing Excel file")
excel_writer = pd.ExcelWriter(config.EXCEL_OUTPUT_FILE, engine='xlsxwriter')
df = pd.DataFrame(output_rows)
df.to_excel(excel_writer, sheet_name="Sheet1", startrow=1, header=False, index=False)
excel_writer.sheets['Sheet1'].set_column(0,20, 25) # for cols 0 to 20 set width=25
(max_row, max_col) = df.shape
column_settings = [{'header': column} for column in df.columns]
excel_writer.sheets['Sheet1'].add_table(0, 0, max_row, max_col - 1, {'columns': column_settings})
excel_writer.close()
```

## pandas groupby to tabs/sheets in Excel with table
```python
df = pd.read_csv("overzicht.csv") 

writer = pd.ExcelWriter('test.xlsx', engine='xlsxwriter')

for aet, frame in df.groupby("AET"):
	frame.to_excel(writer, sheet_name=aet, startrow=1, header=False, index=False)
	
	column_settings = [{'header': column} for column in frame.columns]
	(max_row, max_col) = frame.shape
	writer.sheets[aet].add_table(0, 0, max_row, max_col - 1, {'columns': column_settings})

writer.close()
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

via dotenv:
```python
import cx_Oracle
from dotenv import load_dotenv
load_dotenv()    

def oracle_get_cursor():
    lib_dir = os.path.join(os.environ["ORACLE_LIB_DIR"])
    cx_Oracle.init_oracle_client(lib_dir=lib_dir)
    dsn = cx_Oracle.makedsn(os.environ["ORACLE_IP"],'1521',service_name=os.environ["ORACLE_SERVICE_NAME"])
    connection = cx_Oracle.connect(os.environ["ORACLE_USER"], os.environ["ORACLE_PASS"], dsn)
    cur = connection.cursor()
    return cur
```

query result as dictionary (single result):
```python
def get_bestandsnaam(cur, id):
    cur.execute(f"SELECT * FROM bestanden WHERE id={id}")
    cur.rowfactory = lambda *args: dict(zip([d[0].lower() for d in cur.description], args))
    row = cur.fetchone()
    return row["bestandsnaam"]
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
```bash
pip install liquidpy
```

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

custom filters in liquidpy 'wild' mode
```liquid
{% addfilter md5 %}
import hashlib
def md5(s):
    return hashlib.md5(s.encode()).hexdigest()
{% endaddfilter %}
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
  return result

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
