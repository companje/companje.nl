---
title: Spotify
---

#  Search API 
[[https://developer.spotify.com/web-api/search-item/|documentation]]
```bash
  curl -X GET "https://api.spotify.com/v1/search?q=Gorki+-+Mia&type=artist,track" -H "Accept: application/json"
```

```bash
grep "spotify:track:" spotify.json | cut -c 16-51
grep "spotify:track:" spotify.json | head -n 1 | cut -c 16-51    # only first result
```

# shell script for playlists
https://gist.github.com/companje/3b229c4eb22a4d8199a5
```bash
function spotify() {
  count=$[$count+1]
 
  id=`curl --silent -G --data-urlencode "q=$1" "https://api.spotify.com/v1/search?&type=artist,track" -H "Accept: application/json" | 
    grep "spotify:track:" |
    head -n 1 | 
    cut -c 16-51`
 
  if [ -z "$id" ] ; then
    >&2 echo -e "[0;31m$1[0m"
  else 
    >&2 echo -e "[0;92m$1[0m"
    echo $id
  fi
}
 
if [ $# -eq 0 ] ; then 
  echo ""
  echo "┌─┐┌─┐┌─┐┌┬┐┬┌─┐┬ ┬";
  echo "└─┐├─┘│ │ │ │├┤ └┬┘";
  echo "└─┘┴  └─┘ ┴ ┴└   ┴ ";
  echo ""
  echo "usage:"
  echo "  spotify songs.txt > output.txt"
  echo "  spotify Artist - Song > output.txt"
  echo ""
  echo ""
  echo "when the script is finished:"
  echo "  1. copy output to clipboard."
  echo "  2. paste in a Spotify playlist"
  echo ""
  exit
fi
 
count=0
 
if [ -f "$1" ]
then
  while read line
  do
    spotify "$line"
  done < $1  
else
  spotify "${*:1}"
fi
```
