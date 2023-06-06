---
title: iOS
---

# icon sizes
```xml
<icon src="icon-20.png" width="20" height="20" />
<icon src="icon-20@2x.png" width="40" height="40" />
<icon src="icon-24.png" width="24" height="24" />
<icon src="icon-27.5@2x.png" width="55" height="55" />
<icon src="icon-29.png" width="29" height="29" />
<icon src="icon-29@2x.png" width="58" height="58" />
<icon src="icon-40.png" width="40" height="40" />
<icon src="icon-40@2x.png" width="80" height="80" />
<icon src="icon-44@2x.png" width="44" height="44" />
<icon src="icon-50.png" width="50" height="50" />
<icon src="icon-60.png" width="60" height="60" />
<icon src="icon-60@2x.png" width="120" height="120" />
<icon src="icon-72.png" width="72" height="72" />
<icon src="icon-72@2x.png" width="144" height="144" />
<icon src="icon-76.png" width="76" height="76" />
<icon src="icon-76@2x.png" width="152" height="152" />
<icon src="icon-83.5@2x.png" width="167" height="167" />
<icon src="icon-86@2x.png" width="172" height="172" />
<icon src="icon-98@2x.png" width="196" height="196" />
<icon src="icon-1024.png" width="1024" height="1024" />
<icon src="icon.png" width="20" height="20" />
<icon src="icon2x.png" width="20" height="20" />
```

# icon resize script
```bash
convert bron-icon.png -resize 20x20 icon-20.png
convert bron-icon.png -resize 40x40 icon-20@2x.png
convert bron-icon.png -resize 24x24 icon-24.png
convert bron-icon.png -resize 55x55 icon-27.5@2x.png
convert bron-icon.png -resize 29x29 icon-29.png
convert bron-icon.png -resize 58x58 icon-29@2x.png
convert bron-icon.png -resize 40x40 icon-40.png
convert bron-icon.png -resize 80x80 icon-40@2x.png
convert bron-icon.png -resize 44x44 icon-44@2x.png
convert bron-icon.png -resize 50x50 icon-50.png
convert bron-icon.png -resize 60x60 icon-60.png
convert bron-icon.png -resize 120x120 icon-60@2x.png
convert bron-icon.png -resize 72x72 icon-72.png
convert bron-icon.png -resize 144x144 icon-72@2x.png
convert bron-icon.png -resize 76x76 icon-76.png
convert bron-icon.png -resize 152x152 icon-76@2x.png
convert bron-icon.png -resize 167x167 icon-83.5@2x.png
convert bron-icon.png -resize 172x172 icon-86@2x.png
convert bron-icon.png -resize 196x196 icon-98@2x.png
convert bron-icon.png -resize 1024x1024 icon-1024.png
convert bron-icon.png -resize 20x20 icon.png
convert bron-icon.png -resize 20x20 icon2x.png
```

# Configuring Web Applications
* https://developer.apple.com/library/content/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html

# maggap - make iOS apps
* https://github.com/MacGapProject/MacGap1

