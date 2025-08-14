see also [docs](/docs)

# zero padding
```
=TEXT(A1, "000")
```

# converteer naar Tabel
```
Cmd + Alt + T
```

# extract number after last underscore
```
# NL-UtHUA_34-4_154
=REGEXEXTRACT(A392, "_([^_]+)$")
```

# MATCH
als een item in een lijst voorkomt dan een X en anders niks
```
=IF(ISNUMBER(MATCH(B2; covers!A:A; 0)); "X"; "")
```

# FILTER
```
=FILTER('sheet2'!$E:$E;'sheet2'!$D:$D=C1))
```

# toggle formula / value view
```
Ctrl + `
```

# kwartaal
```
="K"&int(INT(mid(G16;6;2))/4)+1
```
# make column with unique values from a matrix
```
=SORT(UNIQUE(FLATTEN(A2:E)))
```

# replace
```javascript
lower(REGEXREPLACE(REGEXREPLACE( REGEXREPLACE(REGEXREPLACE(Trefwoorden!C2:C; "[–’+=&?|,\.() ""$/':;]"; "-") ;"-+";"-"); "[^a-zA-Z0-9\-]";"");"^-|-$"; ""))
```

# Vlookup with Search Key in Multiple Column Range
https://infoinspired.com/google-docs/spreadsheet/vlookup-find-a-search-key-in-multiple-columns-matrix-in-google-sheets/
(won’t work in Excel)

![matrix-to-two-columns](https://user-images.githubusercontent.com/156066/191735496-97868c5f-d1e8-480d-93db-dd30ac78e735.jpg)
```
=ArrayFormula(split(flatten(B2:D7&"|"&A2:A7);"|"))
```

# transpose comma separated values in multiple rows to one long list of items
```
=transpose(split(join(",";D:D);","))
```

# shortcut for new sheet
http://sheets.new

# Automatische links in spreadsheet kolom voor elke regel
```
'place this in the header cell above a column, that way you will still be able to sort the sheet
=ArrayFormula(if(A1:A<>"",hyperlink("https://....."&A1:A,"title:"&A1:A),))
```

<img src="https://user-images.githubusercontent.com/156066/181225745-d725e25d-011c-49ed-b882-ff10c40acfec.jpg" width="100%">
