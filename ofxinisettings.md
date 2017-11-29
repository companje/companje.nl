---
title: ofxIniSettings
---
ofxIniSettings - by Rick Companje
openFrameworks  addon for reading and writing .ini files.
This code was developped for [[http://www.globe4d.com| Globe4D ]]

=== Download ===
[[http://github.com/companje/addons/|Download from GitHub]]
 
===  Example Usage === 
<code cpp>
ofxIniSettings ini;
ini.load("settings.ini");

bool a =  ini.get("myBoolean",true);
string b = ini.get("section1.myString",(string)"");
float c = ini.get("section1.myFloat",0.0f);
int d = ini.get("section1.myInteger",0);
int e = ini.get("section1.subsection.myInteger",0);
ofVec3f v = ini.get("section1.myVec3f",ofVec3f(0,0,0));

ini.outputFilename="settings.ini";
ini.set("myBoolean",true);

//you can load an additional ini file to override certain settings by

ini.load("subsettings.ini",false);

//now values from subsettings overwrite existing values with the same name in the same section of settings.ini
```

ini file layout:
<code ini>

;myBoolean is not inside a section but is on root level above the sections
myBoolean=1

[section1]
keyInsideSection1=this is a string value
myString=hello word
myFloat=1.0023424
myInterger=5
myVec3f=.001,.5,0
subsection.myInteger=6

[section1.subsection]
;overwrites subsection.myInteger=6
myInteger=7

[section2]
window=0,0,1024,768

```
