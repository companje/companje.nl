---
title: FITC 2008 Amsterdam
---

=====Dinsdag 26-2-2008====

====Koen De Weggheieire over BitmapData, Matrices en Filters====
* http://www.newmovieclip.com
 a b tx
 c d ty
 u v w
 * a en d voor scale
 * b en c voor skew
 * tx en ty voor translation
 * rotation is a combination of a,b,c en d:
* Matrix.createBox(scaleX, scaleY, rotation, tx, ty);
* Matrix.rotate, scale, translate
* Transform object or object.transform.matrix = matrix
* flash.geom.ColorTransform
* greenMultiplier, colortransformobject.blueMuliplier=...
* ColorMatrixFilter 4x5 matrix: R G B A Offset x R G B A
* 0xFF123456, R=(1*0x12) + (0*0x34) + (0*0x56) + (0*0xFF) + 120
* ConvolutionFilter combines pixel data in a bitmap with data from neighboring pixels. full control at pixel level.
* Convolution Kernel (3x3 matrix). je legt de kernal matrix over een pixel heen. dus op de pixel zelf en z'n 8 neighbours (in het geval van 3x3). Kan ook groter. De mexicanHat bijvoorbeeld wat een edge detection met dikke lijnen geeft.
* Veel research gedaan al op convolution filters. Er zijn bijv. smooth, high pass filters, egdes, blur, 5 sharp, 9 shart, extrude, embos.
* Eigenlijk wel te maken met 'game of life' zo'n convolution filter.
* Je kunt BitmapData niet meteen opnemen in de displaylist. Je stopt de BitmapData in een Bitmap object en dan doe je addChild.
* copying pixes: clone, copyPixels, copyChannel, draw
* other interesting methods: applyFilter, colorTransform compare, threshold
* DispacementMapFilter. Nodig: source bitmap + dispacement map. Op basis van de kleur binnen de displace map worden de pixels uit de source verschoven over x of y. 
* DisplacementMapFilterMode.WRAP om te repeaten.
* Binnen de pixels van een displacemaps wordt gekeken naar 2 kleurenkanalen. Bijv. rood en groen. De roodwaarde wordt bijv. gebruikt voor X en de groen voor Y. bijv. roodwaarde 0 betekent 128 naar links de kleurwaarde ophalen? Bij roodwaarde 128 gebruikt verandert er niks. 255 gaat de pixel 128 pixels naar rechts.

=====Maandag 25-2-2008=====