# warning 'Implicit declaration of function'
This was caused by a missing import statement in my fork of org.apache.cordova.wifiinfo. See [[https://github.com/companje/org.apache.cordova.wifiinfo/commit/e416e337e674336013248b0ac493362d23660a36|github]]

# install iOS docs in Dash
First: To install OS X/iOS docsets you need to open Xcode and go to Preferences > Downloads > Documentation.

# get name of currently connected WiFi network
```objc
  CFArrayRef myArray = CNCopySupportedInterfaces();
  CFDictionaryRef myDict = CNCopyCurrentNetworkInfo(CFArrayGetValueAtIndex(myArray, 0));
  NSDictionary *ssidList = (__bridge NSDictionary*)myDict;
  NSString *SSID = [ssidList valueForKey:@"SSID"];
  NSLog(@"%@",SSID);
```

# open Settings from your app
  [[UIApplication sharedApplication] openURL:[NSURL URLWithString:@"prefs:root=WIFI"]];
  [[UIApplication sharedApplication] openURL:[NSURL URLWithString:UIApplicationOpenSettingsURLString]];
* http://stackoverflow.com/questions/8246070/ios-launching-settings-restrictions-url-scheme  
* as of iOS8 you can open the built-in Settings app with

```objc
NSURL *url = [NSURL URLWithString:UIApplicationOpenSettingsURLString];
if ([[UIApplication sharedApplication] canOpenURL:url]) {
   [[UIApplication sharedApplication] openURL:url];
}
```

# Reflector App
* [[http://www.airsquirrels.com/reflector/|via AirPlay het beeld van je telefoon naar je computer streamen]]

# iPhone 6 sensor hacks
* http://www.redmondpie.com/video-demo-shows-off-a-very-cool-iphone-trick-that-you-likely-dont-know-about/

# libobjc.A.dylib`objc_msgSend:
see [[phonegap]]

# URL handling (in .plist)
```plist
    <key>CFBundleURLTypes</key>
    <array>
      <dict>
        <key>CFBundleURLSchemes</key>
        <array>
          <string>doodle3d</string>
        </array>
        <key>CFBundleURLName</key>
        <string>com.foo.xyz</string>
      </dict>
    </array>
```

# file handling (in .plist)
```plist
    <key>UTExportedTypeDeclarations</key>
    <array>
      <dict>
        <key>UTTypeConformsTo</key>
        <array>
          <string>public.plain-text</string>
          <string>public.text</string>
        </array>
        <key>UTTypeDescription</key>
        <string>STL File</string>
        <key>UTTypeIdentifier</key>
        <string>com.doodle3d.stl</string>
        <key>UTTypeTagSpecification</key>
        <dict>
          <key>public.filename-extension</key>
          <string>stl</string>
          <key>public.mime-type</key>
          <string>chemical/x-pdb</string>
        </dict>
      </dict>
    </array>
    <key>CFBundleDocumentTypes</key>
    <array>
      <dict>
        <key>CFBundleTypeIconFiles</key>
        <array>
          <string>Document-molecules-320.png</string>
          <string>Document-molecules-64.png</string>
        </array>
        <key>CFBundleTypeName</key>
        <string>STL file</string>
        <key>CFBundleTypeRole</key>
        <string>Viewer</string>
        <key>LSHandlerRank</key>
        <string>Owner</string>
        <key>LSItemContentTypes</key>
        <array>
          <string>com.doodle3d.stl</string>
        </array>
      </dict>
    </array>
```

# How to obtain Certificate Signing Request
  - [[http://stackoverflow.com/questions/12126496/how-to-obtain-certificate-signing-request|Create a certificate with 'Keychain Access']]
  - [[https://developer.apple.com/account/ios/certificate/certificateCreate.action|Upload the CertificateSigningRequest file to Apple's Dev center]]
  - [[https://developer.apple.com/account/ios/profile/profileDownload.action|Create a mobile provisioning profile]]

# disable select in webpages with css
```css
-webkit-user-select: none;
```

# disable glow effect for home screen icons of webpages
```html
<link rel="apple-touch-icon-precomposed" href="apple-touch-icon-72x72-precomposed.png" />
```
more info: http://mathiasbynens.be/notes/touch-icons

# anti-aliasing on ios with openFrameworks
```c
int main(){
    ofAppiPhoneWindow * iOSWindow = new ofAppiPhoneWindow();
    iOSWindow->enableAntiAliasing(4);
    iOSWindow->enableRetinaSupport();
    ofSetupOpenGL(iOSWindow, 480, 320, OF_FULLSCREEN);
    ofRunApp(new testApp);
}
```

# openFramworks for ios setup info
* http://www.openframeworks.cc/setup/iphone/

# in-app email
* http://blog.mugunthkumar.com/coding/iphone-tutorial-in-app-email/

# in-app purchase
* [[http://stackoverflow.com/questions/11200549/upgrade-free-app-to-paid-with-in-app-purchase|stackoverflow on upgrade-free-app-to-paid-with-in-app-purchase]]
* [[http://www.raywenderlich.com/2797/introduction-to-in-app-purchases|Introduction]]
* [[http://www.raywenderlich.com/21081/introduction-to-in-app-purchases-in-ios-6-tutorial|Introduction IOS6]]
* openFrameworks [[http://forum.openframeworks.cc/index.php?topic=11197.15|ofxInAppProduct]]

# line smoothing
* http://answers.oreilly.com/topic/1669-how-to-render-anti-aliased-lines-with-textures-in-ios-4/

# cross compiling
http://shift.net.nz/2010/09/compiling-freetype-for-iphoneios/

# problem
```file is universal but does not contain a(n) armv7 slice for architecture armv7```
try to remove armv7s from the build settings

# crash logs
```bash
~/Library/Logs/CrashReporter/MobileDevice/
```

# ld: symbol(s) not found for architecture armv7
dit betekent dat er een sourcefile (.m / .mm of .cpp) niet gevonden kan worden of niet wordt mee-gecompiled. Ook al zit deze misschien wel in je projectree.
Oplossing: ga naar je Project settings klik op de juiste Target en ga dan naar ''Compile sources''. Sleep de ontbrekende source file hier naar toe.

# enable redpark serial cable in your iOS project
Be sure to add 'Supported external accessory protocol' = 'com.redpark.hobdb9' to your project's .plist file

# Undefined symbols ...  _OBJC_CLASS_$_EAAccessoryManager
Add ''ExternalAccessory.framework'' to your project's Target Linked Libraries.

# snippets of basic usage of redparkWrapper
```objc
//
//  ViewController.h
//

#import <UIKit/UIKit.h>
#import "redparkWrapper.h"

@interface ViewController : UIViewController <RedparkWrapperDelegate> {

}
- (IBAction)go:(id)sender;

@property (retain) redparkWrapper* rp;
@property (nonatomic, retain) IBOutlet UITextView* res;

@end
```

```objc
//
//  ViewController.m
//

#import "ViewController.h"

@interface ViewController ()

@end

@implementation ViewController

@synthesize rp, res;

- (void)viewDidLoad {
    [super viewDidLoad];
    
    rp = [[redparkWrapper alloc] init];
    [rp setDelegate:self];
    
    [res setText:@"nothing yet"];
}

- (IBAction)go:(id)sender {
    [res setText:@"sending..."];
    [rp sendSerial:@"TEST"];
}

-(void) newMessageAvailable:(NSString*)msg{
    [res setText:@"receiving"];
    [res setText:msg];
}

@end
```

(redpark-serial-cable-redparkwrapper.png?550)
