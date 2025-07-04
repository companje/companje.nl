---
title: Processing
---

# nearest neighbour scaling in Processing with P2D!
```java
((PGraphicsOpenGL) g).textureSampling(2);
```

# copy to clipboard
```java
import java.awt.Toolkit;
import java.awt.datatransfer.StringSelection;
//
Toolkit.getDefaultToolkit().getSystemClipboard().setContents(new StringSelection(s), null);
```

# call git pull from Processing
```java
StringList stdout = new StringList();
StringList stderr = new StringList();
int result = exec(stdout, stderr, "git", "-C", sketchPath(), "pull");
println("Exit code: " + result);
println("stdout:");
println(stdout);
println("stderr:");
println(stderr);
```

# Sphere with mouse and rotate to Lat Lon with Apache Math3 library
* https://gist.github.com/companje/67a5261a664e560f4505c20e5cfc5d95

# OpenCV remap in Processing with 16bits grayscale TIFF's
```java
import gab.opencv.*;
import processing.video.*;
import org.opencv.core.*;
import org.opencv.imgproc.Imgproc;
import org.opencv.imgcodecs.Imgcodecs;

Capture video;
PImage img;
OpenCV opencv;
Mat mapX, mapY, remapImg;

void setup() {
  size(640, 480);

  video = new Capture(this, "pipeline:autovideosrc");
  video.start();

  opencv = new OpenCV(this, video);

  mapX = Imgcodecs.imread("/Users/rick/Documents/Processing/remap_test/data/gx800_map_x.tif", Imgcodecs.IMREAD_ANYDEPTH);
  mapY = Imgcodecs.imread("/Users/rick/Documents/Processing/remap_test/data/gx800_map_y.tif", Imgcodecs.IMREAD_ANYDEPTH);

  if (mapX.empty() || mapY.empty()) {
    println("Kon de TIFF bestanden niet laden.");
    exit();
  }

  mapX.convertTo(mapX, CvType.CV_32FC1);
  mapY.convertTo(mapY, CvType.CV_32FC1);
}

void draw() {
  opencv.loadImage(video);
  remapImg = new Mat();
  Imgproc.remap(opencv.getGray(), remapImg, mapX, mapY, Imgproc.INTER_LINEAR);
  opencv.setGray(remapImg);

  image(opencv.getSnapshot(), 0, 0);
}

void captureEvent(Capture c) {
  c.read();
}
```

# IDE adjustments on MacOS with Karabiner-Elements

** right Alt-key same as left Alt-key **
add Simple Modification `right_option > left_option`

** Complex Modifications > Add your own rule **
```json
{
    "description": "Cmd+L to select entire line in Processing IDE",
    "manipulators": [
        {
            "conditions": [
                {
                    "bundle_identifiers": [
                        "^org\\.processing\\.four$"
                    ],
                    "type": "frontmost_application_if"
                }
            ],
            "from": {
                "key_code": "l",
                "modifiers": { "mandatory": ["command"] }
            },
            "to": [
                {
                    "key_code": "left_arrow",
                    "modifiers": ["command"]
                },
                {
                    "key_code": "left_arrow",
                    "modifiers": ["command"]
                },
                {
                    "key_code": "right_arrow",
                    "modifiers": ["shift", "command"]
                }
            ],
            "type": "basic"
        }
    ]
}
```
# synchronised with oscP5
```processing
import oscP5.*;
import netP5.*;
OscP5 osc;
ArrayList<OscMessage> oscMessages = new ArrayList();
ArrayList<Line> lines = new ArrayList();

void setup() {
  osc = new OscP5(this, 12000); //to receive OSC data from driver
}

void draw() {
  lines.clear();
  oscUpdate();
  //... do something with lines
}

void oscEvent(OscMessage message) {
  synchronized(oscMessages) {
    oscMessages.add(message);
  }
}

void oscUpdate() {
  synchronized(oscMessages) {
    for (OscMessage msg : oscMessages) {
      if (msg.addrPattern().equals("drag")) {
        int px = msg.get(0).intValue();
        int py = msg.get(1).intValue();
        int x = msg.get(2).intValue();
        int y = msg.get(3).intValue();
        lines.add(new Line(px, py, x, y));
      }
    }
    oscMessages.clear();
  }
}
```

