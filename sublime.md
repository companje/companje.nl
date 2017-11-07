---
title: =========SublimeText2=========
---
A light-weight full feature text/code editor for OSX, Windows and Linux

=====build system=====
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

=====Disable Hex view for binary files====
Add to settings:
  "enable_hexadecimal_encoding": false

=====On Linux=====
* Alt+O: toggle Header/Source
* Ctrl+P: jump to anything

=====Keyboard shortcuts=====
* [[https://www.sublimetext.com/docs/2/multiple_selection_with_the_keyboard.html|multiple select with keyboard]]
* Cmd+R: Search function within a file
* Cmd+T: open file by fuzzy search

=====shortcut for reindent=====
in Preferences->User
  [ 
    { "keys": ["super+alt+enter"], "command": "reindent" , "args": { "single_line": false } }
  ]
  
=====don't open new window=====
In Preferences->Settings User change add:
<code>
    "open_files_in_new_window": false,
</code>

=====tips=====
* install [[http://wbond.net/sublime_packages/package_control/installation|package control]]

=====formatting code=====
* [[https://github.com/SublimeText/Tag|Tag package]]
* [[https://github.com/victorporof/Sublime-HTMLPrettify|HTML-prettify]] (needs [[http://nodejs.org/#download|NodeJS]])

=====use sublime from terminal in osx=====
See [[http://www.sublimetext.com/docs/2/osx_command_line.html|this page]]
```
ln -s "/Applications/Sublime Text.app/Contents/SharedSupport/bin/subl" ~/bin/subl
```

=====compile c++ files=====
see https://gist.github.com/1566100

=====Splitting the Selection into Lines=====
Select a block of lines, and then split it into many selections, one per line, using Ctrl+Shift+L, or Command+Shift+L on OS X. [[http://www.sublimetext.com/docs/2/multiple_selection_with_the_keyboard.html|source]]

=====Build using makefile and getting this error: No targets specified and no makefile found. Stop.=====
the current directory might be wrong.

=====openscad=====
see [[openscad]]