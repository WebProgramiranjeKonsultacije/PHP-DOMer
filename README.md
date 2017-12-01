# PHP DOMer

PHP DOMer is simple DOM class for creating HTML elements and complete DOM only via PHP.

## Introduction

This class allow you to render HTML page only via PHP on few simple line of codes. With this you can build any web page and avoid bunch of HTML tags, speedup your development and bring your development and optimization on higher level. Rendering is simple, clean and there is no problems regarding unclosed tags, attributes, bad formats etc.

## Must mention - HELP REQUIRED

This project is open source and I would like you to help in this development. Is not documented all but functions and objects inside class are 80% commented and understandable. If you like this project - JUMP IN and help to all.

### Installation

Download and include `DOMer.php` file into your first lines of project like this:

```
<?php
include_once '/path/to/DOMer.php';
```

After that just simple call class

```
$dom = new DOMer();
```

This will setup HTML5 DOM element for you with charset `UTF-8` and lang `en`.
If you wish to change that pharams, you can simple do like this:

```
$dom = new DOMer('html5', 'ISO-3166-1', 'en');
```

### How to use?

When you initialize your DOM, now is ready for development. Here is one example of Twitter Bootstrap setup and rendered page via DOMer:

```
<?php
include_once '/path/to/DOMer.php';

// Initialize DOM
$dom = new DOMer('html5');

// Include CSS
$dom->css(array(
  'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css',
));

// Include JavaScripts
$dom->js(array(
  'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js',
  'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js',
  'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js',
));

// Title of the web page
$dom->title = 'Home Page';

// Initialize content
$html = $col = $row = array();

// Set columns
$col[] = $dom->tag('div', array('class'=>'col'), 'Column 1');
$col[] = $dom->tag('div', array('class'=>'col'), 'Column 2');
$col[] = $dom->tag('div', array('class'=>'col'), 'Column 3');
$col[] = $dom->tag('div', array('class'=>'col'), 'Column 4');

// Set row
$row[] = $dom->tag('div', array('class'=>'row'),$col);

// Set container
$html[] = $dom->tag('div', array('class'=>'container'),$row);

// Print HTML
$dom->html($html, array(
  'body_attr' => array(
    'class' => 'home-page',
    'id' => 'home-page'
  ),
  'beautify' => true
),true);
```

On this example, we create complete web page using Twitter Bootstrap framework with 4 columns inside container. On this way you can render any we page, any project.
