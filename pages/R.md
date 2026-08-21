---
title: R
---

# links
* tryr.codeschool.com
* <http://www.cyclismo.org/tutorial/R/input.html>
* <http://www.r-tutor.com/r-introduction>
* <http://www.sr.bham.ac.uk/~ajrs/R/r-getting_started.html>


# ggplot2 cheatsheet
* <http://www.rstudio.com/wp-content/uploads/2015/03/ggplot2-cheatsheet.pdf>

# examples
```r
> 1+1
[1] 2

>r <- 5
> r
[1] 5
> pi * r^2
[1] 78.53982

>install.packages("ggplot2")

# vector with 'c'omponents (members)
>c(1,2,3)  
[1] 1 2 3

> x <- c(1,2,3)
> x
[1] 1 2 3
> length(x)
[1] 3

# evaluate expression for each element in x:
> y <- 2*x
> y
[1] 2 4 6

> x <- 5
> y <- c(1,2,3)
> x*y
[1]  5 10 15

> a <- 1:10
> a
[1]  1  2  3  4  5  6  7  8  9 10
```
[read more](http://www.sr.bham.ac.uk/~ajrs/R/r-getting_started.html)

```r
> a <- read.table("http://www.sr.bham.ac.uk/~ajrs/R/datasets/file.dat", header=T)
> a
  r    x    y
1 1 4.20 14.2
2 2 2.40 64.8
3 3 8.76 63.4
4 4 5.90 32.2
5 5 3.40 89.6
```

> id<-196
> data<-read.table(paste("http://meetjestad.net/data?type=sensors&ids=",id,"&format=csv", sep=""), sep="\t", header=T)
> data
      id           timestamp longitude latitude temperature humidity supply
1    196 2017-11-26 16:44:46   0.00000   0.0000     18.1250  48.6875   3.28
2    196 2017-11-26 17:19:50   0.00000   0.0000     18.6875  50.3750   3.29
#...

# <http://meetjestad.net/data/handleiding.r>



