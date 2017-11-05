# sed -i '1i some string' YAML.md

# find . -name '*.test' -exec sh -c '(echo ---\n---;cat {}) > {}.tmp && mv {}.tmp {}' \; -print
# ;echo FOOT

find . -mount -print0 | perl -ne 'INIT{ $/ = "\0"; use File::stat;} chomp; my $s = stat($_); next unless $s; print $s->ctime . "/" . $s->mtime . "/" . $s->atime ."/$_\0"; ' > dates.dat

cat dates.dat |  perl -ne 'INIT{ $/ = "\0";} chomp; m!^([0-9]+)/([0-9]+)/([0-9]+)/(.*)!s or next; my ($ct, $mt, $at, $f) = ($1, $2, $3, $4); utime $at, $mt, $f;'
