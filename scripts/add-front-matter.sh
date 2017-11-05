for f in *.md
do
  echo "Processing: $f"

  title=`cat $f | head -n 1 | sed -e 's/======\(.*\)======/\1/' | xargs`
  echo -e "$title\n-------"

  # UNCOMMENT THIS LINE TO REPLACE ======TITLE====== BY ---\ntitle: TITLE\n---\n
  # echo -e "---\ntitle: $title\n---\n$(tail -n +2 $f)" > $f

done