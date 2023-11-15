---
title: Shaders
---
Zie ook [[OpenGL]]

# shader toy
* https://www.shadertoy.com

# vertex shader art
* https://vertexshaderart.com/

# GLSL preview in Atom
https://atom.io/packages/glsl-preview

# texOffset in Processing
"texOffset uniform. This uniform is set automatically by Processing and contains the vector (1/width, 1/height), with width and height being the resolution of the texture. "

# Tutorials
"There are several resources, such as online tutorials and forums, books, and web coding sandboxes (for example Shader Toy, GLSL Sandbox, and Vertex Shader Art), that can be recommended for learning GLSL programming."
* https://processing.org/tutorials/pshader/
* https://www.shadertoy.com/
* http://glslsandbox.com/
* https://www.vertexshaderart.com/

# The Book of Shaders
* https://thebookofshaders.com

# Online NormalMap creator
* http://cpetry.github.io/NormalMap-Online/

# get shader language version
```c
glGetString(GL_SHADING_LANGUAGE_VERSION)
```

```
color = light * mix(texture2D(night, textureCoord), 
   texture2D(day, textureCoord), 
   smoothstep(-0.25, 0.25, dot(N, L)));
```

# Shaders on Mac
* [[http://www.starstonesoftware.com/OpenGL/mac.htm|port 'recent' shaders to 'old GLSL 1.20' for OSX Lion]]

# Links
* http://stackoverflow.com/questions/16984914/cross-fade-between-two-textures-on-a-sphere
* http://www.gamedev.net/topic/594457-calculate-normals-from-a-displacement-map/
* http://www.opengl.org/wiki/Sampler_(GLSL)
* http://poniesandlight.co.uk/notes/creating_normal_maps_from_nasa_depth_data/
* http://data-arts.appspot.com/globe/
* nog uitproberen: http://stackoverflow.com/questions/5281261/generating-a-normal-map-from-a-height-map
* [[http://codeflow.org/entries/2012/aug/02/easy-wireframe-display-with-barycentric-coordinates/|creating a wireframe using barycentric coordinates]]
* [[https://www.opengl.org/discussion_boards/showthread.php/162857-Computing-the-tangent-space-in-the-fragment-shader|computing tangent space in fragment shader]]
* https://github.com/tgfrerer?tab=repositories
* !! http://www.songho.ca/opengl/gl_transform.html#modelview
* http://www.opengl.org/sdk/docs/tutorials/TyphoonLabs/Chapter_3.pdf
* http://www.opengl.org/sdk/docs/tutorials/TyphoonLabs/Chapter_4.pdf
* http://fabiensanglard.net/bumpMapping/index.php
* https://github.com/kalwalt/OFexamples
* [[http://www.iquilezles.org/apps/shadertoy/|Shader Toy!!!!]]
* http://www.yaldex.com/open-gl/ch16lev1sec4.html
* http://www.davidcornette.com/glsl/glsl.html
* [[http://en.wikipedia.org/wiki/GLSL|Wikipedia]]
* http://fabiensanglard.net/bumpMapping/index.php
* http://web.univ-pau.fr/~bjobard/Cours/SIA/TP4.html
* http://www.conitec.net/shaders/ 
* http://idlastro.gsfc.nasa.gov/examples/doc/
* http://en.wikipedia.org/wiki/GLSL
* http://doc.gold.ac.uk/CreativeComputing/creativecomputation/?page_id=1060
* https://sites.google.com/site/ofauckland/examples/7-bumpmap-displacement-shader
* http://www.clockworkcoders.com/oglsl/
* http://companje.nl/wp/earthclouds-alphablending-shader-in-openframeworks/

# What does gl_TexCoord.st mean?
st is a vec2 containing the x,y tex coord. It is exactly the same as xy or rg. It just returns the first two components of a float[4]. The letters are there for semantic reasons.

# ftransform is depricated
Ftransform is equivalent to this:
```glsl
gl_Position = ftransform();
gl_Position = gl_ProjectionMatrix * gl_ModelViewMatrix * gl_Vertex;
```

v will be a coordinate in projection (screen) space.
# Displacement with diffuse light
```c
uniform sampler2D colormap;
uniform sampler2D bumpmap;
varying vec2  TexCoord;
varying vec3 vertex_light_position;
varying vec3 norm;


void main(void) {
   vec3 normal = normalize( norm );
   float diffuse_value = max(dot(normal, vertex_light_position), 0.0);
   gl_FragColor = diffuse_value * texture2D(colormap, TexCoord);
}
```

```c
uniform sampler2D colormap;
uniform sampler2D bumpmap;
varying vec2 TexCoord;
uniform int maxHeight;
varying vec3 vertex_light_position;
varying vec3 norm;

void main(void) {
    TexCoord = gl_MultiTexCoord0.st;
    vec4 bumpColor = texture2D(bumpmap, TexCoord);
    float df = 0.30*bumpColor.x + 0.59*bumpColor.y + 0.11*bumpColor.z;
    vec4 newVertexPos = vec4(gl_Normal * df * float(maxHeight), 0.0) + gl_Vertex;
    gl_FrontColor = gl_Color;
    vertex_light_position = normalize(gl_LightSource[0].position.xyz);
    norm = -1.0 * gl_NormalMatrix * gl_Normal;
    gl_TexCoord[0] = gl_MultiTexCoord0;
    gl_Position = gl_ModelViewProjectionMatrix * newVertexPos;
```

openFrameworks:
```c
#include "testApp.h"
#include "ofxExtras.h"

//--------------------------------------------------------------
void testApp::setup(){
    ofSetWindowShape(1280,800);
    ofSetWindowPosition(0,0);
    ofBackground(0,0,0);
    ofDisableArbTex();
    ofEnableAlphaBlending();
    ofSetFrameRate(60);
    ofSetVerticalSync(true);
    glEnable(GL_DEPTH_TEST);

    colormap.loadImage("earth8k.jpg");
    bumpmap.loadImage("earth-bumps.png");
    //normalmap.loadImage("earth-normals.png");

    quadratic = gluNewQuadric();
    gluQuadricTexture(quadratic, GL_TRUE);
    gluQuadricNormals(quadratic, GLU_SMOOTH);

    shader.load("shaders/displace");
    material.setShininess(200);
}

void testApp::draw(){
    ofSetColor(255);
    light.enable(); 
    light.setPosition(ofGetWidth()-ofGetMouseX(),ofGetHeight()-ofGetMouseY(),200); //ofxGetCenter().x,ofxGetCenter().y,cam.getP);
    ofEnableLighting();
    material.begin();
    cam.begin();

    shader.begin();
    shader.setUniformTexture("colormap", colormap, 1); 
    shader.setUniformTexture("bumpmap", bumpmap, 2);
    shader.setUniform1i("maxHeight",20); //sin(ofGetElapsedTimef())*50-25);
    
    glEnable(GL_DEPTH_TEST);
    glEnable(GL_CULL_FACE);
    glCullFace(GL_FRONT);
    ofScale(-1, 1);
    gluSphere(quadratic, 250, 400, 400);

    shader.end();
    colormap.unbind();
    bumpmap.unbind();
    material.end(); 
    light.disable();
    ofDisableLighting();    
}
```
