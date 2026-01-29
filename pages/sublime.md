---
title: SublimeText
---

# zelf een plug-in maken (bijv voor dubbele regels tellen)
* zie https://gist.github.com/Alex-Just/e9e100f2dc41c5b03827
* in `~/Library/Application Support/Sublime Text/Packages/User` maak `count_duplicates.py`
* in dezelfde map maak `Main.sublime-menu` (voor `menu > Edit > Count Duplicates`)
* in dezelfde map maak ` Default.sublime-commands` (voor `Count Duplicates` in `Command Palette Cmd+Shift+P` )

count_duplicates.py
```python
import itertools

import sublime
import sublime_plugin


class CountDuplicatesCommand(sublime_plugin.TextCommand):
    def run(self, edit):
        v = self.view
        
        result = []
        allcontent = sublime.Region(0, v.size())
        lines = sorted(v.substr(allcontent).split('\n'))

        for key, rows in itertools.groupby(lines):
            result.append((sum(1 for r in rows), key))

        result.sort(key=lambda tup: tup[0], reverse=True)
        output = '\n'.join([str(r[0]) + ' ' + r[1] for r in result])

        self.view.replace(edit, allcontent, output)
```

Default.sublime-commands:
```json
[
  {
    "caption": "Count Duplicates: Count Duplicate Lines",
    "command": "count_duplicates"
  }
]
```
Main.sublime-menu
```json
[
  {
    "id": "edit",
    "children": [
      { "caption": "-" },
      {
        "caption": "Count Duplicate Lines",
        "command": "count_duplicates"
      }
    ]
  }
]
```

# prevent debug info in output window on build
in `run-script.sublime-build`
```
{
	"shell_cmd": "./build.sh",
	"quiet": true
}
```

# scope info
`Option+Cmd+P`

# Select all text between quotes/brackets?
(multiline select until next quote)
```Ctrl+Shift+Space``` !!!!!

# use specific python version for running python script
make a custom `my_build.sublime-build` file in the 'User' folder that can be accessed through 'Browse Packages...'
```json
{
   "cmd": ["/Users/rickcompanje/.pyenv/shims/python3", "$file", ""]
}
```

# config files including build-system OSX
```
escaped: ~/Library/Application\ Support/Sublime\ Text\ 3/Packages/User
not escaped: ~/Library/Application Support/Sublime Text 3/Packages/User
```

# undo on all open files
```lua
len([v.run_command("revert") for v in window.views()])
```

# Javascript Intenter
* install ```formatjs```
* set shortcut in user key bindings: ```{ "keys":["super+shift+r"], "command": "js_format"}```

# subl:// protocol handler
https://support.shotgunsoftware.com/hc/en-us/articles/219031308-Launching-applications-using-custom-browser-protocols

met de Apple Script Editor -> Export as .app:
```applescript
on open location this_URL
  do shell script "~/bin/sublimeTextLauncher.sh '" & this_URL & "'"
end open location
```
Show Package Contents -> add to info.plist:
```xml
<key>CFBundleIdentifier</key>
<string>nl.companje.SublimeTextLauncher</string>
<key>CFBundleURLTypes</key>
<array>
  <dict>
  <key>CFBundleURLName</key>
  <string>SublimeText Launcher</string>
  <key>CFBundleURLSchemes</key>
  <array>
    <string>subl</string>
  </array>
</dict>
</array>
```


# build system
```
{
   "cmd": ["make"],
   "file_regex": "^(..[^:]*):([0-9]+):?([0-9]+)?:? (.*)$",
   "working_dir": "${project_path:${folder:${file_path)}",
   "selector": "source.makefile",
   "variants":
    [
      {
        "name": "Run",
        "cmd": ["make", "run"]
      }
    ]
}
```
* https://github.com/sublimehq/Packages/blob/master/Makefile/Make.sublime-build
* http://docs.sublimetext.info/en/latest/file_processing/build_systems.html

# Disable Hex view for binary filesAdd to settings:
  "enable_hexadecimal_encoding": false

# On Linux
* Alt+O: toggle Header/Source
* Ctrl+P: jump to anything

# Keyboard shortcuts
* [[https://www.sublimetext.com/docs/2/multiple_selection_with_the_keyboard.html|multiple select with keyboard]]
* Cmd+R: Search function within a file
* Cmd+T: open file by fuzzy search

# shortcut for reindent
in Preferences->User
  [ 
    { "keys": ["super+alt+enter"], "command": "reindent" , "args": { "single_line": false } }
  ]
  
# don't open new window
In Preferences->Settings User change add:
```
    "open_files_in_new_window": false,
```

# tips
* install [[http://wbond.net/sublime_packages/package_control/installation|package control]]

# formatting code
* [[https://github.com/SublimeText/Tag|Tag package]]
* [[https://github.com/victorporof/Sublime-HTMLPrettify|HTML-prettify]] (needs [[http://nodejs.org/#download|NodeJS]])

# use sublime from terminal in osx
See [[http://www.sublimetext.com/docs/2/osx_command_line.html|this page]]
```bash
ln -s "/Applications/Sublime Text.app/Contents/SharedSupport/bin/subl" ~/bin/subl
```
and add ~/bin to your path for example:
```bash
# ~/.bash_profile
export PATH=~/bin:$PATH
```

# compile c++ files
see https://gist.github.com/1566100

# Splitting the Selection into Lines
Select a block of lines, and then split it into many selections, one per line, using Ctrl+Shift+L, or Command+Shift+L on OS X. [[http://www.sublimetext.com/docs/2/multiple_selection_with_the_keyboard.html|source]]

# Build using makefile and getting this error: No targets specified and no makefile found. Stop.
the current directory might be wrong.

# openscad
see [[openscad]]