# draw a circle on the surface of a sphere
```processing
PVector axis = PVector.cross(lens, new PVector(0, 0, -1), null);
float angle = PVector.angleBetween(lens, new PVector(0, 0, 1));
rotate(angle, axis.x, axis.y, axis.z);
translate(0, 0, height/2-3);
circle(0, 0, 100);
```

# Load WorldMap .OBJ with adjusted perspective
```processing
PShape globe;
float w,h,eyeZ;

void setup() {
  fullScreen(P3D);
  
  w = width;
  h = height;
  eyeZ = 1460; // = h/2 + offset
  
  globe = loadShape("1519/159.obj");
  globe.scale(h/2);
  globe.rotateX(PI);
}

void draw() {
  background(0);
  
  perspective(atan(h/2/eyeZ)*2, w/h, eyeZ, eyeZ*2);
  camera(0, 0, -eyeZ, 0, 0, 0, 0, 1, 0);
  scale(-1,1,1);

  rotateY(mouseX*.01); //-frameCount*.0025);
  rotateX(mouseY*.01); //-frameCount*.0025);
  
  shape(globe);
}
```

# PDF
```java
import processing.pdf.*;
PGraphicsPDF pdf;

void setup() {
  println("start");
  size(1748, 2480); //scaling factor = 11,81109799  >>> 1748 / 11,81109799 = 148mm
  pdf = (PGraphicsPDF)beginRecord(PDF, "boek.pdf");

  for (int i=0; i<5; i++) {
    background(random(255), random(255), random(255));
    pdf.nextPage();
  }
  endRecord();
  println("done");
  exit();
}
```

# backface culling
```java
void setup() {
  pgl = (PJOGL) beginPGL();
//...

void draw() {
  pgl.enable( PGL.CULL_FACE );
  pgl.cullFace( PGL.FRONT );
//...
```

# Multiple Zoomable Windows
* https://gist.github.com/companje/727074098eb256b0765f20c288f55477

# loop over HashMap
```java
HashMap<String, PImage> imageUniforms = new HashMap();
//...
for (java.util.Map.Entry me : imageUniforms.entrySet()) {
          String id = (String)me.getKey();
          PImage img = (PImage)me.getValue();
          //...
```

# onderzoeken voor OscMessage tegen crashen tussen thread en main loop
```java
import java.util.concurrent.ConcurrentHashMap;
ConcurrentHashMap<Integer, ...> midiData = new ConcurrentHashMap<Integer, ...>();
```


# scaleInto (resize image into other image nearest neighbour)
```processing
PImage scaleInto(PImage img, PImage img2) { //scale img into img2
  img2.loadPixels();
  img.loadPixels();
  for (int y=0; y<img2.height; y++) {
    for (int x=0; x<img2.width; x++) {
      int xx = int(float(x)/img2.width*img.width);
      int yy = int(float(y)/img2.height*img.height);
      img2.pixels[y*img2.width+x] = img.pixels[yy*img.width+xx];
    }
  }
  img2.updatePixels();
  return img2;
}
```

# mouseWheel
```java
void mouseWheel(MouseEvent event) {
  progress += event.getCount() *.00001;
  progress = constrain(progress, 0, 1);
}

```
# removeIf
```java
lines.removeIf(o -> (((TextLine)o).cell == null)); //remove all lines that are not assigned to a cell
```

# system info / sysinfo
source: from: https://discourse.processing.org/t/processing-in-style-with-java-11/13776/24
```java
println( "__SYS INFO :");
println( "System     : " + System.getProperty("os.name") + "  " + System.getProperty("os.version") + "  " + System.getProperty("os.arch") );
println( "JAVA       : " + System.getProperty("java.home")  + " rev: " +javaVersionName);
//println( System.getProperty("java.class.path") );
//println( "\n" + isGL() + "\n" );
println( "OPENGL     : VENDOR " + PGraphicsOpenGL.OPENGL_VENDOR+" RENDERER " + PGraphicsOpenGL.OPENGL_RENDERER+" VERSION " + PGraphicsOpenGL.OPENGL_VERSION+" GLSL_VERSION: " + PGraphicsOpenGL.GLSL_VERSION);
println( "user.home  : " + System.getProperty("user.home") );
println( "user.dir   : " + System.getProperty("user.dir") );
println( "user.name  : " + System.getProperty("user.name") );
println( "sketchPath : " + sketchPath() );
println( "dataPath   : " + dataPath("") );
println( "dataFile   : " + dataFile("") );
//println( "frameRate  : target "+nf(rset, 0, 1)+" actual "+nf(r, 0, 1));
println( "canvas     : width "+width+" height "+height+" pix "+(width*height));
```

