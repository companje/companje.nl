---
title: SciTE roeleert de pan uit!
---
Ik heb vandaag ontdekt dat mijn favoriete tekst en sourcecode editor SciTE standaard een scripttaal onderdersteund die lua heet. Een heel simpel taaltje en super krachtig om bijvoorbeeld je eigen macro’s mee te schrijven. SciTE, echt briljant!

Hieronder een zelfgeschreven scriptje om een Code Completion lijstje te tonen met daarin Wiki opmaak commando’s.

=== Dit zet je in SciTEUser.properties: ===

<code>
command.name.1.*=WikiMacro
command.1.*=dofile $(SciteUserHome)/WikiMacro.lua
command.mode.1.*=savebefore:no
command.subsystem.1.*=3
command.shortcut.1.*=Ctrl+Space
</code>

=== En dit in je home directory als WikiMacro.lua: ===

<code lua>
editor.AutoCSeparator = string.byte(';')
editor:UserListShow(12,"Internal Link;H1;H2;H3;External Link")
editor.AutoCSeparator = string.byte(' ')

function OnUserListSelection(tp,r)
   if (tp==12) then
	if      (r=="Internal Link") then tag("[[","]]")
	elseif  (r=="H1")            then tag("==","==")
	elseif  (r=="H2")            then tag("===","===")
	elseif  (r=="H3")            then tag("====","====")
	elseif  (r=="Internal Link") then tag("[","]")
	else print("?")
	end
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
</code>

=== Zie ook: ===
*http://lua-users.org/wiki/UsingLuaWithScite
*http://www.lua.org/about.html



(tag>)


~~DISCUSSION~~
