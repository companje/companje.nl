# move_camera to dome position
```python
from ursina import *

app = Ursina()
window.fullscreen = True

# Dome entity
dome = Entity(
    model='dome-detailed.obj',
    texture='data/earth.jpg',
    scale=1,
)

# Startpositie van de camera
camera.position = (5, 2, -5)
camera.look_at(dome.position)

def move_camera():
    camera.animate_position((0, 5, 0), duration=5, curve=curve.out_expo)
    camera.animate_rotation((90, 0, 0), duration=5, curve=curve.out_expo)
    invoke(camera.look_at, dome.position, delay=5)

invoke(move_camera, delay=1)

app.run()
```