# noSmooth for images / nearest neighbour scaling
```javascript
PImage img;

void setup() {
  size(500,500,P2D);
  img = createImage(127,127,RGB);
  ((PGraphicsOpenGL)g).textureSampling(3);
  img.set(10,10,color(255));
}

void draw() {
  image(img,0,0,width,height);
}
```

# listFiles (listDir)
```java
  File[] files = new File(dataPath(imageFolder.replaceAll("data/",""))).listFiles();
  filenames = new String[files.length];
  for (int i=0; i<filenames.length; i++) {
    filenames[i] = files[i].getName(); 
  }
```

# WordWrap
- https://www.rosettacode.org/wiki/Word_wrap
```java
String word_wrap(String text, int LineWidth) {
  StringTokenizer st=new StringTokenizer(text);
  int SpaceLeft=LineWidth;
  int SpaceWidth=1;
  String result = "";
  while (st.hasMoreTokens()) {
    String word=st.nextToken();
    if ((word.length()+SpaceWidth)>SpaceLeft) {
      result = trim(result) + "\n"+trim(word)+" ";
      SpaceLeft=LineWidth-word.length();
    } else {
      result += trim(word)+" ";
      SpaceLeft-=(word.length()+SpaceWidth);
    }
  }
  return result;
}
```

# Count lines
```java
int count_lines(String text) {
  int lines = 0;
  for (int i=0; i<text.length(); i++) {
    if (text.charAt(i)=='\n')
      lines++;
  }
  return lines+1;
}

```

# listFiles
```java
File[] files = new File(folder).listFiles();
```

# Dithering
* https://tannerhelland.com/2012/12/28/dithering-eleven-algorithms-source-code.html
* https://gist.github.com/companje/86645c4183c9073ceb7639f1c37b7905
* https://ditherit.com/
* 
# Tixy.pde - a processing.org tribute to Martin Kleppe's https://tixy.land
* https://gist.github.com/companje/18342a399492fbec4292effff90ebc35

# join integer array to comma separated string
```java
int p[] = {downX,downY,mouseX-downX,mouseY-downY}; 
println(join(nf(p, 0),","));
```

# exit
```java
System.exit(1)
```

# replaceAll
https://www.javatpoint.com/java-string-replaceall

# shell / exec / wait until complete
```java
//if you want to wait until it's completed, something like this:

 Process p = exec("/usr/bin/say", "waiting until done");
 try {
   int result = p.waitFor();
   println("the process returned " + result);
 } catch (InterruptedException e) { }
``` 
 
# good example background learning with accumulateWeighted
https://github.com/sgjava/install-opencv/blob/master/opencv-java/src/com/codeferm/opencv/MotionDetect.java

# opencv minMaxLoc
```js
MinMaxLocResult minMaxResult = Core.minMaxLoc(opencv.getGray());

Imgproc.threshold(opencv.getGray(), opencv.getGray(), mouseX, (int)minMaxResult.maxVal, Imgproc.THRESH_TOZERO);
```

# opencv subtract
```js
void subtract(Mat mat1, Mat mat2) {
  Mat dst = opencv.imitate(mat1);
  Core.subtract(mat1, mat2, dst);
  dst.assignTo(mat1);
}
```

# Play video file backwards
"(Note that not all video formats support backwards playback. For example, the theora codec does support backward playback, but not so the H264 codec, at least in its current version.)"

# Rotate PImage 180 degrees
Rotating 180 degrees is easy. Just swap the first and last pixel and everything in between.
```js
void rotate180(PImage img) {
  img.loadPixels();
  int wh = img.width * img.height;
  for (int i=0; i<wh/2; i++) {
    color tmp = img.pixels[i];
    img.pixels[i] = img.pixels[wh-i-1];
    img.pixels[wh-i-1] = tmp;
  }
  img.updatePixels();
}
```

