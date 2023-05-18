
# 🪗 Play the accordion with your computer keyboard!
* https://github.com/taniarascia/accordion

# Synthesia alternative in Processing
![Screenshot 2023-05-18 at 10 31 12](https://github.com/companje/companje.nl/assets/156066/b598a014-9ad5-49d2-a815-69d767a1ac0f)

```processing
import javax.sound.midi.*;
import java.io.*;
import ddf.minim.*;
import ddf.minim.ugens.*;
import javax.sound.midi.*;
Minim minim;
AudioOutput out;
Synthesizer synth;
MidiChannel[] channels;
int CH0=0, CH1=1;
int BEGIN_Y=400;
int yStep=3; //hmm... lin relation to 60fps?
int curTime;
int yBar;
boolean isPlaying = true;
PShape square, alien;
int y=BEGIN_Y; //-12275;
float scaler=1/50.;
int timeShift = 3560; //1280;
int minNote=40;
int maxNote=80;
int numNotes=maxNote-minNote;
int noteWidth=50;
String fileName = "La Noyee - bewerkt met Reaper midi_export.MID";
ArrayList<Note> notes = new ArrayList();
boolean muteTrack0 = false;
boolean muteTrack1 = false;
int tempo = 85;
float tempoFactor = 1;

void setup() {
  //size(1200, 800);
  fullScreen(P3D);
  alien = createShape(GROUP);
  textFont(loadFont("AlBayan-Bold-20.vlw"));
  textAlign(CENTER, CENTER);

  minim = new Minim(this);
  out   = minim.getLineOut();

  yBar = int(height/1.5);

  // try to get the default synthesizer from JavaSound
  // if it fails, we print a message to the console
  // and don't do any of the sequencing.
  try
  {
    synth = MidiSystem.getSynthesizer();
    synth.open();
    // get all the channels for the synth
    channels = synth.getChannels();
    // we're only going to use two channels,
    // which we'll now configure to use particular midi instruments.
    // but you should have up to 16 channels available.
    // for a list of general midi instrument program numbers, see: http://www.midi.org/techspecs/gm1sound.php
    channels[CH0].programChange( 21 ); // 5 should be "electric piano 1"
    channels[CH1].programChange( 23) ;//21 ); // 33 should be "acoustic bass"

    // ok make a sequence with our output and custom Instrument
    out.setTempo( tempo );
    //out.pauseNotes();

    //// remember that time and duration are expressed relative to the tempo.
    //// so the first two arguments here mean: on beat 0, play a dotted quarter note.
    //note( 0, 1.5, CH1, "D4", 0.8 );
    ////....
    //out.resumeNotes();
  }
  catch( MidiUnavailableException ex )
  {
    // oops there wasn't one.
    println( "No default synthesizer, sorry bud." );
  }

  String[] lines = loadStrings("lines.txt");
  for (String line : lines) {
    notes.add(new Note(line));
  }

  for (Note n : notes) {
    //int x = int(n.octave*noteWidth); 
    int x = int(map(n.noteNumber, minNote, maxNote, 0, width-200));
    int y = -int(n.timestamp*scaler);
    int w = (n.name.indexOf("#")>-1) ? noteWidth/2 : noteWidth;
    float darkness = (n.name.indexOf("#")>-1) ? .6 : 1;
    int h = -int(n.duration*scaler*tempoFactor);

    square = createShape(RECT, x, y, w, h, 10);
    square.setFill(n.track==1 ?
      color(142*darkness, 169*darkness, 204*darkness) :
      color(176*darkness, 227*darkness, 111*darkness));
    square.setStroke(false);
    alien.addChild(square);
  }
}

ArrayList<Note> getNotesByTimestamp(int t, int range) {
  ArrayList<Note> result = new ArrayList();
  for (Note n : notes) {
    if (n.timestamp-t>-range && n.timestamp-t<range) {
      result.add(n);
    }
  }
  return result;
}


void draw() {
  background(40);
  shape(alien, 0, y);

  fill(255);
  for (Note n : notes) {
    //float x = n.octave*noteWidth; 
    float x = map(n.noteNumber, minNote, maxNote, 0, width-200);
    float yy = -n.timestamp*scaler;
    int w = (n.name.indexOf("#")>-1) ? 25 : 50;
    int h = -int(n.duration*scaler);
    text(n.name, x, yy+y, w, h);
  }

  stroke(255);
  strokeWeight(2);
  line(0, height/1.5, width, yBar);
  noStroke();
  fill(0, 200);
  rect(0, height/1.5, width, height-yBar);

  curTime = int((y-yBar)/scaler) + timeShift;

  int detectNoteTimeWindow = int(100*tempoFactor);
  ArrayList<Note> notes = getNotesByTimestamp(curTime, detectNoteTimeWindow); //(abs(curTime-timeStamp)<50
  if (notes.size()>0) {
    //println(curTime, notes);
    for (Note n : notes) {
      int octave = ( n.noteNumber / 12 ) - 1;
      String name = n.name;
      if (isPlaying && (n.track==1 && !muteTrack0) || (n.track==2 && !muteTrack1)) {
        note( 0, n.duration/10000.*tempoFactor, n.track-1, name+octave, 1) ;// );
      }
      //
      //println(n.duration);
    }
  }

  //if (y%20==0) {
  //  note( 0, .1, CH1, "D4", 0.8 );
  //}

  if (isPlaying) {
    y+=(yStep * tempoFactor);
  }

  stroke(0);
  fill(255);
  for (int x=0; x<=width; x+=noteWidth) {
    rect(x, height-100, x+noteWidth, 100);
  }
}

void mouseWheel(MouseEvent event) {
  y -= event.getCount()*3; // *.00001;
  //println(y);
}

void keyPressed() {
  if (key==BACKSPACE) y=BEGIN_Y;
  if (key=='+') yStep++;
  if (key=='-') yStep--;
  if (key=='[') tempoFactor-=.1;
  if (key==']') tempoFactor+=.1;
  if (key=='1') muteTrack0=!muteTrack0;
  if (key=='2') muteTrack1=!muteTrack1;
  if (keyCode==LEFT) y-=250;
  //if (keyCode==LEFT) yStep-=50;
  //println(timeShift);

  if (key==' ') isPlaying=!isPlaying;
}

class Note {
  int track, noteNumber, octave, velocity, timestamp, duration;
  String name;

  Note(String s) {
    String[] items = s.split(",");
    track = int(items[0]);
    timestamp = int(items[1]);
    duration = int(items[2]); //in ms?
    noteNumber = int(items[3]);
    name = items[4];
    octave = int(items[5]);
  }
  
  String toString() {
    return name;
  }
}

class MidiSynth implements ddf.minim.ugens.Instrument
{
  int         channel;
  int         noteNumber;
  int         noteVelocity;
  
  MidiSynth( int channelIndex, String noteName, float vel )
  {
    channel = channelIndex;
    // to make our sequence code more readable, we use note names.
    // and then convert the note name to a Midi note value here.
    noteNumber = (int)Frequency.ofPitch(noteName).asMidiNote();
    // similarly, we specify velocity as a [0,1] volume and convert to [1,127] here.
    noteVelocity = 1 + int(126*vel); 
  }
  
  void noteOn(float duration) {
    channels[channel].noteOn( noteNumber, noteVelocity );
  }
  
  void noteOff() {
    channels[channel].noteOff(noteNumber);
  }
}

void note( float time, float duration, int channelIndex, String noteName, float velocity ) {
  out.playNote( time, duration, new MidiSynth( channelIndex, noteName, velocity ) );
}
```
