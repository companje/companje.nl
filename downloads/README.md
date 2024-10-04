# Time Bandit flux file for Sanyo MBC-550/555 (Option 1)
write this [bandit.scp](https://github.com/companje/companje.nl/raw/refs/heads/master/downloads/bandit.scp) flux file that was created with Greaseweazle to an empty floppy to play the classic Time Bandit game on your Sanyo MBC-550/555. You need a Greaseweazle device for this: https://github.com/keirf/greaseweazle/
```batch
gw write bandit.scp --tracks="c=0-39:step=2"
```

# TimeBandit Sanyo for Sanyo MBC-550/555.hfe (Option 2)
If you have a Gotek drive with FlashFloppy, place this HFE_v3 file (2,5MB) [0001_TimeBandit_Sanyo_MBC55x.hfe](https://github.com/companje/companje.nl/raw/refs/heads/master/downloads/0001_TimeBandit_Sanyo_MBC55x.hfe) on your Gotek drive to play Time Bandit on your Sanyo MBC-550/555. The flux file created with Greaseweazle was converted to HFE_v3 using [HxC2001](https://github.com/jfdelnero/HxCFloppyEmulator).
