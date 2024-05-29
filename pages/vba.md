
# Play wave file
```vba
Declare Function sndPlaySound32 Lib "winmm.dll" Alias "sndPlaySoundA" (ByVal lpszSoundName As String, ByVal uFlags As Long) As Long

Sub PlayWavFile()
    Call sndPlaySound32("A1.wav", &H1)
End Sub
```
