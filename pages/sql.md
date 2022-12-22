# date field condition
```sql
select * from TABLE where dt >= DATE '2022-12-22'
```

# rownum (oracle)
```sql
where ROWNUM<5000
```

# select case select
```sql
case when MY_FUNC(persoon.id, 'ROL')='Bruid' 
     then concat('  ',persoon.beschrijving)
     else persoon.beschrijving end "Persoon"
```       

# for every record replace part of the value
```sql
UPDATE adressen
SET straat = REPLACE(straat, 'https://data.kkg.kadaster.nl/id/openbareRuimte/', '')
```

# regexp_like
```sql
regexp_like(beschrijving, '^1221-1|^1221-2|^1221-3|^1221|^1232|^1394|^1395|^1396|^1397|^463|^481')
```

# regex_substr
```sql
REGEXP_SUBSTR(besch.beschrijving, '(\()(\d*)\)')
```

# regexp_replace
```sql
regexp_replace(besch.beschrijving, 'xxx', '')
```

# regexp_replace with substr / groups
```sql
regexp_replace(REGEXP_SUBSTR(besch.beschrijving, '(\()(\d*)\)'), '\(|\)','') "AantalFotos"
```
