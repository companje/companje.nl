# Wikimedia Commons Get All Files in certain Category

```python
#!/usr/local/bin/python3
import requests,json

category = "Images from Het Utrechts Archief"
cmcontinue = ""

while True: 
  base_url = "https://commons.wikimedia.org/w/api.php?action=query&list=categorymembers&"\
            "cmtype=file&cmtitle=Category:"+category+"&"\
            "cmlimit=max&format=json&cmcontinue=" + cmcontinue

  response = requests.get(base_url + cmcontinue)
  data = response.json()

  if not 'continue' in data:
    break

  cmcontinue = data["continue"]["cmcontinue"]

  for i in data["query"]["categorymembers"]:
    print(i["title"])
```
