---
title: See [[maps]]
---

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

