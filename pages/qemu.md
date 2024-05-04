```bash
# Install MS-DOS 6.22 on MacOS with QEMU
qemu-img create -f qcow msdos.disk 2G  # create image for virtual harddisk
qemu-system-i386 -hda msdos.disk -m 64 -L . -fda dos622_1.img -boot a
```
during install when asked for next diskette: `Ctrl+Alt+2` to go to qemu shell:
```bash
eject floppy0 # or fd0? In case you don't know the name type `info block`.
change floppy0 disk2.img
```
press `Ctrl+Alt+1` to go back to installer

