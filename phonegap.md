---
title: Phonegap
---

=====macgap - make iOS apps=====
* https://github.com/MacGapProject/MacGap1

=====crosswalk=====
* https://www.npmjs.com/package/cordova-android-crosswalk

=====hooks=====
* http://devgirl.org/2013/11/12/three-hooks-your-cordovaphonegap-project-needs/

=====statusbar plugin=====
* http://plugins.cordova.io/#/package/org.apache.cordova.statusbar
* Try out the different statusbar possibilities and read explanation: http://devgirl.org/2014/07/31/phonegap-developers-guid/

```
<preference name="StatusBarOverlaysWebView" value="false"/>
<preference name="StatusBarBackgroundColor" value="#000000"/>
<preference name="StatusBarStyle" value="lightcontent" />
```

=====Bonjour (ios)=====
* https://github.com/rossmartin/BonjourPlugin

=====ZeroConf (android)=====
* only for Android???
* https://github.com/vstirbu/ZeroConf
* [[http://www.tildesoft.com/|Bonjour Browser]]
* add plugin and run this code:
<code javascript>
ZeroConf.list("_3dprinter._tcp.local.", 6000,
  function(success) {
    console.log("list succes");
    console.log(arguments);
    console.log(success);
  },
  function(error) {
    console.log(error);
  }
);  

ZeroConf.watch("_3dprinter._tcp.local.", function(result) {
  console.log('>>>>>>>>>>>>>>>>> _3dprinter._tcp.local.');
  console.log(JSON.stringify(result, null, 4));
  //console.log(util.inspect(service, false, null));
});
```

=====android run=====
  phonegap run android --device

=====angular directive with templateUrl=====
be careful with templateUrl's in angular directives. iPhone (and probably Android) is case-sensitive. So not exactly matching templateUrl's don't show up on iPhone but do show up on OSX!

make sure to delete files in www/ and platforms/.../www/ folder since overwriting file WebLink.html with Weblink.html will keep the same file!

=====Overscroll / webview bounce background color=====
* https://github.com/EddyVerbruggen/iOSWebViewColor-PhoneGap-Plugin
or disable:
```
 <preference name="DisallowOverscroll" value="true" />
 <preference name="webviewbounce" value="false" />
```

in native Objective C code in MainViewController.m
```
#define UIColorFromRGB(rgbValue) [UIColor colorWithRed:((float)((rgbValue & 0xFF0000) >> 16))/255.0 green:((float)((rgbValue & 0xFF00) >> 8))/255.0 blue:((float)(rgbValue & 0xFF))/255.0 alpha:1.0]
- (void)webViewDidFinishLoad:(UIWebView*)theWebView
{
    // Black base color for background matches the native apps
  theWebView.backgroundColor = UIColorFromRGB(0xF1EFF0);
```

=====get rid of 'Started backup to iCloud! Please be careful...'=====
in config.xml:
  <preference name="BackupWebStorage" value="local"/>

=====libobjc.A.dylib`objc_msgSend + 6, name = 'WebThread', stop reason = EXC_BAD_ACCESS=====
* I tried to turn on 'zombies' with Cmd+Alt+R -> diagnostics...... Then use Cmd+I to run the app in Instruments. Choose 'Zombies' as template and press the REC button. It gives the error '''An Objective-C message was sent to a deallocated 'UIViewAnimationState' object (zombie) at address: 0x167a5c60. ''' It seems to be a problem with phonegap: read more: https://groups.google.com/forum/#!topic/phonegap/YyIvESnRVGs
* see this issue for a workaround (update: problem came back): https://github.com/Doodle3D/UltimakerApp/issues/28
* http://www.codza.com/how-to-debug-exc_bad_access-on-iphone

=====splashscreen notation in config.xml=====
Don't use '''gap:''' in front of '''splash''' because that doesn't work!
```
<splash gap:platform="ios" src="assets/screen/ios/Default-568h@2x~iphone.png" width="640" height="1136" />
<splash gap:platform="ios" src="assets/screen/ios/Default-Landscape@2x~ipad.png" width="2048" height="1536" />
<splash gap:platform="ios" src="assets/screen/ios/Default-Landscape~ipad.png" width="1024" height="768" />
<splash gap:platform="ios" src="assets/screen/ios/Default-Portrait@2x~ipad.png" width="1536" height="2048" />
<splash gap:platform="ios" src="assets/screen/ios/Default-Portrait~ipad.png" width="768" height="1024" />
<splash gap:platform="ios" src="assets/screen/ios/Default@2x~iphone.png" width="640" height="960" />
<splash gap:platform="ios" src="assets/screen/ios/Default~iphone.png" width="320" height="480" />
```

=====log verbose=====
  phonegap -d build ios > build.log

=====toolchain info=====
```
sw_vers -productVersion
ios-deploy -V
xcodebuild -version
xcode-select --print-path
gcc --version
lldb --version
```

=====Unable to mount developer disk image=====
no solution yet.
see my issue: https://github.com/phonegap/ios-deploy/issues/73

=====couldn't understand kern.osversion `14.0.0'=====
To solve this I removed ios-deploy. But then I wasn't able to re-install it with npm because the make command failed.

  sudo npm install -g ios-deploy
    gcc -ObjC -g -o ios-deploy -framework Foundation -framework CoreFoundation -framework MobileDevice -F/System/Library/PrivateFrameworks ios-deploy.c
    couldn't understand kern.osversion `14.0.0'
The solution was to disable the llvm-gcc42 compiler installed by macports ('''type gcc''' gave '''/opt/local/bin/gcc'''). And use the Xcode gcc compiler instead.

  sudo port deactivate llvm-gcc42
  type gcc         # result: gcc is hashed (/usr/bin/gcc)
  gcc -v            # result: Configured with: --prefix=/Applications/Xcode.app/Contents/Developer/usr ......
  sudo npm install -g ios-deploy

now it works

=====create a true empty phonegap project=====
* http://stackoverflow.com/questions/24151325/error-project-directory-could-not-be-found-with-phonegap

=====icons and splashscreens=====
* http://docs.build.phonegap.com/en_US/configuring_icons_and_splash.md.html

=====Writing plugins=====
* [[http://cordova.apache.org/docs/en/2.5.0/guide_plugin-development_index.md.html|javascript part]]
* [[http://cordova.apache.org/docs/en/2.5.0/guide_plugin-development_ios_index.md.html#Developing%20a%20Plugin%20on%20iOS|iOS part]]

=====Books=====
* http://phonegap.com/book/

=====Error: CDVPlugin class CDVLogger (pluginName: Console) does not exist.=====
first remove platform/ios folder then:
  cordova platforms add ios
  cordova plugin rm org.apache.cordova.console
  cordova plugin add org.apache.cordova.console
  cordova build
hmm.. en als dat niet werkt dan: plugins map en platforms map verwijderen en dan:
  phonegap local plugin add org.apache.cordova.inappbrowser
  phonegap local plugin add org.apache.cordova.console
  phonegap local plugin add org.apache.cordova.statusbar
  phonegap build ios

=====cordova.js aan het eind van je index.html=====
  <script src="cordova.js"></script>

=====tutorial=====
* http://ccoenraets.github.io/cordova-tutorial/

=====forward console.log to xcode=====
  phonegap local plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-console.git
  phonegap build ios
  
=====stop lldb=====
  killall  lldb
  
=====hangs on (lldb) connect=====
update ios-deploy....?
  npm update -g ios-deploy
  
=====init=====
```
phonegap create MyApp -i com.myapp.app -n MyApp
cd MyApp
phonegap build ios
export ANDROID_HOME=/Users/rick/Documents/android-sdk-macosx/
export PATH=$PATH:$ANDROID_HOME/tools/
export PATH=$PATH:$ANDROID_HOME/platform-tools
phonegap build android
```

=====don't backup to iCloud=====
  <preference name="BackupWebStorage" value="local" />

=====list plugins=====
  phonegap plugin list
  
=====add/remove plugin=====
* http://docs.phonegap.com/en/3.0.0/guide_cli_index.md.html

=====debug=====
http://debug.build.phonegap.com/

=====nice slideshow=====
*http://don.github.io/slides/2013-02-16-snow-mobile/#

=====error Abort trap: 6 ios-deploy=====
error
  .........platforms/ios/cordova/run: line 138: 58950 Abort trap: 6           ios-deploy -d -b "$DEVICE_APP_PATH"
fix:
  npm install -g ios-deploy

=====Run=====
  phonegap run ios
  phonegap build ios
  phonegap remote build ios

=====in app browser=====
   phonegap local plugin add https://git-wip-us.apache.org/repos/asf/cordova-plugin-inappbrowser.git
* [[http://cordova.apache.org/docs/en/3.0.0/cordova_inappbrowser_inappbrowser.md.html|InAppBrowser]]
* [[https://build.phonegap.com/plugins/2|Child Browser]]

=====InAppBrowser=====
<code javascript>
function openInternalBrowser(id) {
  var ref = window.open('http://10.0.0.161', '_blank', 'location=yes,transitionstyle=fliphorizontal');
  ref.addEventListener('loadstart', function(event) {
    if (event.url.indexOf(".stl") > 0) {
      alert(event.url);
      ref.close();
    }
  });
}

function openExternalBrowser(id) {
  var ref = window.open('http://10.0.0.161', '_system', 'location=yes');
}
```

=====phonegap remote build ios - PhoneGap 3.5.0 not supported....=====
Solution from [[http://community.phonegap.com/nitobi/topics/phonegap_3_5_0_not_supported_is_there_a_fix_for_this_or_a_way_around_it|here]]. In your ''www/config.xml'' file add the following:
  <preference name="phonegap-version" value="3.4.0" />

=====certificate help=====
http://docs.build.phonegap.com/en_US/signing_signing-ios.md.html

=====combine gulp & phonegap=====
* http://danielhough.co.uk/blog/gulp-browserify-phonegap-ripple/
* http://blog.dynamicprogrammer.com/2014/05/06/refactoring-the-js-code-structure-part-4.html

=====Phonegap App=====
http://app.phonegap.com/
  phonegap serve
  
=====emulate.phonegap.com =====
http://emulate.phonegap.com/

 
