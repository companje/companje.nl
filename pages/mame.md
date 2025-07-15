see [sanyo](/sanyo)

# log all excecuted instructions
put this is autostart.txt: `trace log.txt`

# capture movie
```bash
mame ....... -aviwrite screendump.avi
```

# mame debugger lua script console
* https://docs.mamedev.org/luascript/index.html#luascript-console

# save 1MB of memory to file every frame
```lua
function draw(text)
  screen = manager.machine.screens[":screen"]
  cpu = manager.machine.devices[":maincpu"]
  mem = cpu.spaces["program"]
  local file = io.open("memory.bin", "wb")
  file:write(mem:read_range(0, 0xfffff, 8, 1)) --1MB
  file:close()
  
  -- screen:draw_box(20,20,125,80,0xff00ffff,0)
  -- screen:draw_text(40,40,string.format('Stack: $%X', cpu.state["SP"].value))
end

emu.register_frame_done(draw, "frame")
```
