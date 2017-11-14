---
title: Globe4D
---
Globe4D is an interactive, four-dimensional globe. Its a projection of the Earth's surface on a physical sphere. It shows the historical movement of the continents as its main feature, but it is also capable of displaying all kinds of other geographical data such as climate changes, plant growth, radiation, rainfall, forest fires, seasons, airplane routes, and more.

===== Enhanced Life =====
Globe4D extends the functionality of traditional globes found in many households and schools by allowing people of all ages and backgrounds to learn in an entertaining way about how a planet changes over time.

===== Goal =====
Globe4D is designed to be a simple but highly educational entertaining multi-user device for globe viewing. We want people, especially kids, to learn about the earth, let them realize in a playful and tactile manner how earth changed and still changes over time. On long term (continental drift) as well on short term (e.g.daylight changes).

(en:globe4d:globe4d.jpg|Globe4D, Jurassic 180 million years ago)

===== Innovations =====
Globe4D's main innovation is its method of mapping spatio-temporal geographic data on a physical sphere. It is not a flat representation of a changing planet but a real physical globe featuring hands-on interactivity. All kinds of spherical data can be displayed and interacted with as a result of creating our own flexible software engine. The significance of touch combined with a new perspective on showing geographic data makes using Globe4D both entertaining and highly educational.

===== Vision =====
Flat screens in classrooms and museums are outdated when it comes to learning about the earth. The earth is a sphere, and so is Globe4D. The earth changes over time, and so does Globe4D. You can play on earth, and you can play with Globe4D. You can learn on earth, and you can learn from Globe4D.

Don't put kids behind computers. Put computers behind things!

===== Contributors =====
Nico van Dijk
Hanco Hogenbirk
Danica Mast
Rick Companje
Leiden University, the Netherlands

===== Contact =====
info . (at) . globe4d . (dot) . com

===== Interaction =====
The user can interact with the globe in two ways. First: rotation of the sphere itself. Second: turning a ring around the sphere.

By rotating the sphere the projected image rotates along with the input movement. Turning the ring controls time as the 4th dimension of the globe.

In our installation the user experiences a time-shift of more than 750.000.000 years. You can perfectly see the continental drift during this time-travel!

Of course Globe4D limitation boundaries are not fixed to the earth alone. Live weather images and daylight changes can be projected on the globe as well as climate changes, earthquakes and hurricanes.

You can even think of going to the middle of the earth by zooming in on its crust peeling of the earth as if it is an onion.

Of course Globe4D is not limited to the earth alone. The moon, the sun, mars and any other spherical object can be projected as well.

===== Some technical details =====
The Globe4D concept consists of three parts: the Hardware, the Software and the Data Model.

The hardware is the physical installation which is build out basic materials. A single video projector, placed on the ceiling, covers the visible part of the sphere. Optical sensors are used for registering the rotation of sphere in all directions and another optical sensor is used for tracking the movement of the outer ring. Having the projector above the sphere also makes it possible to walk around the globe without affecting the projection.

The software application for Globe4D was written in C++ and uses OpenGL and OpenGlut for rendering and controlling the 3D animated movies and handling user interaction.

The Data Model was written in C++ and Processing and provides the textures for the animations on the sphere and the text projected around it. It extracts and caches texture sequences from a movie whereby it interpolates embedded elliptical images to texture maps for a sphere.

===== Conclusion and future work =====
The Globe4D software application and Data Model make it possible to project various kinds of educational, scientific or artistic data on a sphere. The combination with the Hardware makes it a direct manipulation device in four dimensions. Globe4D can be used for educational purposes by Museums of Natural History, schools, research institutes and universities.

We encourage you to think of new applications or animations. Ideas and suggestions are very welcome.

Globe4D, the four dimensional direct manipulation device on a physical sphere!

====== Events ======

===== 2008 =====
*[[IPON 2008]] (23/24 jan 2008)
*[[Kshitij 2008]] (India)
*[[InnovAction 2008]] (Italië)
*Lezing in Leiden (16 april 2008)


===== 2007 =====
*[[Echangeur]] 10th anniversary 2007
*[[MuseumN8 2007 in NEMO]]
*[[Lezing op Hogeschool van Amsterdam 2007]]
*[[Lunchlezing Leidsche Flesch 2007]]
*[[Wetenschapsdag 2007]]
*[[Cinekid 2007]]
*[[Todays Art 2007]]
*[[Wired Nextfest 2007]]
*[[ACM Siggraph 2007]]
*[[Laval Virtual 2007]]

===== 2006 =====
*[[Globe4D:ACM Multimedia 2006]]
*[[Globe4D:Kunstroute 2006]]

====== Software ======
Since the first version the Globe4D software has been fully written in C++ using [[OpenGL]] for the graphics. Currently we're working at version 3 of the software. This one will be built on top of [[openFrameworks]]. Version 3 will be released in Februari and be installed at the Globe4D installation in the [[Natuurhistorisch Museum Maastricht|Museum for Natural History in Maastricht]], The Netherlands.

====== Hardware ======
Globe4D is currently at hardware version 3. The first version has been used backstage at [[Naturalis]] Museum for Natural History in Leiden, The Netherlands and was used in the video demonstration show at [[ACM Multimedia 2006]]. Version 2 has been shown on several festivals and conferences in Europe and the United States. Version 3 is the first professional production model which is being sold to museums and shown at festivals all around the world.

(tag>Globe4D)
