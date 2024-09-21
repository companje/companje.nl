# Project texture on to surface
* https://www.youtube.com/watch?v=QM2SSExYTaE
* Addon "Import-Export: Import Images as Planes”
* Modifier: Shrinkwrap
* eerst je plane een paar keer subdividen
* Wrap Method: Project
* Negative / Positive
* Offset: bijv 0.03m

# Video Texture 
https://www.youtube.com/watch?v=ZqsDjCVBkiw 

# WASD mode camera
* Shift F

# g = Grab
* G van grab. - X,Y,Z voor welke as

# Emulate numpad
* Blender preferences settings input: Emulate numpad
* Camera view 0, 3 etc

# Viewport shading
om textures te kunnen zien

# toggle between edit/object mode
* TAB

# Add object
* shift-A

# Node Wrangler addon
* install Node Wrangler addon in Preferences
* ctrl+T is Node Wrangler options in Shader Editor

# bevel flat disk / objects
* https://www.youtube.com/watch?v=OmlY9mpSRXI
* Ctrl+A in Object Mode -> Apply Scale -> dan Bevel

# Align Camera with View
* Cmd+Alt+0

# Repos
* https://www.blendswap.com
* https://sandbox.babylonjs.com
* https://polyhaven.com
* https://www.textures.com

# Snap
* Ctrl voor snap

# Apply transform(s)
* Ctrl+a = Apply transform(s) in objectmode

# iSpy tutorial
* https://youtu.be/PSeBh5HdDVs

# Texture mappen van een huisje:
1. eerst huisje tekenen evt met Reference Image (dak maken door subdivide van 2 bovenkant edges te doen. Die omhoog verplaatsen met G Z
2. Image Texture instellen als Base Material voor hele Object
3. U-toets voor UV mapping Menu. Kies voor Smart Unwrap

# Loop Cut
* https://docs.blender.org/manual/en/latest/modeling/meshes/tools/loop.html

# Donut tutorial
* Blender
* 'n' for properties
* Lock Camera to View
* Ctrl+1 = add Subdivide Surface Modifier
* proportional editing (toolbar rechtsboven). wanneer je 1 vertex verplaats gaat de rest eromheen een beetje mee. met scroll kun je de circle of influence beinvloeden.
* edge select. met alt op de edge klikken en dan volgt ie de hele edge verder. bijv. de zijkant van de donut. vervolgens scale'd ie met propotional editing uit om de zijkant van de donut platter te maken.
* alt-Z xray mode om ook de achterkant een object in edit mode te kunnen selecteren.
* toggle orthographic mode met raster icoontje onder de gizmo
* solidify modifier om een doorgesneden donut weer solid te maken. het kan ook een thickness geven
* in edit mode zie je naar de solidify modifier de vertices niet meer. Je kunt de toggle bovenaan bij de modifier uitzetten
* je kunt snapping aanzetten. snap zit rechtsbovenaan. kan zijn dat je je icing nog wel moet subdividen met een modifier die je apply'd.
* ctrl+ en ctrl- extra vertices (of edges).
* h=hide
* alt-h om het weer te tonen
* e=extrude
* sculptmode: 
    * inflate brush. zorg dat je eerst je subdivision modifier applied.
    * grab: in en uitduwen 
    * mask: om andere sculpt tools te gebruiken overal behalve binnen de getekende mask. (select: front faces only)
    * ctrl+I invert the brushed mask
    * mesh filter: for uniform inflation for entire mesh (denk aan de 'pizza shader')
    * mask -> smooth mask om de edge minder erg te maken
* / key is isolation mode / view (sculpt, texture paint, object, edit?). je ziet dan ook linksboven staan dat je 'Local' bent.
* ctrl+p = parent. eerst de child selecteren (icing) daarna (donut) dan ctrl+p
* F3 search functies
* poliigon (let op 2 i's) website van blender guru. gescande models, HDR etc. ook gratis spul
* poliigon addon voor blender
* Ctrl+Space toggle fullscreen
* Geometry nodes (Aflevering 6) (moet je voor naar rechts draggen/scrollen in de mode menu). ook node based.
    * distribute points on faces node (poission disk distribution voor niet controle over botsende dots)
    * join geometry node
    * je die punten nog koppelen aan een mesh bijv allemaal kleine low res UVSpheres.
    * sleep je sphere naar de node editor.  Dan krijg je een object info node.
    * voeg een instance on points node toe
    * verbind objectinfo>geometry naar instance van de  instance on points node.
    * weight painting om te voorkomen dat de instances OVERAL op je surface zitten.. (onderkant van je icing). 
    * vertex group / named attribute etc..
* Numpad period key om in 1 keer te zoomen naar een object uit je collection
* Ctrl+A apply Scale bijv
* shift right click = move 3D cursor
* ctrl B = bevel
* ctrl R = loop cut then scroll up
* change origin/anchor point - select > set origin >  origin to geometry 
* simple deform > apply
* m = move to collection. of nieuwe collection
* shift d = make duplicate
* je kunt een collection slepen naar je geometry node
* bij collection info node 3 boxes ticken. separate childen, reset children, pick instance
* je kunt in je geometry node distribute points node de rotation output verbinden aan de rotation input van instance on points node
* als je de sprinkles roteert moet je het eerst apply'en
* je wil ook nog random rotation per sprinkle
* rotate euler node: geen object maar local
    * dan rotate by: random value node
* multiple values in properties aanpassen is gewoon shift click dus ook bij scale x en y
* ctrl L link materials
* object info node los invoegen om random value in een base color te stoppen
* color ramp node voor gradient
    * gradient kun je ook uit 'constants' (steps) maken
* ctrl shift d duplicate node met connections
* f to create a face
* a select all vertices
* m merge vertices by distance
* shift ~ fly mode wasd
* bij rotate meerdere keren op toggles tussen global en local axis
* je kunt het pivot point scalen. hiermee kun je bepalen hoeveel de camera is ingezoomd bij een animatie. dan hoe je de camera niet te bewegen
* shift n of ctrl shift n om normals van faces te inverten of recalculaten
* shift right click om 3D cursor te plaatsen voordat je een nieuw object plaatst zoals een area light of iets anders. dan komt ie meteen op de goede plek.
* alt g set origin point to 0,0 ?? 
* r r = arcball rotation 
* Ctrl + L om alle faces die verbonden zijn met de geselecteerde face te selecteren. 
