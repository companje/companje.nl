---
title: Alternatieve Sinus Functie
---
Over het algemeen kost het aanroepen van een sinusfunctie binnen een programmeertaal een hoop clock-cycles. Daarvoor krijg je wel een hele nette benadering van de sinus van een getal. Vaak veel te netjes als het mij vraagt. Zijn er alternatieven voor wanneer je wat minder detail nodig hebt? Ja, en die zijn een stuk sneller. [1].

Wat dacht je van:

  sinX = 1.2732395*x + (0.4052847*x*x) * (x<0?1:-1);

Het is een aardige benadering van de sinusfunctie die op mijn vaste computer wel even 20x zo snel is als de ingebouwde Processing/Java sinus functie.

Wil je nou iets meer detail dan kun je ‘m nog finetunen door met het resultaat van de vorige berekening nog even het volgende uit te halen.

  sinX= .225*((x<0?-1:1)*sinX*sinX-sinX)+sinX;

Die is nog steeds 9x zo snel als de standaard sinus functie.

Ben je benieuwd hoe dit werkt zoek dan even op Taylor reeksen want volgens mij hebben we het oorspronkelijk aan hem te danken.

    Taylor wilde iedere willekeurige functie schrijven als een polynoom.
    Eventueel met graad oneindig, oftewel: als een oneindige reeks.
    De gedachte achter dit polynoom is, dat hij voor het punt x=0 de
    zelfde waarde heeft als f(x), maar ook dezelfde afgeleide en 2e
    afgeleide, enz. [2]

Fijn om te weten is misschien waar die twee getalletjes (1.2732395 en 0.4052847) precies vandaan komen. Waarom weet ik niet maar de eerste staat voor 4/PI en de tweede voor 4/PI2.

Sourcecode:
*Zie [[http://companje.nl/processing/Alternatieve-Sinus-Functie.html|source Alternatieve-Sinus-Functie]]
*Zie [[http://companje.nl/processing/qSin-Color-Wave-Demo.html|source qSin-Color-Wave-Demo]]

Zoals je ziet is voor zoiets als het onderstaande de grove benadering prima voldoende:

(http://www.companje.nl/wp-content/uploads/2007/11/cool-sine-3559.png)

#  Externe links =
*http://home.wanadoo.nl/rule-off/wis/reeksen.htm
*http://lab.polygonal.de/
*http://lab.polygonal.de/wp-content/articles/fast_trig/fastTrig.as

(tag>Math Processing)

~~DISCUSSION~~
