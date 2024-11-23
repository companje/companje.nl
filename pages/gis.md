---
title: See [[maps]]
---

# convert MapInfo TAB (EPSG28992 RD) to geojson (ESP4326 wsg84)
```python
import os
import geopandas as gpd

input_folder = 'input.TAB'
output_filename = 'output.geojson'

gdf = gpd.read_file(tab_path)
gdf = gdf.to_crs(epsg=28992) # first to RD ... needed to prevent weird offset
gdf = gdf.to_crs(epsg=4326) # wgs84
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

