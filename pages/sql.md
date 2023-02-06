# recursive title lookup for titles
```sql
SELECT LISTAGG(a.title, '...')
FROM apples a
CONNECT BY PRIOR parent_id = id
START WITH id=123123
ORDER BY level DESC
```

or with a subselect to get apple titles from another table:
```sql
SELECT LISTAGG((SELECT title FROM apple_descriptions b WHERE b.id=a.id),' ... ')
FROM apples a
CONNECT BY PRIOR parent_id = id
START WITH id=123123
ORDER BY level DESC
```

# oracle version
```sql
select * from v$version;
```

# changes on a certain day
```sql
where aew.dt >= DATE '2023-01-05' and  aew.dt < DATE '2023-01-06'
```

# ignore &'s in sql values
```sql
set define off
```

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