====Joa Ebert over Hydra====
* Werkt aan (en met?) popforge, WiiFlash, ImageProcessing, FlashMate, AS3C, AS3Doc, Speedcoding
* Hydra allows you to manipulate pictures on the graphics card (in Flash en AfterEffect)
* Hydra is nog niet gereleased. Wanneer dan?
* What is a pixel? Not a square, not a dot. More like an abstract point. Can carry various information CMYK/RGB/(Alpha ook toch?), ...
a pixel on the screen does not move. movement is een illusion by setting the color value.
* PixelShader is a program written in a shader language that is executed on the graphics hardware.  Part of the GPU to save processing time on the CPU.
* The unit on the hardware executing the shader is also called pixel shader.
* Common shader languages are currently HLSL (3dfx) and GLSL (opengl).
* !!!!!!!!!!!!!!!!!!!!!!!!!!! Tip: Download Shader Designer voor OpenGL of het alternatief voor 3dfx.
* Hydra Shader Language helps to forget: red<<16 | green << 8 | blue  :-) 
* Colors are used normalized. 0..1
* HSL (Hydra Shader Language) is very common to GLSL.
* Belangrijk: het is limited, strict and type-safe.
* Identity shader doet niks:  kernal Identity { void evaluatePixel(in image4 src, out pixel4 dst) { dst - sampleNearest(src, outCoord()); )
* sampleLinear vs sampleNearest
* Belangrijk: You do not place a pixel - you take a pixel.
* Hydra is compiled using LLVM architecture. Low level compiler. Moeite waard om eens in te duiken volgens Joa.
* AIF Runtime has nothing to do with the Flash Runtime.
* Het werk ook als je gfx card geen shaders ondersteund maar dan is het je cpu die het overneemt.
* ondersteund geen loops en geen if-then-else. maakt het niet al te makkelijk om te gebruiken. Daarom gaan ze het er wel inbouwen.
* Heeft geen random functie (nog)
* Joa gebruikt de grafische kaart ook om supersnelle audio bewerkingen te doen.
* Joa's blog: http://blog.je2050.de
* Voorbeeld zonder Hydra van iets waar je wel hydra voor zou willen gebruiken: http://www.bit-101.com/blog/?p=1167
 parameter float width <
  minValue: 1.0;
  maxValue: 2288.0;
  defaultValue 1024.0;
 >
* adobe image foundation toolkit technology preview: http://labs.adobe.com/wiki/index.php/AIF_Toolkit
* parameter pixel4 multiplier; dan verschijnt multiplier rechts in beld met 4 sliders om de waarden van de pixel4 te veranderen.
* color.rgba * multiplier.abgr
* let op dat je wanneer je bijv. minValue: 1 zegt dat 1 dan een integer is en niet een float. Je moet 1.0 zeggen voor een float net als in C.
* labs.polygonal.de
* http://www.je2050.de/imageprocessing/

====Tali Krakowsky ====
* Super coole presentatie met heel veel inspirererende (media) kunst en architectuur.

====Particles in Flash, Seb Dee Lisle====
* http://www.sebleedelisle.com/
* Het werkt heel fijn om eigenschappen zoals drag/friction, shrink, gravity, fade, spin in te stellen die dan vervolgens worden toegepast op de snelheid etc.
* particle.drag voor snelheid
* particle.shrink voor scale.
* particle.gravity voor snelheid verticaal
* particle.fade voor alpha
* particle.spin voor rotatie
* bij iedere particle.update() worden die waarden toegepast bijv. snelheid van de huidige waarde. Dus niet op de positie maar op de snelheid.
* BlurFilter op container plaatsen. Niet op de afzonderlijke particles. Zie voorbeeld Particles62.fla. this.filters = [new BlurFilter(8,8,1)]; Wel in een macht van 2 voor drastische performance verbetering.
* Recyclen van Particles is heel veel sneller dan nieuwe genereren.
* Slim: Hij gebruikt atan2 om een vonkjesparticle te draaien in de richting waarin ie 
clip.rotation = Math.atan2(yVel,xVel)*RadToDeg
* bmData.draw(this);  om de huidige stand van de movieclip in een bitmap te stoppen

====Renacsent, Joost Korngold====
* Blijft erg vet wat ie allemaal maakt! http://www.renascent.nl

==== Data Visualization with Flex and Air ====
* Door Nicolas Lierman van Boulevart uit Antwerpen
* FlexLib: http://code.google.com/p/flexlib
* TreeMap component: http://zeuslabs.us
* canDropLabels property op een HorizontalAxisRenderer zorgt dat als het de labels rond de assen niet passen dat ie d'r een paar weglaat. bijv: Januari ... Juni ... December
* charting backgroundElements
* public class PieChartBackdrop extends ChartElement implements IChartElement2
* mx:HTML om een htmlpagina met bijv. Google Maps
* Je kunt zo super simpel door de DOMTree heen lopen via het mx:HTML object: _dom = htmlView.htmlLoader.window; _dom.setPosition waarbij setPosition een gewone javascript functie is.
* Scheduling component binnen FlexLib!
* Ik was even vergeten hoe fijn de native drag en drop functies binnen Air ook al weer zijn. Zowel files maar ook TEXT_FORMAT zodat je rechtstreeks vanuit je kalender een snippet kunt slepen naar je Air app.
* Nicolas: The guy who stands up for PDF in Air ;-)
* Binnen de mx:HTML kun je dus ook gewoon een PDF laden. Vet.

==== Paperworld3D ====
* Goeie plannen maar er moet nog veel gebeuren wil het een echt massive online multiplayer game platform worden.

==== Adobe Keynote ====
* Vandaag (3 uur geleden) zijn Flex 3 en AIR 1.0 gereleased.
* Adobe Air Marketplace Beta is een site met veel AIR apps.
* Vanuit het Flex menu een nieuw menuitem: Create Application From Database
* Toch ook wel even in modules duiken van flex. Soort loadMovie... Serge liet 4 manieren zien om je Flex app honderden kilobytes kleiner te maken.
* import ArtWork kijkt naar folder naar de afbeeldingen en als je die op de juiste manier benoemd hebt zoals Button_upSkin.png dan kan ie op basis daarvan automatisch een CSS maken en je hele app skinnen. Vanuit Photoshop makkelijk slices opslaan for the web.
* H264 support, HD video up to 1080px in Fash 9. Fullscreen hw scaling
* Astro en Diesel... 
* Flash Player 10? nog ff niet... :-) z-axis en wat perspectief dingen. Maar geen echte 3D nog.  Wel support voor pixelshaders. (Hydra Kit). 
* Meer over Hydra Kit morgen in FlashNow FlashFuture session hier op het festival.
* Hele goeie zichtbare tween/animaties maken. Object based animation model. Minder timeline achter maar meer volgens paths/grafiekjes
* 30onair.com nieuwe site van Ted Patrick.

=====Zondag 24-2-2008, Papervision3D workshop=====
**Notes by [[friends:Ralph Kok]].**

=== Tim Knip ===
frustrum camera:
* define near plane, far plane, fov (viewing angle in degrees)
* only renders objects within the viewing frustrum

