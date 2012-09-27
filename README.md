wp-github-buttons
=================
Insert GitHub buttons(http://ghbtns.com) to WordPress post.

Installation
------------
1. Upload the plugin files to the `/wp-content/plugins/` directory.

2. Activate the plugin through the 'Plugins' menu in WordPress.

Usage
-----
### Format
```
[github user={user} type={button type} repo={repository} count={show count} size={button size}]
[github type={button type} count={show count} size={button size}]{user/repo url}[/github]
```

You can also use `[gh]`.

### Argument
See here: https://github.com/adamcoulombe/github-buttons#usage

## Example
#### Follow button
```
[gh user=frah type=follow]
```
#### Large watch button
```
[github user=frah repo=wp-github-buttons type=watch size=large]
```

#### Fork button with count
```
[github type=fork count=true]https://github.com/frah/wp-github-buttons[/github]
```

License
-------
Copyright (c) 2012 Atsushi OHNO.

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
