---
title: See [[maps]]
---

# convert MapInfo TAB (EPSG28992 RD) to geojson (ESP4326 wsg84)
```
import os
import geopandas as gpd

input_folder = 'HISGIS-mapinfo/'
output_folder = 'HISGIS-geojson/'

os.makedirs(output_folder, exist_ok=True)

for file_name in os.listdir(input_folder): 
    if file_name.lower().endswith('.tab'):
        try:
            tab_path = os.path.join(input_folder, file_name)
            basename = os.path.splitext(os.path.basename(tab_path))[0]

            gdf = gpd.read_file(tab_path)
            gdf = gdf.to_crs(epsg=28992) # first to RD ... needed to prevent weird offset
            gdf = gdf.to_crs(epsg=4326) # wgs84
            gdf.to_file(output_folder+basename+".geojson", driver='GeoJSON')

        except Exception as e:
            print(e, tab_path)
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