* DAE describes 3d model
* use model = new DAE(); model.load("pathToDAEFile");
* listen for loading complete with Event.COMPLETE event
* contains joints which are automatically created as DisplayObject3D instances
* contains animation description per joint
* if you want animation, pass 'true' in the constructor of Scene3D
* no implementation yet for control over DAE-defined animation
* approach joint through model.getChildByName("nameAsSetInDAEFile");
* as joints are just DisplayObject3D objects, you can also just animate yourself with for example Tweener
* when using animation defined in your 3D application, export with paper matrices on!
* Do not use Blender for complex objects; export to collada does not work very well
* Don't use complex, intricate, nested skinning

=== Carlos Ulloa ===   
Performance:
* framerate: rather have lower framerate than jumpy framerate
* stage quality: higher == slower
* bitmap smoothing: on == slower
* polygon count: more == slower
* polygon size: bigger == slower
* polygon overlap: more == slower
* animated materials: they update, so complexity slows down, use cacheAsBitmap
* Flash player automatically applies mip mapping
* keep texture sizes a power of two (384 also works, which is 128 + 256!!!)
* Group objects in a container object, manipulate just the container

Change registration point:
* move object into a new empty object with an offset
* Traverse custom array to manipulate children, in stead of looping with getChild
* Create all objects at once, keep them visible false until they need to show

BRILJANT!!!:
Van dynamische naar fixed camera:
* variable userControl:Number tussen 0 en 1
* vermenigvuldig userControl met mouseX en mouseY als je de camera positie update
* tween userControl naar 0 of 1 voor een geleidelijke overgang

=== John Grden ===
* viewport
* scene
* camera
* BasicRenderEngine

Camera:
* target camera always looks at target (Camera3D)
* free camera extends DisplayObject3D (FreeCamera3D)
* DisplayObject3D: lookAt method makes it rotate towards other object. you can have stuff follow other, non visible, stuff to create an illusion of one object knowing where another object is in the scene.

* Higher res geometry (more fragments) increases quality, precision helps cutting processor weight

chase cam example:
1. move object forward
2. move cam to same location
3. move camera back a bit (moveBack(350))
4. move camera up

yaw, pitch and roll: z is kept as depth into the screen
yaw rotates around y axis, pitch around x axis, roll around z axis
is additive, so to set to a specific value, use rotationX/rotationY/rotationZ

* Geom objects available: Cone, Cube, Cylinder, Plane, Sphere

DisplayObject3D extends Sprite: "Think of it as the Sprite of Papervision"

Universe demo (primitiveDemos/PrimitivesDemo.fla):
 add earth and moon to 'universe' displayobject and rotate universe, it will rotate all its children right along nest universe-like objects to make a solar system

MaterialObject3D is base class for all materials in PV3D
MovieMaterial: use MC with timeline animation and map that onto 3D object as a material, uses Sprite/MovieClip(/DisplayObject?) instance as material

materialsDemo/MaterialsDemo.fla:
use any object in library with linkage, or any on stage object as material

if you want alpha, use alpha property of the material, not of the DisplayObject3D
Using MC material: tell the MC displayobject class about the displayobject and material

Collada: interactive 3d file communication, exports 3d model to xml file, PV3D can parse it

zoom = 11; focus = 100; => shows up as 1 to 1 pixel representation of what you created in 2D

=== Ralph Hauwert ===
* Something can be 'shaded' and a material can HAVE a 'shader'
* A shader modifies how an object looks, without modifying its geometry
plugin for 3D Studio Max exports class that contains the selected shape!

use BasicView to handle simple stuff in PaperVision
* it provides scene, camera and other stuff
* override protected function onRenderTick(event:Event):void

FlatShadeMaterial asks for a light, diffuse and ambient light colors
PointLight3D is the only light object existing in PV3D right now

GouraudMaterial: simplest material that applies lighting smoothing on each triangle
* calculates lighting direction on a vertex by averaging the directions of all connecting faces
* normals are under construction, so material doesn't work on cubes or planes

PhongShader: next step beyond GouraudMaterial
* allows specification of specular value for lighting, the higher the value the narrower the light

EnvMapMaterial: environment map
* wants a lightmap, you can use bitmapdata object for this
* front and back will be lit the same way
* backEnvMap allows specification of a map for the back to prevent this problem

CellMaterial: for cell shading
* parameter 'steps', defines smoothness of color transitions

new ShadedMaterial(material, shader, compositeMode)
* compositeMode

EnvMapShader
* allows for a bumpmap and a specularmap (the latter doesn't work yet right now)

(tag>Flash Flex 3D Tech Adobe Papervision3D)

Overige offtopic notes:
* MAMP is een soort XAMPP die ik nog niet ken.
* Misschien eens een treemap van m'n HD maken maar wel anders dan Sequoiaview
* Ik heb Directory Opus 9 leren kennen. Een extreem goed alternatief voor de Windows Verkenner / Explorer. Beetje Norton Commander achtig. Heel fijn en snel.

~~DISCUSSION~~
