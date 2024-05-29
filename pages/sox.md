---
title: Sox
---

# play
```bash
play file.mp3
```

# Halveren snelheid audio mp3 voor ongedaan maken cassette high speed dubbing
sox ipv ffmpeg

```bash
sox input.mp3 output.mp3 --show-progress speed 0.5
```

# show progress
```bash
--show-progress
```

# remove silence at the end of an mp3
```bash
sox input.mp3 output.mp3 reverse silence 1 0.1 0.1% reverse
```

# split mp3 based on silence
```bash
sox input.mp3 output-.mp3  silence 1 0.5 1% 1 5.0 1% : newfile : restart
```
