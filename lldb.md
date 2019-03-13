---
title: LLDB debugger
---

first create a debug version of the app by compiling with `-g`
* http://www.rapidtables.com/code/linux/gcc/gcc-g.htm
* http://lldb.llvm.org/lldb-gdb.html

```bash
lldb myExecutable
> run
# when program stops because of EXC_BAD_ACCESS type:
> frame variable
```

# run with arguments
```bash
lldb myExecutable
> run -o"/Users/rick/Documents/openFrameworks/of0092" -a"ofxCv,ofxOpenCv" -p"osx" "/Users/rick/Documents/openFrameworks/of0092/apps/myApps/cvBgTest9"
```

# stacktrace
```
> bt
```
