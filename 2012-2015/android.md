---
title: Android
---

==force webgl to be enabled==
  chrome://flags  
  chrome://gpu
enable: #ignore-gpu-blacklist
* https://code.google.com/p/chromium/issues/detail?id=306938

  
==targets==
  android list target -c
  
==disable notification sound on Samsung Galaxy Tab==
  Instellingen -> Apparaat -> Geluid -> Meldingen --> stil
  
==delete app on Samsung Galaxy Tab==
  Instellingen -> Algemeen -> Applicatiebeheer

==adb devices==
  adb devices

==web debugging in chrome browser==
  chrome://inspect/#devices
turn on USB webdebugging on device Settings->DeveloperTools
  
==logging==
  adb logcat
filteren
  adb logcat cordova:D *:S
  adb logcat | grep Cordova

==path==
  export ANDROID_HOME=~/Documents/android-sdk-macosx
  export PATH=$PATH:$ANDROID_HOME/tools:$ANDROID_HOME/platform-tools:$ANDROID_HOME/build-tools/20.0.0

==download/update sdk's==
  tools/android update sdk --no-ui
  
==file handling==
* http://stackoverflow.com/questions/1733195/android-intent-filter-for-a-particular-file-extension
* http://stackoverflow.com/questions/9872450/make-your-app-a-default-app-to-open-a-certain-file

==clear default file launch settings==
Open App Info for app and clear 'launch by default'

==adb shell==
connect to the shell on the device

==adb pull==
copy file from device to computer
  adb pull /data/app/filename.apk localfile.apk
  
==read manifest from apk==
  aapt l -a /localfile.apk
  
==app folders==
  /data/app
  /mnt/asec/
  
==find a file==
  ls -laR | grep filename
  
==app not visible in launcher?==
may be you broke your AndroidManifest.xml. Make sure the following is in there:
<code xml>
<intent-filter android:label="@string/launcher_name">
  <action android:name="android.intent.action.MAIN" />
  <category android:name="android.intent.category.LAUNCHER" />
</intent-filter>
```

==register STL files for open with app==
<code xml>
<intent-filter>
    <action android:name="android.intent.action.VIEW" />
    <category android:name="android.intent.category.DEFAULT" />
    <category android:name="android.intent.category.BROWSABLE" />
    <data android:mimeType="application/vnd.ms-pki.stl" />
    <data android:mimeType="application/sla" />
    <data android:scheme="file" android:mimeType="*/*" android:pathPattern=".*\.stl" />
    <data android:scheme="content" android:mimeType="*/*" android:pathPattern=".*\.stl" />
    <data android:scheme="http" android:mimeType="*/*" android:pathPattern=".*\.stl" />
    <data android:scheme="https" android:mimeType="*/*" android:pathPattern=".*\.stl" />
</intent-filter>
```
