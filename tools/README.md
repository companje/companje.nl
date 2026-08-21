# Image to PIC

`img2sanyo.html` is a standalone browser tool that converts a PNG, JPG/JPEG, or GIF into a `.pic` file with three 1-bit RGB bitplanes. There are no dependencies, build steps, or server-side components: upload this file to a website and open it in a modern browser.

## Usage

1. Open `img2sanyo.html` in a browser.
2. Choose a PNG, JPG/JPEG, or GIF no larger than **640 × 200** pixels.
3. Check the preview. It uses exactly the same 1-bit color conversion as the export.
4. Optionally choose the 4-byte header.
5. Download the `.pic` file.

The download is named `<original-name>-<output-width>x<output-height>.pic`.

## Supported input

- PNG, JPG/JPEG, and GIF.
- At most 640 pixels wide and 200 pixels high, before padding.
- For animated GIFs, the browser processes the decoded image/frame; the tool is not an animation exporter.

## 3-bit RGB

PIC output has one bit per pixel for red, green, and blue. Therefore, only these eight colors can be represented exactly:

| R | G | B | Color |
| --- | --- | --- | --- |
| 0 | 0 | 0 | black |
| 0 | 0 | 1 | blue |
| 0 | 1 | 0 | green |
| 0 | 1 | 1 | cyan |
| 1 | 0 | 0 | red |
| 1 | 0 | 1 | magenta |
| 1 | 1 | 0 | yellow |
| 1 | 1 | 1 | white |

A channel value of `128` or higher becomes `1` on export; lower values become `0`. The web page warns if the input contains RGB values other than `0` or `255`, or contains transparency. The warning shows the number of unique RGB colors and color swatches (up to 64). Suitable preprocessing can be done with [ditherit.com](https://ditherit.com/).

The warning does not block the download: the tool still quantizes the image using the threshold above.

## Dimensions and padding

The output is **always** padded because the file format requires complete byte rows and heights in increments of four:

- output width: rounded up to a multiple of 8;
- output height: rounded up to a multiple of 4.

The source image is placed in the upper-left corner of the output. Extra pixels are filled with the padding color. Whenever padding is necessary, the interface shows a notice and a padding-color selector. The default is black, so all padding bits are zero. If the input already meets both divisibility requirements, this selector is not shown.

## PIC binary layout

The output contains no file magic or palette. The data consists of three bitplanes, packed per row from left to right, with the most significant bit first.

```text
[optional 4-byte header]
[blue bitplane]
[green bitplane]
[red bitplane]
```

For padded dimensions `W` and `H`:

```text
bytesPerRow = W / 8
planeLength = bytesPerRow * H
payloadLength = planeLength * 3
```

The plane order is therefore **blue, green, red**. This deliberately matches `savePIC()` in `dither.pde`.

### Optional header

When “Add 4-byte header” is selected, it precedes the three bitplanes:

```text
byte 0–1: width W, unsigned 16-bit little-endian
byte 2–3: height H, unsigned 16-bit little-endian
```

The header always contains the **padded output dimensions**, not the original input dimensions.

## Important implementation details

- `getOutputDimensions()` is the sole source of the padded width and height. Use this function in any future extension that needs the output size.
- `renderPreview()` and the download routine use the same threshold (`>= 128`) and padding color. Keep both paths in sync when changing the conversion logic.
- The export uses `0x80 >> (x & 7)` per pixel; the leftmost pixel in a group of eight is bit 7.
- Transparency is not stored in the PIC format. The browser supplies pixels through a canvas, so the tool marks transparent input as unsuitable for exact 3-bit conversion.
- The web tool runs entirely locally in the browser. This code does not upload any images.

## Related Processing code

`dither.pde` contains the original Processing export. It must always write a channel as one bit, for example:

```java
bytes[i+0*n] |= blue(c) >= 128 ? byte(j) : 0;
```

Do not use `byte(j * blue(c) / 255)`: values such as `254` would then become `127` (`01111111`) instead of one bit, which can corrupt pixels in the same byte group and cause black artifacts.