# Enable / Disable DEPTH_TEST
```js
hint(DISABLE_DEPTH_TEST);
fill(255, 255, 0);
ellipse(mouseX, mouseY, 10, 10);
hint(ENABLE_DEPTH_TEST);
```

# Disable smoothing for images
```js
hint(DISABLE_TEXTURE_MIPMAPS);
((PGraphicsOpenGL)g).textureSampling(2);
```

# PVector 3D rotation
```js
void applyRotation(PVector src, PVector axis, float angle) {
  PMatrix3D rMat = new PMatrix3D();
  PVector tmp = new PVector();
  rMat.rotate(radians(angle), axis.x, axis.y, axis.z);
  rMat.mult(src, tmp);
  src.set(tmp);
}
```

# convert 16bit gray RAW image to 8 bit RGB png
```js
byte gray[] = loadBytes("/Users/rick/Documents/openFrameworks/of0093/apps/Globe4D/Globe4D/bin/data/maps/hull/terra8M.raw"); 

PImage img = createImage(4096, 2048, RGB);
img.loadPixels();

int j=0;
for (int i = 0; i < gray.length; i+=2) {
  img.pixels[j++] = color(gray[i],gray[i+1],0);
}

img.updatePixels();
img.save("rgb.png");

println("done");
```

# Globe intro in ProcessingJS for Khan Academy 
```js
var planet = getImage("space/planet");
var logoY,sloganX,globeY,webY;

var resetAnimation = function() {
    logoY=-50;
    sloganX=-1000;
    webY=1000;
    globeY=300;
};

resetAnimation();

var draw = function() {
    //drawing a semi transparent rect instead of
    //using background() creates the nice fading effect.
    noStroke();
    fill(128,128,128,50);
    rect(0,0,width,height);
    
    //restart the aimation every 300 frames
    if ((frameCount % 300)===0) {
        resetAnimation();
    }
    
    //animate
    if (logoY<50)        { logoY+=3;    }
    if (sloganX<width/2) { sloganX+=20; }
    if (webY>120)        { webY-=10;    }
    if (globeY>120)      { globeY-=10;  }
    
    //text
    fill(255);
    textAlign(CENTER);
    textFont(createFont("sans-serif",50));
    text("Globe4D",width/2,logoY);
    textFont(createFont("serif",20));
    text("interactive four-dimensional globes",sloganX,90);
    textFont(createFont("sans-serif",15));
    text("www.globe4d.com",width/2,webY);
    
    //draw globe
    fill(255);
    ellipse(200,250,300,140);
    image(planet,100,globeY,220,220);

    //draw pedestal
    stroke(255);
    fill(0);
    rect(95,260,220,190);
    
    //mask ring
    strokeWeight(70); //for a very thick arc
    stroke(255); //make red to see mask
    noFill();
    arc(200,223,260,130,30,150);
    
    //draw thin gray arc for table
    strokeWeight(1);
    stroke(0,0,0,50);
    arc(201,240,299,140,0,180);
};
```

