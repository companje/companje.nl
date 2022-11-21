```
build:
	php index.php
```

# run python, kill excel, reopen excel, move and enlarge window
```makefile
build:
	./maak-overzicht.py
	killall -9 "Microsoft Excel" || true
	open "test.xlsx"

	sleep 2

	osascript \
	-e 'tell application "Microsoft Excel"' \
	-e 'set bounds of front window to {-250, -1000, 1600, 1000}' \
	-e 'end tell'
```
