
# regex
```basic
Set regex = CreateObject("VBScript.RegExp")
regex.Global = False
'...
```
    
# Play wave file
```basic
Declare Function sndPlaySound32 Lib "winmm.dll" Alias "sndPlaySoundA" (ByVal lpszSoundName As String, ByVal uFlags As Long) As Long

Sub PlayWavFile()
    Call sndPlaySound32("A1.wav", &H1)
End Sub
```
