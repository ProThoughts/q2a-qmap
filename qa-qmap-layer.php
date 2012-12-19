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

	class qa_html_theme_layer extends qa_html_theme_base
	{

		function head_script() // add a Javascript and CSS file from plugin directory
		{
			
			$this->content['script'][]="<script type='text/javascript'>
	$(document).ready(function(){
		$('a:not(.tGrabber),h1,h2,img').tg();
		$('span:not(.badgetooltip)').tgDuplicate(); //same as .tg in seperate namespace, to solve problem of span within a.
		$('.tGrabber').tgClickable(); // custom class
		$('.qa-q-list-item').copyHTMLtoTitleToolTip(); //
	});
</script>";
			$this->content['script'][]="<script type='text/javascript' src='".qa_html(QA_HTML_THEME_LAYER_URLTOROOT."tg.js")."'></script>";
			$this->content['script'][]="<script type='text/javascript' src='".qa_html(QA_HTML_THEME_LAYER_URLTOROOT."qa-qmap.js?version=13")."'></script>";
			$this->content['script'][]="<link rel='stylesheet' type='text/css' href='".qa_html(QA_HTML_THEME_LAYER_URLTOROOT."tg.css")."'>";
			$this->content['script'][]="<link rel='stylesheet' type='text/css' href='".qa_html(QA_HTML_THEME_LAYER_URLTOROOT."tweetbutton.css")."'>";
			qa_html_theme_base::head_script();
		}
				
		function post_meta_who($post, $class) // show usernames of privileged users in italics
		{
			require_once QA_INCLUDE_DIR.'qa-app-users.php'; // for QA_USER_LEVEL_BASIC constant
			
			if (isset($post['raw']['opostid'])) // if item refers to an answer or comment...
				$level=@$post['raw']['olevel']; // ...take the level of answer or comment author
			else
				$level=@$post['raw']['level']; // otherwise take level of the question author
			
			if ($level>QA_USER_LEVEL_BASIC) // if level is more than basic user...
				$post['who']['data']='<I>'.@$post['who']['data'].'</I>'; // ...add italics
			
			qa_html_theme_base::post_meta_who($post, $class);
		}
	}
	
/*
	Omit PHP closing tag to help avoid accidental output
*/
	