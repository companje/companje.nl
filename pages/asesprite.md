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
local white = app.pixelColor.rgba(255, 255, 255, 255)
local sprite = app.activeSprite
local filePath = app.fs.filePath(sprite.filename)
local fileName = app.fs.fileTitle(sprite.filename)
if filePath=="" then filePath = "." end

local outputFile = filePath .. "/" .. fileName .. ".bin"
local file = io.open(outputFile, "wb")
local image = Image(sprite)
local w = image.width
local h = image.height

for y = 0, h-1, 4 do
    for x = 0, w-1, 8 do
        for i = 0, 3 do
            local byte = 0
            for b = 0, 7 do
                local c = image:getPixel(x+b, y+i)
                if c == white then
                    byte = byte | (1 << (7-b))
                end
            end
            file:write(string.char(byte))
        end
    end    
end

file:close()
```
