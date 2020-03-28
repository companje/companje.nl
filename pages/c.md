---
title: C++
---

see also [[openFrameworks]]
see also [[arduino]]

# Passing values to member classes
main.ino:
<code cpp>
#include "Slider.h"

Slider slider1(2,3);
Slider slider2(4,5);
```

Slider.h:
<code cpp>
#include "Sensor.h"

class Slider {
public:

  Sensor a, b;  

  Slider(int pinA, int pinB) : a(pinA), b(pinB) {
    
  }
};
```

Sensor.h:
<code cpp>
class Sensor {
  public:

    int pin = 0;

    Sensor(int pin) : pin(pin) {

    }
};

```


# union
<code c>
union
{
  uint16_t AX;
  
  struct {
    uint8_t AL;
    uint8_t AH;
  };
};
```

# read & write string
<code c>
//--------------------------------------------------------------
string loadString(string filename) {
    ifstream input(filename.c_str());
    string line;
    getline(input, line);
    return line;
}

//--------------------------------------------------------------
void saveString(string filename, string str) {
    ofstream file(filename.c_str(),ios::out);
    file << str;
    file.close();
}
```

# vies maar wel leuk
<code c>
#include "stdio.h"

int main() {
        10 && printf("hoi");
}
```

# read contents of a file
<code c>
ifstream f("filename.txt",ios::in);
stringstream buf;
buf << f.rdbuf();
string str = buf.str();
```

# popen() as alternative to system()
<code c>
string ofxExecute(string cmd) {
    string result;
    char line[130];
    FILE *fp = popen(cmd.c_str(), "r");
    while (fgets( line, sizeof line, fp)) result += line;
    pclose(fp);
    return result;
}
```
* [[http://www.lix.polytechnique.fr/Labo/Leo.Liberti/public/computing/prog/c/C/FUNCTIONS/popen.html|info]]


# join vector to string
<code cpp>
template<typename T> string join(const vector<T>& vec, string t=",") {
    stringstream ss;
    for (size_t i = 0; i < vec.size(); ++i) {
        if (i!=0) ss << t;
        ss << vec[i];
    }
    return ss.str();
}
```

# ostringstream
<code cpp>
ostringstream s;
s << doodle.vshape;
printf("%s",s.str().c_str());
```

```
//copy(doodle.vshape.begin(),doodle.vshape.end(), ostream_iterator<string>(cout, ","));
 ```


# DEFAULTLIB  warnings with CodeBlocks/MinGW
/DEFAULTLIB is a directive that is Visual Studio specific. It is added by the compiler if source contains
```#pragma comment(linker,"/DEFAULTLIB:something")```
It is meant to be processed by the microsoft linker when creating final executable - microsoft linker understands the directive. MinGW does not understand it
* source: http://bugs.mysql.com/bug.php?id=45318

# sort std::vector
<code c>
bool triggerSortByDate(const Trigger& a, const Trigger& b) {
    return a.triggerDate < b.triggerDate;
}
//usage:
std::sort(myvector.begin(), myvector.end(), triggerSortByDate);
```
(never return -1 as a false)

# loop over std::map
<code c>
for (map<int,Event>::iterator it=events.begin(); it!=events.end(); it++) {
    Event &e = events[it->first];
    //...
}
```

# calling a function in the global namespace from within a class
<code c>
#include <iostream>
#include <string>
using namespace std;

void draw() {
    cout << "test" << endl;
}

class A {
public:
    void draw() { ::draw(); }
};

int main() {
    A a;
    a.draw();
}
```

extra:

<code c>
#include ...
...
void setup() {
    ...
}

void draw() {
    ...
}

int main() {
    class App : public ofBaseApp {
    public:
        void setup() { ::setup(); }
        void draw() { ::draw(); }
    };
    ofSetupOpenGL(new ofAppGlutWindow, 1280, 800, OF_WINDOW);
    ofRunApp(new App);
}
```

# opvragen welke functies een compiled library bevat
nm' in je terminal om van een compiled library (bijv freeimage.a) info opvragen over welke functies er aan te roepen zijn.
zoeken met grep in de output: nm | grep -i "vet"
-i van grep is voor case insensitive

# andere tools
c++filt
otool kijken met welke dynamische libraries jouw applicatie linkt

# nice way of struct initializing
<code c>
typedef struct {
  string name; 
  float latitude;
  float longitude;
} City;

City newyork = { "new york", 40+47/60., -73 + 58/60. };

//cast:
return (ofxLatLon){lat,lon};
```

# good graphics and math stuff
* http://www.iquilezles.org/www/

# foreach macro with reference (and silly endfor)
<code c>
#define foreach(t,v) for(typeof(v.begin()) p=v.begin(); p!=v.end(); p++) { typeof(*p) &t=*p; 
#define endfor }

foreach (t,triangles) {
    foreach (s,ss) {
        cout << t.v << " " << s << endl;
    } endfor
} endfor
```

# foreach macro with pointer p
<code c>
#define foreach(p,v) for(typeof(v.begin()) p=v.begin(); p!=v.end(); p++)
```

# get pointer as a reference to the object it's pointing to
<code c>
#define ref(a,b) &a=*b
```

# typeof to make a reference
<code c>
Triangle t;    
typeof(t) &i = t;
cout << i.v << endl;
```

# goede uitleg over references
http://www.parashift.com/c++-faq-lite/references.html

# std::vector
back() is the same as *end() 

# interessante operator cast
<code c>
ClassName::operator bool() {
  return ....
}
```
hierdoor kun je zeggen: 
<code c>
ClassName instance;
if (instance) { .... }
```
zou ook voor string moeten werken waardoor je op die manier een object naar string kunt casten vergelijkbaar met een toString functie.
