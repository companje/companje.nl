---
title: ArcText, text on a circle / arc
---
( http://www.companje.nl/wp-content/uploads/2008/01/arc-text.jpg|ArcText, Text on Arc / circle, Processing / Flash) Tekst op een boog. Niet moeilijk op zich, maar omdat elke letter een andere breedte heeft moest ik om het helemaal perfect te krijgen qua spatiering weer even wat oude goniometri regeltjes boven halen. De truuk is namelijk voordat je een letter print alvast een beetje te roteren met een hoek die je berekent op basis van de halve breedte van de letter. Dan voeg je eventueel nog een beetje tracking toe als je wilt dat de letters dichterbij over verder uit elkaar staan. Dat doe ik relatief t.o.v. het gekozen lettertype en de fontgrootte volgens de 'em' eenheid waarbij 1em de breedte van de letter 'm' is. Vervolgens roteer je weer een halve letterbreedte en begin je opnieuw voor de volgende letter.

Dat met die hoek zie je goed in het bovenste plaatje. Je berekent heel gemakkelijk hoek alpha door de inverse tangens te nemen van de overstaande zijde (halve letterbreedte) gedeeld door de aanliggende zijde (straal van de cirkel).

Klik op het onderstaande screenshot voor een interactieve versie waarbij je met de slidertjes de variabelen kunt instellen.

[[http://www.companje.nl/projects/ArcText/index.html|(http://www.companje.nl/wp-content/uploads/2008/01/screenshot.jpg|ArcText, Text on Arc / circle, Processing / Flash)]]

<code java>
float adjacent=radius;
float opposite=textWidth(letter)/2;
angle += atan(opposite/adjacent);
</code>

*[[http://www.companje.nl/projects/ArcText/index.html|View demo]]
*[[http://www.companje.nl/projects/ArcText/source.html|View source]]
*[[http://www.companje.nl/projects/ArcText/source.pdf|Source as PDF]]

(tag>Math Processing)


~~DISCUSSION~~
