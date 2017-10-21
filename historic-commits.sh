SRC_FOLDER=attic-combi-flat
DST_FOLDER=.

# exit

rm -rf .git
rm *.txt
git init
git add .gitignore
git add historic-commits.sh
git commit -am "first commit"


a=0

for f in `ls -tr $SRC_FOLDER/*.gz`; do
# f=$SRC_FOLDER/wiring.org.co-io-board-very-slow.1199840910.txt.gz

  a=$(($a+1))

  base=$(basename "${f}" .txt.gz)
  timestamp="${base##*.}"
  title=`echo $base | rev | cut -d "." -f 2- | rev`
  datestr=`date -r $timestamp '+%Y-%m-%d %H:%M:%S'`
  outfile=$DST_FOLDER/"${title}.txt" 
  echo "$a: $title - $datestr ($timestamp) -> $outfile"
  
  gunzip -c "${f}" > $outfile
  touch -t `date -r $timestamp +%Y%m%d%H%M.%S` $outfile
  git add $outfile
  git commit --date="$timestamp" -m "$title"

  # if [ "$a" -gt 10 ]
  # then
  #   break  # Skip entire rest of loop.
  # fi

done
