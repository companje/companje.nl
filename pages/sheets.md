see also [docs](/docs)

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
