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

regexp_replace(REGEXP_SUBSTR(besch.beschrijving, '(\()(\d*)\)'), '\(|\)','') "AantalFotos", 
```

