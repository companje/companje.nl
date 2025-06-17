# Update 'backlink' metadata field
```python
import requests,json,csv,sys
from tqdm import tqdm

def get_headers(token):
  return {
      'Accept-Language': 'en-GB,en-US;q=0.9,en;q=0.8',
      'Connection': 'keep-alive',
      'Origin': 'https://app.transkribus.org',
      'Referer': 'https://app.transkribus.org/',
      'Sec-Fetch-Dest': 'empty',
      'Sec-Fetch-Mode': 'cors',
      'Sec-Fetch-Site': 'cross-site',
      'Sec-GPC': '1',
      'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
      'accept': 'application/json, text/plain, */*',
      'authorization': f"Bearer {token}", # global
      'content-type': 'application/json',
      'sec-ch-ua': '"Brave";v="135", "Not-A.Brand";v="8", "Chromium";v="135"',
      'sec-ch-ua-mobile': '?0',
      'sec-ch-ua-platform': '"macOS"'
  }

def listDocuments(colId, token):
    url = f'https://transkribus.eu/TrpServer/rest/collections/{colId}/list'
    return requests.get(url, headers=get_headers(token)).json()

def setKeyValue(doc, key, value, token):
    colId = doc["mainColId"]
    docId = doc["docId"]
    url = f'https://transkribus.eu/TrpServer/rest/collections/{colId}/{docId}/metadata'
    data = {
        "type": "trpDocMetadata",
        "docId": docId,
        "title": doc["title"],
        key: value
    }
    response = requests.post(url, headers=get_headers(token), json=data)

    if response.status_code!=200:
        print("Error",response.response.text)
        sys.exit()

###############################################

token = '......'

docs = listDocuments(colId = 1904438, token=token)

guid_by_inv = {row["top"]+"."+row["stuk"]:row["GUID"] for row in csv.DictReader(open("guids-per-stuk.csv"),delimiter=';')}

for doc in tqdm(docs):
  inv = doc["title"].split(" -")[0].strip()
  guid = guid_by_inv[inv]
  backlink = f".....{guid}"
  print(inv,backlink)
  setKeyValue(doc, "backlink", backlink, token=token)
  # break
```


# Update 'hierarchy' metadata field
```python
import requests,json,csv,sys
from tqdm import tqdm

auth = 'Bearer .............. '
url = 'https://transkribus.eu/TrpServer/rest/collections/1904438/list'

headers = {
    'Accept-Language': 'en-GB,en-US;q=0.9,en;q=0.8',
    'Connection': 'keep-alive',
    'Origin': 'https://app.transkribus.org',
    'Referer': 'https://app.transkribus.org/',
    'Sec-Fetch-Dest': 'empty',
    'Sec-Fetch-Mode': 'cors',
    'Sec-Fetch-Site': 'cross-site',
    'Sec-GPC': '1',
    'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
    'accept': 'application/json, text/plain, */*',
    'authorization': auth,
    'content-type': 'application/json',
    'sec-ch-ua': '"Brave";v="135", "Not-A.Brand";v="8", "Chromium";v="135"',
    'sec-ch-ua-mobile': '?0',
    'sec-ch-ua-platform': '"macOS"'
}

def listDocuments(colId):
    url = f'https://transkribus.eu/TrpServer/rest/collections/{colId}/list'
    return requests.get(url, headers=headers).json()

def setDocumentHierarchy(doc, hierarchy):
    colId = doc["mainColId"]
    docId = doc["docId"]
    url = f'https://transkribus.eu/TrpServer/rest/collections/{colId}/{docId}/metadata'
    data = {
        "type": "trpDocMetadata",
        "docId": docId,
        "title": doc["title"],
        "hierarchy": hierarchy  #doc["collectionList"]["colList"][0]["colName"]
    }
    response = requests.post(url, headers=headers, json=data)

    if response.status_code!=200:
        print("Error",response.response.text)
        sys.exit()

###############################################

for doc in tqdm(listDocuments(colId = 1904438)):
    setDocumentHierarchy(doc, "34-4 - Notarissen in de stad Utrecht 1560-1905")
```

# List documents within collection and nrOfNew pages (not transcribed yet)
```python
import requests,json,os,sys

collection_id = 297657   # Burgerlijke Stand
htr_model_id = 58997        # Dutch Demeter 1

base_url = 'https://transkribus.eu/TrpServer/rest'
username = os.getenv("TRANSKRIBUS_USER")
password = os.getenv("TRANSKRIBUS_PASS")

auth_response = requests.post(f"{base_url}/auth/login", data={'user': username, 'pw': password}, headers = {'Accept': 'application/json'})
auth_token = auth_response.json()['sessionId']

headers = {
    #'Authorization': f'Bearer {auth_token}', # something wrong here?
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Cookie': f'JSESSIONID={auth_token}'
}

documents_response = requests.get(f"{base_url}/collections/{collection_id}/list", headers = headers)
documents_info = documents_response.json()

for doc in documents_info:
    doc_id = doc["docId"]    
    documents_response = requests.get(f"{base_url}/collections/{collection_id}/{doc_id}/fulldoc", headers = headers)
    doc_info = documents_response.json()["md"]
    title = doc_info["title"]
    print(title, doc_info["nrOfNew"]))
```
