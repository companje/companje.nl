---
title: Excel
---

# match / index (with strip non-digits)
```
=INDEX(sheet_table!$A$2:$A$18;
  MATCH(
    1;
    (sheet_table!$B$2:$B$18=D2)*
    (sheet_table!$C$2:$C$18<=NUMBERVALUE(REGEXEXTRACT(E2;"\d+")))*
    (sheet_table!$D$2:$D$18>=NUMBERVALUE(REGEXEXTRACT(E2;"\d+")));
    0
  )
)
```


```
familienaam	voorvoegsel	voornaam	wijk	bladzij
Stubbe		Geertruida Gerarda	A	1

--------------------------

inv	wijk	blad_start	blad_eind	huis_start	huis_eind
7466	A	1	339	1	350
7467	A	339	647	350	701
7468	A	647	1165	701	1233
7469	B	1	568	1	610
```


# xlookup
nieuwe versie van vlookup
```
=XLOOKUP([@bplace];Sheet2!A:A; Sheet2!B:B; 0)
```
als klikbare links
```
=HYPERLINK(XLOOKUP([@Bestandsnaam]&".jpg"; Links!B:B;Links!E:E; "x" ))
```

# Serial Communication in Excel 97
```
MSCOMM32.OCX
https://www.ocxme.com/files/mscomm32_ocx
Install the .OCX:  Place 32-bit OCXs in the '/Windows/SysWOW64' directory and 64-bit OCXs in the '/Windows/System32' directory.
regsvr32 mscomm32.ocx

[HKEY_CLASSES_ROOT\Licenses\4250E830-6AC2-11cf-8ADB-00AA00C00905]
@="kjljvjjjoquqmjjjvpqqkqmqykypoqjquoun"

and restart excel 97
```


# save python dict as Excel 97 file
```python
import xlwt

# Gegeven waarden
values = {
    'B1': '=SUM(C2:C6)',
    'C1': 'Resultaten',
    'C2': 5,
    'C3': 4,
    'C4': 3,
    'C5': 8,
    'C6': 12
}

workbook = xlwt.Workbook()
worksheet = workbook.add_sheet('Sheet1')

for cell, value in values.items():
    col = ord(cell[0]) - 65
    row = int(cell[1:]) - 1
    if isinstance(value, str) and value.startswith('='):
        worksheet.write(row, col, xlwt.Formula(value[1:]))  # Verwijder '=' voor xlwt Formula
    else:
        worksheet.write(row, col, value)

filename = 'output.xls'
workbook.save(filename)

```

# returns true, when an error occurs or when B2 is not 1 higher than B1
```vbscript
=IFERROR(VALUE(B2)<>VALUE(B1)+1, TRUE)
```

# bewerk huidige cell
```
Control + U
```

# freeze row / set row as header
View > Freeze Panes > Freeze Top Row