# Monstertje in ProcessingJS voor Khan Academy
```js
var eye = function(cx,cy,eyeX,eyeY) {
    fill(126, 242, 149);
    triangle(eyeX-10,eyeY,eyeX+10,eyeY,cx,cy);
    ellipse(eyeX,eyeY,35,35);
    fill(255);
    stroke(0);
    ellipse(eyeX,eyeY,24,24);
    fill(0);
    ellipse(eyeX,eyeY+2,12,12);
    fill(255);
    noStroke();
    ellipse(eyeX,eyeY+4,4,4);
};

var animate = function(f) {
    return sin(millis()*f); //use sine function for smooth animation
};

var draw = function() {
    background(0);
    
    //cx,cy = center of body
    var cx = width/2+50*animate(0.1);
    var cy = height/2+10*animate(1);

    //feet
    var footY = 10*animate(0.5);
    fill(126, 242, 149);
    triangle(cx,cy,cx+10,cy+100+footY,cx+20,cy+100+footY);
    triangle(cx,cy,cx-10,cy+100-footY,cx-20,cy+100-footY);
    rect(cx+10,cy+90+footY,30,10,20);
    rect(cx-40,cy+90-footY,30,10,20);
   
    //body
    ellipse(cx,cy,100,70);
    ellipse(cx,cy+0,90,90);
    ellipse(cx,cy+10,80,70);
    ellipse(cx,cy+20,70,60);
    ellipse(cx,cy+30,60,50);
    ellipse(cx,cy+40,50,40);
    
    //eyes
    eye(cx,cy,cx-30,cy-70+10*animate(1));
    eye(cx,cy,cx+30,cy-90+20*animate(0.5));
    
    //mouth
    strokeWeight(3);
    stroke(0);
    noFill();
    fill(255);
    var mouthW = 15*abs(animate(0.3))+5;
    var mouthH = 10*abs(animate(0.3))+5;
    ellipse(cx,cy+25,mouthW,mouthH);
    strokeWeight(2);
    noStroke();
    
    //freckles
    fill(0);
    ellipse(cx-2,cy-16,4,4);
    ellipse(cx-8,cy-25,3,3);
    ellipse(cx+8,cy-21,4,4);
    ellipse(cx+18,cy-19,3,3);
    ellipse(cx-19,cy-22,3,3);
    ellipse(cx+13,cy-7,3,4);
    ellipse(cx-16,cy-9,4,4);
};
```

# Starfield 2D perspective 
```js
class Star extends PVector {
  float speed;
  
  Star(float x, float y, float z) {
    super(x, y, z);
    speed = random(.1,5);
  }
}

ArrayList<Star> stars = new ArrayList();

void setup() {
  size(1280, 800); 
  noStroke();
  for (int i=0; i<300; i++) {
    float x = random(-width*2,width*2);
    float y = random(-height*2,height*2);
    float z = random(-1000,0);
    stars.add(new Star(x,y,z));  
  }
}

void draw() {
  background(0);
  
  translate(width/2, height/2);
  for (Star s : stars) {
    float x = s.x * 100/s.z;
    float y = s.y * 100/s.z;
    float d = 1000. / -s.z;
    ellipse(x, y, d, d);
    
    s.z+=s.speed;
    if (s.z>0) s.z=-1000;
  }
}
```

# Mandelbrot set 
```js
// Interactive Mandelbrot Set
// Inspired by Daniel Shiffman's Processing example.
// by Rick Companje - www.companje.nl - 6 december 2009
 
double xmin = -2.5;
double ymin = -2;
double wh = 4;
double downX, downY, startX, startY, startWH;
int maxiterations = 200;
boolean shift=false;
 
void setup() {
  size(300, 300);
  colorMode(HSB, 255);
  loadPixels();
}
 
void mousePressed() {
  downX=mouseX;
  downY=mouseY;
  startX=xmin;
  startY=ymin;
  startWH=wh;
}
 
void keyPressed() {
  if (keyCode==SHIFT) shift=true;
}
 
void keyReleased() {
  if (keyCode==SHIFT) shift=false;
}
 
void mouseDragged() {
  double deltaX=(mouseX-downX)/width;
  double deltaY=(mouseY-downY)/height;
 
  if (!shift) {
    xmin = startX-deltaX*wh;
    ymin = startY-deltaY*wh;
  } 
  else {
    if (wh>10) wh=10;
    if (deltaX>1) deltaX=1;
    wh = startWH-deltaX*wh;
    xmin = startX+deltaX*wh/2;
    ymin = startY+deltaX*wh/2;
  }
}
 
void draw() {
  double xmax = xmin + wh;
  double ymax = ymin + wh;
 
  // Calculate amount we increment x,y for each pixel
  double dx = (xmax-xmin) / width;
  double dy = (ymax-ymin) / height;
 
  double y = ymin;
  for (int j = 0; j < height; j++) {
    double x = xmin;
    for (int i = 0; i < width; i++) {
      double a = x;
      double b = y;
      int n = 0;
      while (n < maxiterations) { 
        double aa = a * a; 
        double bb = b * b; 
        b = 2.0 * a * b + y; 
        a = aa - bb + x; 
        if (aa + bb > 16.0) break;
        n++;
      }
 
      pixels[i+j*width] = (n==maxiterations) ? color(0) : color(n*16 % 255, 255, 255);
 
      x += dx;
    }
    y += dy;
  }
  updatePixels();
}
```
