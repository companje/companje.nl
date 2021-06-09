---
title: ffmpeg
permalink: /ffmpeg
tags: ['notes','software','video']
---

# replace audio of a video keeping length of the shortest
```bash
ffmpeg -i IMG_8327.mp4 -i Motions.mp3 -c:v copy -map 0:v:0 -map 1:a:0 -shortest new.mp4
```

# convert for Whatsapp
crf 30 is lower quality. default is 23. (see: https://trac.ffmpeg.org/wiki/Encode/H.264)
```bash
ffmpeg -i Flocking.mp4 -crf 30 -c:v libx264 -profile:v baseline -level 3.0 -pix_fmt yuv420p FlockingWhatsapp.mp4
```

# convert for Whatsapp, scale and remove duplicate frames
```bash
ffmpeg -i INPUT.MOV -c:v libx264 -profile:v baseline -level 3.0 -pix_fmt yuv420p -s 500x500 -vf mpdecimate,setpts=N/FRAME_RATE/TB -q:v 1 OUTPUT.MOV
```

# convert multiple audio files to mp3
```bash
for foo in *.m4a; do ffmpeg -i "$foo" "${foo%.m4a}.mp3"; done
```

# make 10x10 tile from each 586th frame (scaled)
```bash
ffmpeg -i 140001.mp4 -filter:v "scale=195:-1,select=(gte(n\,586))*not(mod(n\,586)),tile=10x10" -frames:v 1 -vsync vfr -y tile.jpg
```

# stack videos horizontal + vertical
```bash
ffmpeg -i video1.mov -i video2.mov -i video3.mov -filter_complex "\
 [1:v]scale=960:-1[left]; \
 [2:v]scale=960:-1[right]; \
 [left][right]hstack[bottom]; \
 [0:v][bottom]vstack \
" -s hd720 -q:v 0 -vcodec mpeg4 output.mp4
```

# stack videos vertical + horizontal
```bash
ffmpeg -i video1.mov -i video2.mov -i video3.mov -filter_complex "\
 [1:v]scale=960:-1[top]; \
 [2:v]scale=960:-1[bottom]; \
 [top][bottom]vstack[right]; \
 [0:v][right]hstack \
" -s hd720 -q:v 0 -vcodec mpeg4 output.mp4
```

# export raw image sequence rgb24
```bash
ffmpeg -i airtraffic-2k-400f.mp4 -vcodec rawvideo -pix_fmt rgb24 -f image2 -y raw/image%d.raw
```

# higher quality
```bash
ffmpeg -i INPUT -vb 20M -q:v 0 -vcodec libx264 OUTPUT.mp4
```

# high quality bitrate
```bash
ffmpeg -i INPUT -vb 20M -vcodec mpeg4 OUTPUT.mp4
```

# blur
```bash
ffmpeg -i intro.mov -vf "boxblur=5:1" intro-blur.mov
```

# reinstall with libraries
If you already have ffmpeg installed, but not with the other libraries, use the reinstall command.
```bash
brew reinstall ffmpeg --with-opus
```

# for web?
```bash
ffmpeg -i IN.mov -an -strict experimental -vcodec libx264 -f mp4 -crf 22 OUT.mp4
```

# image fade in and out to movie
```bash
ffmpeg -loop 1 -i frame.png -frames:v 400 -vcodec mpeg4 -vf fade=in:0:25,fade=out:375:25  -y -q:v 0 output.mp4
```

# create video with x frames from image
```bash
ffmpeg -loop 1 -i frame.png -vframes 400 -vcodec mpeg4 output.mp4
```

# fade in and out
```bash
ffmpeg -i 1.mp4 -y -vf fade=in:0:25,fade=out:375:25 inout.mp4
```

# fade two videos and make same framerate
```bash
ffmpeg -r 25  -i 1.mp4 -y -vf 'fade=in:0:25,fade=out:375:25' -vcodec mpeg4 -q:v 0 -r 25 1-inout.mp4
ffmpeg -r 25  -i 2.mp4 -y -vf 'fade=in:0:25,fade=out:375:25' -vcodec mpeg4 -q:v 0 -r 25 2-inout.mp4
```

# convert *.mjpeg to mp4
```bash
#!/bin/bash

vardate=$(date +%Y\-%m\-%d); 

for i in *.mjpeg; do
    echo -n "Converting '$i'..."

    if [[ "$vardate.mjpeg" == "$i" ]]; then
        echo "SKIP"
    else
        name="${i%.*}"
        ffmpeg -i $i -vcodec mpeg4 -q:v 0 -loglevel error movies/$name.mp4
        # rm $i
        echo "OK"
    fi

done
```

# sequence in folder to movie
```bash
if [[ $# -eq 0 ]] ; then
    echo 'Usage: ./toMovie.sh FOLDER'
    exit 0
fi

folderpath=$1
foldername=${folderpath##*/}

echo Combining $folderpath/'*.png' to $foldername.mp4

ffmpeg \
  -pattern_type glob -i "$folderpath/*.png" \
  -vcodec mpeg4 \
  -q:v 0 \
  movies/$foldername.mp4

```

# convert mp4 to iPhone ringtone
seek to 6:45 then take 30 seconds of input
```bash
ffmpeg -y -ss 0:06:45 -t 30 -i cybernoid.mp4 -vn cybernoid.m4a
mv cybernoid.m4a cybernoid.m4r
# then drag the result into the 'Tones' folder of the iPhone in iTunes
```

# image sequence / slideshow to gif
```bash
ffmpeg -i %d.png -s hd480 -y -filter:v "setpts=15*PTS" output.gif
```

# glob 
```bash
ffmpeg -pattern_type glob -i "2017-06-07/*.png" -vcodec mpeg4 -y -q:v 0 test.mp4
```

# ffserver 
```bash
HTTPPort 8090

<Feed bunny.ffm>
	Launch ffmpeg -i rtsp://184.72.239.149/vod/mp4:BigBuckBunny_115k.mov
</Feed>

<Stream bunny.mjpeg>
	Feed bunny.ffm
	Format mpjpeg
	VideoCodec mjpeg
	ACL allow localhost
	ACL allow 192.168.0.0 192.168.255.255
</Stream>

<Stream stat.html>
	Format status
	ACL allow localhost
	ACL allow 192.168.0.0 192.168.255.255
</Stream>
```

even een snelle file in elkaar gezet als voorbeeld, zie config

als je dan start: ffserver -f bunny.conf

zie je hier een konijn
http://localhost:8090/bunny.mjpeg

en hier de status
http://localhost:8090/stat.html

In dit geval haal ik een rtsp stream binnen en voer die weer uit als mjpeg stream

Maar het kan nog super veel meer

https://www.ffmpeg.org/ffserver.html

Ik had het zelf nodig met 10 netwerk camera’s die per stuk maar 3 gelijktijdige streams aankunnen, door het door mijn eigen ffserver heen te gooien gebruik ik op elke camera maar 1 stream en kan ik het naar tientallen clients streamen.

Ook fijn als je een camera achter een router hebt zitten en je wel ergens een server hebt, kun je met ffserver die camera naar die server streamen en weer verder.
# quality 
-q:v 0 is highest quality
-q:v 31 is lowest quality
# get / calculate number of frames 
```bash
ffmpeg -i FILENAME -f null /dev/null
```

or add this to ```~/.bash_profile```
```bash
_fps() {
    ffmpeg -i "$1" -f null /dev/null
}
alias fps=_fps
```
# set volume of audio 
  ffmpeg -i input.wav -af "volume=0.5" output.wav
# gifenc (with speed) 
```bash
palette="/tmp/palette.png"
speed=5

filters="fps=15,scale=800:-1:flags=lanczos,setpts=(1/$speed)*PTS"

ffmpeg -v warning -i $1 -vf "$filters,palettegen" -y $palette
ffmpeg -v warning -i $1 -i $palette -lavfi "$filters [x]; [x][1:v] paletteuse" -y $2
```
# calculate framerate to get a desired number of frames 
say you have 2500 frames. You want to get 300 frames. 2500/300=8.3. Use 8.3 as input framerate and use 1 as output framerate. (or 83 as input framerate and 10 as output framerate)

  ffmpeg -r 8.3 -i input.mp4 -vcodec mpeg4 -q:v 0 -r 1 output.mp4
or
  ffmpeg -r 83 -i input.mp4 -vcodec mpeg4 -q:v 0 -r 10 output.mp4

# etc 
  ffmpeg -r 7.5 -f concat -i list.txt -vcodec mpeg4 -g 1 -q:v 0 -s 2048x1024 -r 1 -y output2.mov

# HQ animated gifs with custom palette 
* http://blog.pkh.me/p/21-high-quality-gif-with-ffmpeg.html
* http://superuser.com/questions/556029/how-do-i-convert-a-video-to-gif-using-ffmpeg-with-reasonable-quality/556031#556031

# concat 
list.txt:
```bash
file 'a-ceno.mov'
file 'b-cret.mov'
file 'c-jurtrias.mov'
file 'd-ltpaleo.mov'
```
commmand:
```bash
ffmpeg -f concat -i list.txt -c copy output.mov
```

# change speed 
```bash
  ffmpeg -i input.mp4 -vf "setpts=(1/<speed>)*PTS" output.mp4
```

# resize video to 480p or 720p 
```bash
  ffmpeg -i INPUT.MOV -s hd480 OUTPUT.MOV
```

# create animated GIF from sequence and specify framerate 
```bash
  ffmpeg -r 2 -i screen-%04d.tif -y kochcurve.gif
```
more settings: http://superuser.com/questions/556029/how-do-i-convert-a-video-to-gif-using-ffmpeg-with-reasonable-quality/556031#556031

# convert image sequence to movie 
  ffmpeg -i output/frame%d.jpg -g 1 -y -q:v 0 -r 10  output.mp4

# lossless skip first x seconds of mp3 
```bash
  ffmpeg -ss 54 -i input.mp3 -acodec copy -y output.mp3
```

# limit total number of frames (not changing framerate) 
```bash
ffmpeg -i input.mp4 -vcodec mpeg4 -vframes 500 -q:v 0 output.mp4
```

# cut / split video 
```bash
ffmpeg -ss 00:01:22 -i Doodle3D.m4v -q 0 -vcodec copy -acodec copy -y tmp.mov
```

```bash
ffmpeg -ss 00:01:14 -t 00:00:31 -i Doodle3D-kickstarter-movie.mp4 -q 0 -vcodec copy -acodec copy -y tmp2.mp4
```

# offset & seek 
offset video (0.5 sec) & seek/skip to position in audio (0.3 sec):
```bash
ffmpeg -itsoffset 0.3 -i video-input.mp4 -ss 0.5 -i audio-input.mp3 -vcodec copy -acodec copy -y output.mov
```

# fix aspect ratio 
```bash
..... -s 900x720 -aspect 16:9 
```

# -sameq vs -qscale 
* for video
```bash
-q:v 0
```
* for audio
```bash
-q:a 0
```

# add audio / soundtrack to movie 
```bash
ffmpeg -i IMG_7966.MOV -i sound.mp3 -vcodec copy -acodec copy output.mov 
```

# wellicht interessant 
* http://rodrigopolo.com/ffmpeg/cheats.html
* http://www.warpwood.com/wiki/ffmpeg/#index9h2
* http://superuser.com/questions/347433/how-to-create-an-uncompressed-avi-from-a-series-of-1000s-of-png-images-using-ff

# rawvideo / uncompressed avi 
```bash
ffmpeg -i air_traffic_2048.mp4 -sameq -r 15 -vcodec rawvideo -y new.avi
```

# more uncompressed info 
* http://superuser.com/questions/347433/how-to-create-an-uncompressed-avi-from-a-series-of-1000s-of-png-images-using-ff
```bash
ffmpeg -i one-hand-with-sleeve.mpg -sameq -g 1 -f mov -vcodec qtrle -pix_fmt rgb24 output.mov
```
* http://ffmpeg-users.933282.n4.nabble.com/Outputting-uncompressed-8-bit-4-2-2-MOV-td3264815.html
```bash
ffmpeg -i input.mov -vcodec rawvideo -pix_fmt uyvy422 -vtag 2vuy  uncompressed.mov
```

# combine jpg's with existing mjpeg movie (on Windows) 
```bash
@echo off
cd /d %0\..
if exist clouds-queue\*.jpg (
  cat clouds-queue/*.jpg | ffmpeg -f image2pipe -vcodec mjpeg -i - -sameq -s 2048x1024 -f mjpeg -vcodec mjpeg -y new-clouds.mov
  ffmpeg -f mjpeg -i concat:"clouds2048.mov|new-clouds.mov" -c copy -y combined.mov
)

if exist combined.mov (
  del clouds2048.mov
  del new-clouds.mov
  move combined.mov clouds2048.mov
  move clouds-queue\*.jpg clouds-done\
)
```

# in case of segmentation faults you might need to add -vcoded 
```bash
ffmpeg -i Globe4D-energy-related-content.mov -sameq -s 512x256 -vcodec mpeg4 Globe4D-energy-related-content-512.mov
```

# combine multiple movies with ffmpeg 
```bash
#!/bin/bash
ffmpeg -f mjpeg -i <\
(  ffmpeg -v 0 -i 07h.mov -f image2pipe -vcodec copy -y /dev/stdout;
  ffmpeg -v 0 -i 08h.mov -f image2pipe -vcodec copy -y /dev/stdout;
  ffmpeg -v 0 -i 09h.mov -f image2pipe -vcodec copy -y /dev/stdout;
  ...
) -vcodec copy -an -y total.mov
stty echo
```# add non-sequence images to movie with cat and ffmpeg 
```bash

cat 2012050412*.jpg | ffmpeg -v 0 -f image2pipe -vcodec mjpeg -i  - -sameq -vcodec mjpeg -y 12h.mov
```

# add new frames / image files to an existing mjpeg movie with ffmpeg 
```bash
#!/bin/bash
ffmpeg -f mjpeg -i <\
(  ffmpeg -v 0 -i clouds.mov -f image2pipe -vcodec copy -y /dev/stdout;
  cat clouds-queue/*.jpg | ffmpeg -v 0 -f image2pipe -vcodec mjpeg -i  - -sameq -vcodec mjpeg -f mjpeg -y -s 1024x512 /dev/stdout;
) -vcodec copy -an -y tmp.mov

rm clouds.mov
mv tmp.mov clouds.mov
mv clouds-queue/*.jpg clouds-done/
```

# losse plaatjes omzetten naar filmpje met framerate en veel keyframes 
```bash
ffmpeg -f image2 -r 1 -i frame-%04d.png -r 5 -g 1 -y -sameq -s 1024x512 output.mov
```

# output framerate 10, keyframe on everyframe 
```bash
ffmpeg -i ring.mov -r 10 -g 1 -sameq -y ring-10.mov```

# -r 1 sets input framerate to 1 
```bash
ffmpeg -r 1 -i image-%d.PNG -sameq -g 1 -y  A2-vegetation.mov```

# convert movie to iPad 
```bash
ffmpeg -i input.mov -acodec libfaac -ac 2 -ab 160k -s 1024x768 -vcodec libx264 -vpre slow -vpre ipod640 -b 1200k -f mp4 -threads 10 output.mp4```

# convert image sequence to movie 
```bash
ffmpeg -f image2 -i frame%03d.png -s 1024x512 output.mov```

# convert movie from canon ixus to mpeg 
```bash
ffmpeg -i MVI_0131.AVI -r 25 -sameq output.mov```

# rotate movie (with memcoder) 
```bash
mencoder -vf rotate=1 -o OUTPUT.AVI -oac copy -ovc lavc MVI_7590.AVI ```

# Remove audio from a movie 
```bash
ffmpeg -i input.mov -an output.mov```

# Combine jpg and mp3 audio to mpg 
(in this case portrait). Be sure to use RGB jpg's instead of CMYK.
```bash
ffmpeg -y -i vogels.jpg -loop_input -i vogels.mp3 -s 320x480 vogels320x480.mpg
or:
ffmpeg -y -b 2500k -r 30 -i yellow-brick-road.jpg -i brand-new-day.mp3 -map 0:0 -map 1:0 -vsync 1 -sameq  -vcodec mpeg4 -s 320x480 result2.mp4```

# crop away black side bars 
```bash
ffmpeg -i in.mov -sameq -cropleft 104 -cropright 104 hands.mov
```

# set start / offset time of input movie 
-itsoffset needs to go before -i filename
```bash

ffmpeg -itsoffset 10 -i IMG_4699.MOV -s 640x360 -an -sameq hebbenEnHouden.mov
```

# timelapse with ffmpeg / gstreamer 
* see http://www.oz9aec.net/index.php/gstreamer/346-simple-time-lapse-video-with-gtreamer-and-ffmpeg
