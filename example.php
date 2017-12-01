<?php
// Include DOM class
include 'DOMer.php';
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