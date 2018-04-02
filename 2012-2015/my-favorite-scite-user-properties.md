---
title: Scite User Properties file
---

```
check.if.already.open=1

open.filter=\
$(all.files)\
All Source|$(source.files)|\

line.margin.visible=1
line.margin.width=1+

position.width=1280
position.height=800

#output.horizontal.size=300

default.file.ext=.txt

find.files=*.c *.cxx *.h *.cpp *.cc *.txt

tabsize=2
indent.size=2
use.tabs=0
#indent.auto=1
indent.automatic=1
indent.opening=0
indent.closing=0
tab.indents=0

```

Onderstaande code kun je toevoegen aan een lua script dat automatisch geladen wordt. Zorg dat 'extman' ingeladen is om gebruik te kunnen maken van de scite_Command functie.
```
scite_Command 'Wiki H1|markup H1|Shift+Ctrl+1'
scite_Command 'Wiki H2|markup H2|Shift+Ctrl+2'
scite_Command 'Wiki H3|markup H3|Shift+Ctrl+3'
scite_Command 'Wiki Link|markup Link|Shift+Ctrl+Space'

function markup(r)
	if (r=="Link") then tag("[[","]]")
	elseif (r=="H1") then tag("==","==")
	elseif (r=="H2") then tag("===","===")
	elseif (r=="H3") then tag("====","====")
	else print("?"..r)
	end
end

function tag(starttag,endtag)
	local txt = editor:GetSelText();
	if string.len(txt) ~= 0 then
		editor:ReplaceSel(starttag..txt..endtag)
	else
		editor:AddText(starttag..endtag)
		editor:GotoPos(editor.CurrentPos-string.len(endtag))
	end
end
```
