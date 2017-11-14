---
title: Write to COM port using JScript
---

It's quite easy to write to a COM port through JScript. You don't need any ActiveX components if you just 'echo' to a port.

===== The Trick =====
<code javascript>
function comInit(port) { exec("mode "+port+"9600,N,8,1") }
function comWrite(s,port) { exec("echo "+s+" > " + port); }
</code>



===== Complete script =====
<code javascript>
//Rick Companje
//23-12-2007
//
//Scriptje dat via de USB poort de PapierVerscanneraar aan en uit kan zetten.
//Op het moment dat de scanner een nieuw beeld heeft klaar gezet in de queue
//gaat de versnipperaar aan voor 5 seconden.
//De versnipperaar is via een Wiring bordje icm met een solid state relais
//aangesloten via een seriele verbinding via de usb poort op de computer.

var port = "COM3:";   //volledig, met dubbele punt
var baseFolderName = "G:\2007\scans\";    //gebruik geen fwd slashes, alleen dubbele backslases
var queueFolderName = baseFolderName + "queue\"     
var outputFolderName = baseFolderName + "output\"

var fs = new ActiveXObject("Scripting.FileSystemObject");
var shell = new ActiveXObject("WScript.Shell");

var queueFolder = fs.getFolder(queueFolderName);
var count = queueFolder.files.count;

echo("Starting Verscanneraar batch. " + count + " files found yet in: " + queueFolderName);
echo("Waiting...");
	
comInit(port);

while (true) {

	var queueFolder = fs.getFolder(queueFolderName);
	var count = queueFolder.files.count;
	
	if (count>0) {
		echo("NU! found="+count);
		WScript.sleep(1000); //even wachten nog, misschien is ie nog in gebruik.
		comWrite("H",port);
		execQueue();
		WScript.sleep(5000);
		comWrite("L",port);
	}

	WScript.sleep(500);

}

function execQueue() {
	var queueFolder = fs.getFolder(queueFolderName);
		
   for (var fc = new Enumerator(queueFolder.files); !fc.atEnd(); fc.moveNext()) {
	   var newFileName = replaceString(getDateTimeString(new Date()),":",".");
		newFileName += "." + getFileExtension(fc.item().name);
		echo("moving " + fc.item() + " to " + outputFolderName);
		fs.moveFile(fc.item(),outputFolderName+newFileName);
   }

}


function getDateTimeString(d) { d=new Date(d); return getDateString(d) + "  " + getTimeString(d) }
function getDateString(d) { return d.getFullYear() + "-" + nf2(d.getMonth()+1) + "-" + nf2(d.getDate()); }
function getTimeString(d) { return nf2(d.getHours()) + ":" + nf2(d.getMinutes()) + ":" + nf2(d.getSeconds()); }
function nf2(i) { var r="0"+i; return r.substring(r.length-2) }
function echo(s) { WScript.Echo(s) }
function exec(s) { shell.Exec("%comspec% /c " + s);  }
function comInit(port) { exec("mode "+port+"9600,N,8,1") }
function comWrite(s,port) { exec("echo "+s+" > " + port); }
function getTextAfterLastIndexOf(s,needle) { return s.substr(s.lastIndexOf(needle)+1); }
function replaceString(inString,findString,replaceBy) { return inString.replace(new RegExp(findString,"g"),replaceBy); }
function getFileExtension(s) { /*input as string!*/ return getTextAfterLastIndexOf(s,"."); }
</code>

(tag>tech)

See: [[afstuderen:Papierverscanneraar]]
