---
title: Problem booting Vista after installing Ubuntu
---

6-3-2008: Windows Vista did not boot after installing Ubuntu. To get back to Windows I booted from the Vista DVD and got myself a commandprompt. From there I entered:

bootrec /fixmbr
bootrec /fixboot
bootrec /rebuildbcd

This should get Vista to boot. Once booted go to http://www.neosmart.net and download easybcd. With Easybcd you can add Ubuntu to the Vista bootloader.
