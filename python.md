---
title: Python
---

===Guide to Python function decorators===
http://thecodeship.com/patterns/guide-to-python-function-decorators/

===4 interessante stukjes Python===
<code python>
def logUser(name, age, length):
    print('name: ', name)
    print('age: ', age)
    print('length: ', length)

#logUser('alex', 29, 1.75)
user = ('alex', 29, 1.75)
logUser(*user)
</code>
<code python>
def logUser(name, age, length):
    print('name: ', name)
    print('age: ', age)
    print('length: ', length)

# logUser(age=28, length=1.75, name='alex')
user = {'age':28, 'length':1.75, 'name':'alex'}
logUser(**user)
</code>
<code python>
def log(*args):
    print(args)
    message = ""
    for item in args:
        message += item+' ';
    print(message)

log('hallo', '2134', 'fsfdsf')
</code>
<code python>
def logUser(**kwargs):
    print('name: ', kwargs['name'])
    print('age: ', kwargs['age'])
    print('length: ', kwargs['length'])

logUser(age=28, length=1.75, name='alex')
</code>

==SimpleHTTPServer==
  python -m SimpleHTTPServer 8000
  
==kivy==
http://kivy.org/#home

==ImportError: No module named NK.gui.app==
In NinjaKittens folder:
<code>
export PYTHONPATH=.
</code>

==gui==
* http://kivy.org

==libxml / libxml2 / lxml ==
untested:
<code>
brew install libxml2
</code>
or
<code>
sudo port install py25-lxml
</code>

==install easy_install==
http://pypi.python.org/pypi/setuptools#downloads
<code>
sh setuptools-0.6c9-py2.4.egg --prefix=~
</code>

==Mogelijke oplossing voor problemen met Python op OSX Lion==
* http://www.niconomicon.net/blog/2011/10/09/2120.wrestling.python.snow.leopard
* http://stackoverflow.com/questions/6886578/how-to-install-pycairo-1-10-on-mac-osx-with-default-python

==version==
<code bash>
python --version
</code>

==location of python==
<code bash>
type python
</code>
or
<code bash>
which python
</code>

==info about executable==
<code bash>
file /usr/local/bin/python
</code>

==set python path (for macports?)==
see also: [[macports]]
<code bash>
export PYTHONPATH=/opt/local/Library/Frameworks/Python.framework/Versions/2.7/lib/python2.7/site-packages
</code>