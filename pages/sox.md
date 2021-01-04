---
title: Sox
---

# Halveren snelheid audio mp3 voor ongedaan maken cassette high speed dubbing
sox ipv ffmpeg

```bash
sox input.mp3 output.mp3 --show-progress speed 0.5
```

# show progress
--show-progress

# remove silence at the end of an mp3
```bash
sox input.mp3 output.mp3 reverse silence 1 0.1 0.1% reverse
```
