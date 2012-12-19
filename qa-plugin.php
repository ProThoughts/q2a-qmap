<?php

/*
	Ecofys (c) Ruut Brandsma
	http://www.ecofys.com/


	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.question2answer.org/license.php
*/

/*
	Plugin Name: QMap
	Plugin URI: 
	Plugin Description: plugin for question2answer which enables you to generate a clickable column based map of questions which can be used to easily navigate through a list of questions
	Plugin Version: 1.0
	Plugin Date: 2012-12-19
	Plugin Author: Ecofys/Ruut Brandsma
	Plugin Author URI: http://www.ecofys.com/
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.4
	Plugin Update Check URI: 
*/


	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
	}

	qa_register_plugin_module(
		'page', // type of module
		'qa-qmap.php', // PHP file containing module class
		'qa_qmap', // module class name in that PHP file
		'QMap' // human-readable name of module
	);

	qa_register_plugin_phrases(
		'qa-qmap-lang-*.php', // pattern for language files
		'plugin_qmap' // prefix to retrieve phrases
	);
	
	qa_register_plugin_layer(
		'qa-qmap-layer.php', // PHP file containing layer
		'QMap' // human-readable name of layer
	);

/*
	Omit PHP closing tag to help avoid accidental output
*/