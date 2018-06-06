---
title: openFrameworks
---

# OF 0.10.0 - error clang: error: no such file or directory: '../../../libs/rtAudio/lib/osx/rtAudio.a'
This error happens on OSX with 'Case Sensitive' file system. The file on disk is actually called 'rtaudio.a'

# cannot convert parameter 1 from 'char' to 'LPCWSTR'
[stackoverflow](http://stackoverflow.com/questions/3924926/cannot-convert-parameter-1-from-char-to-lpcwstr): in Visual Studio project properties set 'Character Set' to 'not set'.

# recent problem with ofVideoPlayer
https://github.com/openframeworks/openFrameworks/issues/5114

# cheap tricks video
http://petewerner.blogspot.nl/2014/01/cheap-tricks-interactive-dance-floor.html

# read 16bit raw image into ofImage=
```cpp
ofImage heightmap;
ofFile file("terra8M.raw", ofFile::ReadOnly, true);
ofBuffer raw(file);

int w = 4096, h = 2048, wxh = w*h;    
heightmap.allocate(w, h, OF_IMAGE_COLOR_ALPHA);

int i = 0;
for (int y = 0; y < h; y++) {
  for (int x = 0; x < w; x++) {
    int r = raw.getData()[i++];
    int a = raw.getData()[i++];
    heightmap.setColor(x, y, ofColor(r,0,0,a));
  }
}

heightmap.update();
heightmap.save("output.png");
```

# openFrameworks code style guide=
* https://github.com/openframeworks/openFrameworks/wiki/oF-code-style

# loading 16 bits grayscale images seems to be broken after version 008x
https://github.com/openframeworks/openFrameworks/issues/3249
I modified some code in `putBmpIntoPixels` to be able to load 16 bits grayscale images:

```cpp
	if(sizeof(PixelType)==1 &&
		(FreeImage_GetColorType(bmp) == FIC_PALETTE || FreeImage_GetBPP(bmp) < 8
		||  imgType!=FIT_BITMAP)) {
		if(FreeImage_IsTransparent(bmp)) {
			bmpConverted = FreeImage_ConvertTo32Bits(bmp);
////////////////////////////////////////////
// added this to support 16 bit grayscale images
		} else if (FreeImage_GetBPP(bmp)==16) {                     
			bmpConverted = FreeImage_ConvertToType(bmp,FIT_UINT16); 
////////////////////////////////////////////
		} else {
			bmpConverted = FreeImage_ConvertTo24Bits(bmp);
		}
		bmp = bmpConverted;
```

# enable errors to force checking return values=
```
-Werror=return-type
```

# ofSetupScreenOrtho=
```cpp
//--------------------------------------------------------------
void ofApp::draw(){
	ofSetupScreenOrtho(ofGetWidth(),ofGetHeight(),-1000,1000);
	ofTranslate(mouseX,mouseY);
	ofDrawSphere(ofGetHeight()/2);
}
```

# Projectgenerator on Linux: error projectgenerator not found=
first run `compilePG.sh`

# Projectgenerator on Linux: terminate called after throwing an instance of std::regex_error=
```
Command failed: /bin/sh -c "projectGenerator"  -o"/home/globe4d/Documents/of0092" -a" " -p"linux64" "/home/globe4d/Documents/of0092/apps/myApps/mySketch"
terminate called after throwing an instance of 'std::regex_error'
  what():  regex_error
Aborted (core dumped)
```
solution? https://github.com/openframeworks/openFrameworks/issues/4575

ipv 'test' kan ik beter 'ppa' doen zegt peter. daar staat gcc-4.9 ook bij.
```bash
sudo add-apt-repository ppa:ubuntu-toolchain-r/ppa   # this one worked for me: ppa:ubuntu-toolchain-r/test
sudo apt-get update
sudo apt-get install g++-4.9
sudo apt-get remove g++-4.8
sudo update-alternatives --install /usr/bin/gcc gcc /usr/bin/gcc-4.9 60 --slave /usr/bin/g++ g++ /usr/bin/g++-4.9
gcc -v
```
then run `compilePG.sh` again.

# Logitech C920 with IR blocker removed and red filter added. OpenCV code using BackgroundSubtractorMOG2=
see [[logitech]]
%gist(0ab635af72b7d0ef4421)%

# projectGenerator=
cmdline command:
```
"/Users/rick/Documents/openFrameworks/of0092/projectGenerator-osx/projectGenerator.app/Contents/Resources/app/app/projectGenerator"  -o"/Users/rick/Documents/openFrameworks/of0092" -a"ofxCv,ofxOpenCv" -p"osx" "/Users/rick/Documents/openFrameworks/of0092/apps/myApps/cvBgTest2"
```

# Segmentation Fault 11 with projectGenerator=
I got a Segmentation Fault 11 when I changed the `scripts/templates/osx` Xcode project. I removed ofApp.cpp and ofApp.h but that resulted in a lot of recursive calls in the `xcodeProject.cpp::addInclude` function.
I worked around this by manually removing the xml elements of `ofApp.h` and `ofApp.cpp` in `project.pbxproj` within the `emptyExample.xcodeproject` folder.

# extending ofBaseVideoDraws in of009=
%gist(a006644bad4d187f0003)%

# inherit from ofBaseDrawsVideo class=
In XCode when pressing Cmd+8 then click on Clock icon on bottom you get detailed info about build error message. It shows which functions you need to implement:
(::screen_shot_2016-02-23_at_12.02.44.png?nolink|)

# what means 'const' after method definition?=
When you define a method like this in your class:
```cpp
bool isReady() const
```
It means that it "will return a bool but it will not change the logic state of your object. So this is your getter."
So by supplying const you promise the compiler you won't change any data inside your class instance.


# Member function '...' not viable:=
```
Member function 'drawGlobe' not viable: 'this' argument has type 'const Globe', but function is not marked const
```

???


# Field type '...' is an abstract class=
In openFrameworks 009 the declarations of some ofBase classes has changed. For example when your class inherits from  ofBaseDraws you need to add the `const` term after your function definition:
```cpp
  float getWidth() const { return 640; }
  float getHeight() const { return 480; }
  void draw(float x, float y) const { /*...*/ }
  void draw(float x, float y, float w, float h) const { /*...*/ }
```

# opencv2/opencv.hpp file not found=
When you add `ofxCv` to your openFrameworks project make sure to also add `ofxOpenCv`. Because this adds the `opencv2` folder to your project.

# openFrameworks 009 on ElementaryOS=
* https://forum.openframeworks.cc/t/cannot-install-of-0-9-0-on-elementary-os-freya-ubuntu-14-04/21616

# ArcText (curved text on circular path) with openFrameworks 009=
%gist(2defbb27726017b710e2)%

# make Debug openFrameworks 009=
  make Debug
then:
  make RunDebug
or:
  cd bin
  gdb Project_debug
  r
 
# openframeworks 009=
* installing MSYS2 for openFrameworks 009.
* follow the steps at http://openframeworks.cc/setup/msys2/
  * you might need to set the HOME environment variable to your home folder (C:\Users\YOUR_USERNAME) to be able to access your files from the MSYS2 shell.
  * there's a typo in the line `cd your_oF_directory/scripts/win_cb/msys2`. Remove the win_cb part.
* when the tutorial says: "Open a **MINGW32** shell" it means run the following batch file (from the Start Menu):


# ....
file truncated???


