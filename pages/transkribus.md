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
