# see also
* [[/maps]]

# percelen en gerechten geografisch binnen provincie utrecht of binnen 2km afstand
```sparql
prefix geo: <http://www.opengis.net/ont/geosparql#>
prefix geof: <http://www.opengis.net/def/function/geosparql/>
prefix uom: <http://www.opengis.net/def/uom/OGC/1.0/>
prefix xsd: <http://www.w3.org/2001/XMLSchema#>
prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#>
prefix id: <https://hisgis.hualab.nl/id/>

select distinct ?geoTooltip ?geo ?point ?afstand_m where {
  ?s a ?type .
  VALUES ?type { id:gerecht id:perceel } # alleen percelen en gerechten

  # bind("Point(5.12121 52.091036)"^^geo:wktLiteral AS ?point) # afstand tot punt
  bind("POLYGON ((5.112736806283495 51.88777617992665, 5.0269079605788045 51.85860771503387, 5.026955908456537 51.88191820509294, 4.99623812500091 51.873119191568726, 4.994968055710061 51.90229233350238, 4.973410699441986 51.897250049557194, 4.929475474039446 51.950191449182, 4.877792920155553 51.938030675583256, 4.817805905521931 51.99976688905378, 4.857126556382688 52.00595950798791, 4.84689577765411 52.01818170817921, 4.803523202820685 52.01395615945561, 4.8732004118148415 52.06874539314768, 4.829807903191166 52.06682340284294, 4.825379801010068 52.107087187811686, 4.792345364100413 52.123164139745114, 4.874518480819275 52.13883141526054, 4.8922726201862385 52.161592759132176, 4.802925127414553 52.20102775709673, 4.79457569770201 52.226730665167196, 4.9108051253289435 52.25261396627942, 4.926444447303983 52.27977961481453, 5.014264634308429 52.30367703567619, 5.024381632669335 52.281357446748586, 5.030528417835438 52.28848718128803, 5.065471486614253 52.28519815713901, 5.022886886924668 52.2724834371064, 5.057729908047755 52.23535277902338, 5.021292959948584 52.20188574324998, 5.0462229900694995 52.16601444042287, 5.192585964926101 52.177832945608294, 5.265977690366701 52.28192616764453, 5.335461439568466 52.29021841425957, 5.404643328230816 52.249630477283546, 5.393214979747361 52.22063389950551, 5.441039359507684 52.20569343553966, 5.439875543340798 52.17119745511442, 5.514079390291915 52.135923062822464, 5.459242922467752 52.08022575292875, 5.522654413224604 52.077542117545796, 5.550342587549043 52.105419542474245, 5.55790328582633 52.048865119387315, 5.590030134181171 52.03438706317611, 5.59098955490832 52.00264996016284, 5.626170614785312 51.973844458829014, 5.60593964718888 51.94312478453369, 5.484600633434329 51.98393131803589, 5.321611259114633 51.9549191698971, 5.241165301208637 51.97886896444708, 5.180226656080339 51.967448325528885, 5.112736806283495 51.88777617992665))"^^geo:wktLiteral as ?utrecht) # afstand tot Utrecht polygon

  ?s geo:asWKT ?geo .
  bind (str(?s) as ?geoTooltip) .

  BIND(xsd:decimal(geof:distance(?utrecht, ?geo, uom:metre)) AS ?afstand_m )
  
  FILTER( ?afstand_m <= 2000 )
  # FILTER( ?afstand_m > 0 ) 
  
} limit 10000
```

# Query HISGIS Utrecht <1832 Gerechten
https://data.netwerkdigitaalerfgoed.nl/hetutrechtsarchief/-/queries/Gerechten-HISGIS/
![Screenshot 2024-11-25 at 21 23 50](https://github.com/user-attachments/assets/39738156-17e8-4f14-b302-22dc4e2c8c13)

# Zoeken naar achtergrondkaarten in het nationaalgeoregister
* https://www.nationaalgeoregister.nl/geonetwork/srv/dut/catalog.search#/search 

# Coordinate Systems MapInfo
* https://docs.precisely.com/docs/sftw/stratus/mapinfo_stratus_uploader_guide/en/supported%20projections.pdf

# convert MapInfo TAB (EPSG28992 RD) to geojson (ESP4326 wsg84)
```python
import os
import geopandas as gpd

input_filename = 'input.TAB'
output_filename = 'output.geojson'

gdf = gpd.read_file(input_filename)
gdf = gdf.to_crs(epsg=28992) # first to RD ... needed to prevent weird offset
gdf = gdf.to_crs(epsg=4326) # wgs84
gdf.to_file(output_filename, driver='GeoJSON')
```

# convert MapInfo TAB (force EPSG28992 RD) to ESP4326 wsg84 geojson 
```python
import os
import geopandas as gpd

input_filename = './Utrecht/Zuilen/MapInfo/Zuilen_Opstallen.TAB'
output_filename = 'Zuilen_Opstallen.geojson'

gdf = gpd.read_file(input_filename)
print(gdf.crs)          
print(gdf.head()) 
gdf = gdf.set_crs(epsg=28992, allow_override=True)
print(gdf.crs)
gdf = gdf.to_crs(epsg=4326)
gdf.to_file(output_filename, driver='GeoJSON')
```


# GDAL
* install GDAL on Mac: https://formulae.brew.sh/formula/gdal

# load .TAB file with geopandas
It seems that it also needs the MapInfo .ID, .IND, .DAT, .MAP files. Even for simple TAB files that don't have those.

# geopandas
```python
import geopandas as gpd

gdf = gpd.read_file('data/mapinfo2/Percelen.mif') # dit werkt
gdf.to_file('data/Percelen-MapInfo2.json', driver='GeoJSON') # dit werkt

gdf = gpd.read_file('data/mapinfo/Percelen.TAB') # dit werkt ook
gdf.to_file('data/Percelen-MapInfo1.json', driver='GeoJSON') # dit werkt ook
```

