* https://www.aseprite.org
* veel overeenkomsten met keys in Photoshop
* shift+S toggle snap to grid
* shift+N new layer
* B brush
* M select
* V move/select, also for auto select layer when the autoselect checkbox is selected
* Cmd+Shift+J layer via cut
* Alt+Shift+arrows move selection
* change brush size: -/=
* alt key for eyedropper tool

# lua script via commandline
```bash
/Applications/Aseprite.app/Contents/MacOS/aseprite -b 8x4-nibble-font.aseprite --script /Users/rick/Library/Application\ Support/Aseprite/scripts/sprite.lua
```

# videos
* https://youtu.be/iWvfaiiVuDI
* https://youtu.be/59Y6OTzNrhk

# save BIN file for Sanyo MBC-555
```lua
local sprite = app.activeSprite
-- local outputFile = "./bitmap.bin"
local filePath = app.fs.filePath(sprite.filename)
local fileName = app.fs.fileTitle(sprite.filename)
local outputFile = filePath .. "/" .. fileName .. "_binary_bitmap.bin"

local file = io.open(outputFile, "wb")
local image = Image(sprite)
local w = image.width
local h = image.height
local i = 0
local x = 0

for i=0, w*h/8-1 do
    local j = 128
    local byte = 0

    for b=0,7 do
        local y = i//(w//8)
        local c = image:getPixel(x,y)
            
        if c~=4294967295 then
            byte = byte | j
        end

        x = (x+1) % w
        j = j//2
    end
    file:write(string.char(byte))
end

file:close()
```
