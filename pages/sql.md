# for every record replace part of the value
```sql
UPDATE adressen
SET straat = REPLACE(straat, 'https://data.kkg.kadaster.nl/id/openbareRuimte/', '')
```
