# see also
* [[/maps]]

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

