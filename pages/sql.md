# create ID lookup table from >1000 GUIDs
```python
print ("select guid,id from A where 1=0")
for row in csv.DictReader(open("data/spreadsheet.csv",encoding="utf-8-sig"),delimiter=";"):
  guid = row['Guid']        
  print(f"OR guid='{guid}'")
print(";")
```

# query to retrieve archival context + get folder of scans for an arbitrary id
```sql
SELECT
  (select nummer||code from A where id=a.a_id_top) "toegang",
  code||nummer "inventarisnummer", 
  WAARDE(a.id, 'INHOUD') "beschrijving",
  (SELECT bes.padnaam from a_relaties rel join a_bestanden bes on bes.a_id=rel.a_id2 where rel.a_id=a.id and rst_id=54) "pad",
  (SELECT LISTAGG(TRIM(beschrijving), ' > ') WITHIN GROUP (ORDER BY LEVEL DESC) FROM a_b START WITH id = a.a_id CONNECT BY PRIOR a_id = id) AS "context"
  
FROM A a
WHERE regexp_like(WAARDE(a.id,'INHOUD'), '1971|1972|1973')
START WITH id in (3274759,30211990) -- id's van willekeurige rubrieken of items in een willekeurige toegang met willekeurige opbouw.
CONNECT BY PRIOR id = a_id;
```


# random record from a aggregation
```sql
MAX(scn.guid) KEEP (DENSE_RANK FIRST ORDER BY DBMS_RANDOM.VALUE) AS random_sample
```

# insert
```sql
insert into TABEL (a_id,t_id,dtm) values(123,456, systimestamp);
```

# date
```sql
where dt>to_date('01-07-2023','DD-MM-YYYY')
```

# record met wijzigingsdatum vandaag
```sql
where u.dt>=trunc(sysdate)
```

# case / if
```sql
CASE WHEN a IS NOT NULL THEN 'ja' ELSE 'nee' END AS "gescand"
```

# nodig als je meerdere langere teksten aan elkaar wilt plakken met group_concat. staat standaard op 1024
```php
$db->prepare("SET GLOBAL group_concat_max_len=100000;")->execute();
```

# create slug from title
```sql
CONCAT('story/',LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(tv.field_cast_video_title, ' ', '-'), ',', ''), '.', ''), ':', ''), '/', '-'),'''',''),'---','-'),'--','-')))) "url_alias",
```

# via 'CONNECT BY PRIOR' alle 'dieper liggende record' vinden op basis van de ID van een bovenliggend record
```sql
SELECT * FROM appels
START WITH ID = 1305602
CONNECT BY PRIOR ID = ParentID;
```

# order by in a group_concat (mysql)
```sql
select 
  node.nid "ID", 
  #...
  group_concat(rel.field_location_target_id order by rel.delta) "locaties"
from node 
  #...
left join field_revision_field_location rel on rel.entity_id = node.nid
where node.type='series'
group by 
  node.nid,
  #.....
;
```


# recursive title lookup for titles (TRAVERSING!)
```sql
SELECT LISTAGG(a.id, '...')
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

or with a join to retrieve titles from the titles table:
```sql
select listagg(t.title, '...')
from apples a
join apple_titles t on t.id=a.id
connect by prior a.parent_id = a.id
start with a.id=724423
order by level desc
```

order by (within group)
```sql
SELECT LISTAGG(b.title, ' ... ') WITHIN GROUP (ORDER BY level DESC)  "context"
FROM apples a
JOIN apple_titles b ON b.id=a.id
CONNECT BY PRIOR a.parent_id = a.id
START WITH a.id=724423
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
