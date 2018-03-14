---
title: React
---
* <https://github.com/jakubgarfield/expenses>
* see also [[d3]] for visualisations with D3 in React
* see also [[redux]]
* <http://reactcheatsheet.com/>
* <https://facebook.github.io/react/docs/tutorial.html>
* <https://www.youtube.com/watch?v=NpMnRifyGyw>
* download and install React dev tools for Chrome (and open your page in a new tab)
* <https://facebook.github.io/react/docs/thinking-in-react.html>
* <https://github.com/ptmt/react-native-desktop>
* <https://facebook.github.io/react-native/>
* <https://facebook.github.io/react/docs/interactivity-and-dynamic-uis.html>
* <http://babeljs.io/blog/2015/06/07/react-on-es6-plus/>

===== propTypes =====
Het is ook handig de mogelijke proptypes te omschrijven. dit kan bijv zo:
```
class Input {
    static propTypes = {
      placeholder: PropTypes.string,
      onSubmit: PropTypes.func.isRequired
   }
}
```
Hiervoor moet je nog wel stage 0 enablen in de babel config, dit kan door in de `config.js`, in `babelOptions`, `stage: 0` toe te voegen.

zie ook: <https://facebook.github.io/react/docs/reusable-components.html>

==== Bind a component's onClick event ====
<https://github.com/peteruithoven/todo/blob/master/src/components/Input.js#L11>
