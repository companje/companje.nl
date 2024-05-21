---
title: Excel
---

# save dict as Excel 97 file
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

# Vul het werkblad met de waarden uit de dictionary
for cell, value in values.items():
    col = ord(cell[0]) - 65  # kolom index
    row = int(cell[1:]) - 1  # rij index
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
