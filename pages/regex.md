---
title: Regular Expressions
---

# zorg dat de letter A of B altijd gevolgd wordt door een spatie wanneer het direct gevolgd wordt door een cijfer.
```python
re.sub(r"(A|B)(\d)", r"\1 \2", input_string)
```

# swap values of lat/lon coordinate in Google Sheets
```
from:  5.11699542 52.09363001
to:   52.09363001, 5.11699542
=REGEXREPLACE(A1,"(\-?\d+\.\d+)\s+(\-?\d+\.\d+)","$2, $1")
```

# get domain
```python
domain = re.findall(r"^.+?[^\/:](?=[?\/]|$)",link)
#ie. http://dspace.library.uu.nl
```

# fuzzy regex
see python

# non greedy match in python
```python
matches = re.findall(r'(geboren op )(.*?)( te)(.*?)(,)',text) # non greedy
```

# nice visual regex debugger
* https://www.debuggex.com/

# non greedy img src url's in textfile in SublimeText
" was escaped in textfile by \\". In regex you need to escape the \ as well
```regex
src=\\".*?\\"
```
only the middle group:
```regex
(?<=src=\\")(.*?)(?=\\")
```

# everything before the first digit
```python
result = re.findall(r'^[^\d]*',address)
```

# replace all non-alphanumeric characters by spaces
```
=trim(regexreplace(REGEXREPLACE(join(" ",B2,D2,E2,F2),"[^a-zA-Z0-9\-]"," "),"\s+"," "))
```

# in sublimetext middelste groep van 3 groepen vinden
dit vind dus alleen B in (A)(B)(C)
```regex
(?<=lastname%2Fp%2Fvalue%2F)(.*)(?=%2Fq%2F)
(?<=birthdate%2Fp%2Fvalue%2F)(.*)(?=%2Fr)
```

# Alles na de laatste slash (filename)
```regex
[^/]+$)
```
# Alles voor de laatste slash (pad)
```regex
.*\/
```

# zoek alle GUID's in een tekst
```regex
[\da-z]{32}
```

# good introduction
* https://dl.icewarp.com/online_help/203030104.htm

# find date(s) in a string
```js
str.match(/\d{2}(\D)\d{2}\1\d{4}/g)
//finds one or more instances of dates in this format: 03-04-2018 
```
# add brackets around auto links in md files in [SublimeText](SublimeText)
```
Find: http://.*
Replace: <$0>
```

# javascript math id= parameter in querystring
```javascript
var link = "http://aap.com?nav_id=3-1&id=138622&index=14";
var matches = link.match(/&id=([^&]*)/);
var id = matches ? matches[1] : null;
//result: 138622
```

# regexp pal
<http://www.regexpal.com/>

# javascript parse whitespace
```javascript
let wc = "        7      1312";
wc.split(/,?\s+/).filter(Boolean);
```

# Shiffman about regex
<http://shiffman.net/a2z/regex/>

#  Find all URLs starting with http and ending with .html 
this works in the SublimeText search function:
```
(http).*(.html)
```

#  Online tools 
* <http://regexr.com/>
* <http://txt2re.com/>
* <http://www.phpliveregex.com/> (php)

#  expr 
```bash
expr "ok T:83.4 /0.0 B:0.0 /0.0 @:0" : 'ok T:\([0-9]*\.[0-9]*\)'
```

# sed & cut
```bash
grep -o '\(T:\)\([0-9]*\.[0-9]*\)' /tmp/UltiFi/ttyACM0/ temp.out | cut -c 3-
```
```bash
grep "spotify:track:" spotify.json | cut -c 16-51
grep "spotify:track:" spotify.json | head -n 1 | cut -c 16-51    # only first result
```

# awk
see [[awk]]

# turing machine with SED
<http://sed.sourceforge.net/grabbag/scripts/turing.sed>
