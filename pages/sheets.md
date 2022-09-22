see also [docs](/docs)

# transpose comma separated values in multiple rows to one long list of items
``
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
