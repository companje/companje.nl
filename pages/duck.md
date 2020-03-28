---
title: Harig badeendje
---
(:blog:2011:02:duck512.gif|)

# Processing code
<html>
<pre style='color:#000000;background:#ffffff;'>PImage img <span style='color:#808030; '>=</span> loadImage<span style='color:#808030; '>(</span><span style='color:#800000; '>"</span><span style='color:#0000e6; '>duck.jpg</span><span style='color:#800000; '>"</span><span style='color:#808030; '>)</span><span style='color:#800080; '>;</span>

size<span style='color:#808030; '>(</span>img<span style='color:#808030; '>.</span>width<span style='color:#808030; '>,</span>img<span style='color:#808030; '>.</span>height<span style='color:#808030; '>)</span><span style='color:#800080; '>;</span>
background<span style='color:#808030; '>(</span><span style='color:#008c00; '>0</span><span style='color:#808030; '>)</span><span style='color:#800080; '>;</span>
smooth<span style='color:#808030; '>(</span><span style='color:#808030; '>)</span><span style='color:#800080; '>;</span>
noFill<span style='color:#808030; '>(</span><span style='color:#808030; '>)</span><span style='color:#800080; '>;</span>

<span style='color:#800000; font-weight:bold; '>for</span> <span style='color:#808030; '>(</span><span style='color:#800000; font-weight:bold; '>int</span> y<span style='color:#808030; '>=</span><span style='color:#008c00; '>0</span><span style='color:#800080; '>;</span> y<span style='color:#808030; '>&lt;</span>img<span style='color:#808030; '>.</span>height<span style='color:#800080; '>;</span> y<span style='color:#808030; '>+</span><span style='color:#808030; '>=</span><span style='color:#008c00; '>5</span><span style='color:#808030; '>)</span> <span style='color:#800080; '>{</span>
  <span style='color:#800000; font-weight:bold; '>for</span> <span style='color:#808030; '>(</span><span style='color:#800000; font-weight:bold; '>int</span> x<span style='color:#808030; '>=</span><span style='color:#008c00; '>0</span><span style='color:#800080; '>;</span> x<span style='color:#808030; '>&lt;</span>img<span style='color:#808030; '>.</span>width<span style='color:#800080; '>;</span> x<span style='color:#808030; '>+</span><span style='color:#808030; '>=</span><span style='color:#008c00; '>5</span><span style='color:#808030; '>)</span> <span style='color:#800080; '>{</span>
    color c <span style='color:#808030; '>=</span> img<span style='color:#808030; '>.</span>get<span style='color:#808030; '>(</span>x<span style='color:#808030; '>,</span>y<span style='color:#808030; '>)</span><span style='color:#800080; '>;</span>
    <span style='color:#800000; font-weight:bold; '>float</span> br <span style='color:#808030; '>=</span> brightness<span style='color:#808030; '>(</span>c<span style='color:#808030; '>)</span><span style='color:#808030; '>/</span><span style='color:#008000; '>255.0</span><span style='color:#800080; '>;</span>
    <span style='color:#800000; font-weight:bold; '>float</span> sa <span style='color:#808030; '>=</span> saturation<span style='color:#808030; '>(</span>c<span style='color:#808030; '>)</span><span style='color:#808030; '>/</span><span style='color:#008000; '>255.0</span><span style='color:#800080; '>;</span>
    <span style='color:#800000; font-weight:bold; '>float</span> hu <span style='color:#808030; '>=</span> hue<span style='color:#808030; '>(</span>c<span style='color:#808030; '>)</span><span style='color:#808030; '>/</span><span style='color:#008000; '>255.0</span><span style='color:#800080; '>;</span>
    pushMatrix<span style='color:#808030; '>(</span><span style='color:#808030; '>)</span><span style='color:#800080; '>;</span>
      translate<span style='color:#808030; '>(</span>x<span style='color:#808030; '>,</span>y<span style='color:#808030; '>)</span><span style='color:#800080; '>;</span>
      rotate<span style='color:#808030; '>(</span>br<span style='color:#808030; '>*</span>TWO_PI<span style='color:#808030; '>)</span><span style='color:#800080; '>;</span>
      stroke<span style='color:#808030; '>(</span>c<span style='color:#808030; '>)</span><span style='color:#800080; '>;</span>
      line<span style='color:#808030; '>(</span><span style='color:#808030; '>-</span><span style='color:#008c00; '>5</span><span style='color:#808030; '>,</span><span style='color:#808030; '>-</span><span style='color:#008c00; '>5</span><span style='color:#808030; '>,</span><span style='color:#008c00; '>10</span><span style='color:#808030; '>,</span><span style='color:#008c00; '>10</span><span style='color:#808030; '>)</span><span style='color:#800080; '>;</span>
    popMatrix<span style='color:#808030; '>(</span><span style='color:#808030; '>)</span><span style='color:#800080; '>;</span>
  <span style='color:#800080; '>}</span>
<span style='color:#800080; '>}</span>
</pre>
</html>

(tag>Programming Art)


~~DISCUSSION~~
