---
title: Raspbery Pi
---

# Raspberry PI in Virtual Box
* hmm.. https://www.youtube.com/watch?v=CeUDAIPKBGQ


# SSH into Model A
https://www.reddit.com/r/raspberry_pi/comments/2oqs2a/how_can_i_ssh_into_the_a_model/
"By default, the A+ has no way to get onto a network. You need to add a Wifi adapter."

# Raspberri pi internal WiFi
please note that the raspberry pi 3 internal wifi does currently not support the wifi channels 12 and 13

# OS distro for full screen webpage
* https://github.com/guysoft/FullPageOS

Voor Globe4D misschien nog 2 interessante Raspberry Pi alternatieven: 
* [[http://www.cnx-software.com/2014/04/21/solidrun-hummingboard-is-a-raspberry-pi-compatible-board-powered-by-freescale-i-mx6/|solidrun-hummingboard]]
* [[http://www.cnx-software.com/2014/04/20/banana-pi-is-a-raspberry-pi-compatible-board-fitted-with-an-allwinner-a20-soc/|banana-pi]]

# Peter's aantekeningen
* http://kladblok.planb.coop/p/raspberrypi

# GPIO
gpio -g mode 17 out/in
gpio -g write/read 17 1

# PWM
gpio -g mode 18 pwm
gpio -g pwm 18 512

# login
pi
raspberry
