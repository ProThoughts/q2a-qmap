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

	class qa_qmap {
		
		var $directory;
		var $urltoroot;
		

		function load_module($directory, $urltoroot)
		{
			$this->directory=$directory;
			$this->urltoroot=$urltoroot;
		}

		
		function suggest_requests() // should return an array of suggested pages for your module. These suggestions will be displayed within the Q2A admin interface.
		{	
			return array(
				array(
					'title' =>qa_lang_html('plugin_qmap/title-qmap-popular'), // contains a human-readable title used to describe the page, e.g. 'More Stats'.
					'request' => 'qmap-popular', // contains the Q2A $request string for the page, e.g. 'stats/more'. Your match_request() function should of course return true for this string.
					'nav' => 'M', // contains a suggestion about where this page should be linked in the navigation menus. This is only a hint and can easily be changed by the site's administrator. Use 'M' for after the main menu, 'B' for before the main menu, 'O' for opposite the main menu, 'F' for the footer, or null for no navigation element.
				),
				array(
					'title' => qa_lang_html('plugin_qmap/title-qmap-compliance'), 
					'request' => 'qmap-compliance', 
					'nav' => 'M',
				),
				array(
					'title' => qa_lang_html('plugin_qmap/title-qmap-initiative'), 
					'request' => 'qmap-initiative', 
					'nav' => 'M',
				),
				array(
					'title' => qa_lang_html('plugin_qmap/title-qmap-issue'), 
					'request' => 'qmap-issue', 
					'nav' => 'M',
				),
				array(
					'title' => qa_lang_html('plugin_qmap/title-qmap-all'), 
					'request' => 'qmap-all', 
					'nav' => 'M',
				),
				array(
					'title' => qa_lang_html('plugin_qmap/title-qmap-all-except-issues'), 
					'request' => 'qmap-all-except-issues', 
					'nav' => 'M',
				),
			);
		}

		
		function match_request($request) // should return true if your page module will respond to Q2A page $request.
		{
			if (strpos($request,'qmap')!== false)
				return true;

			return false;
		}

		
		function process_request($request)
		{
			$pos = strpos($request,"submenu");
			if ($pos!== false){
				$submenu = substr($request,$pos+8);
				$page = str_replace("qmap-","",substr($request,0,$pos-1));
			}else{
				$submenu = "popular";
				$page = str_replace("qmap-","",$request);
			}
			
			$qa_content=qa_content_prepare(); //Always start by assigning $qa_content=qa_content_prepare(); to include navigation menus and the like.
			
			
			$categoryslugs=qa_request_parts(1);
			$countslugs=count($categoryslugs);			
			$sort=($countslugs && !QA_ALLOW_UNINDEXED_QUERIES) ? null : qa_get('sort');
			$start=qa_get_start();
			$userid=qa_get_logged_in_userid();
			$selectsort='netvotes';
			$generated_htmls_hover=array();
			
			@list($questions, $categories, $categoryid, $favorite)=qa_db_select_with_pending(
				qa_db_qs_selectspec($userid, $selectsort, $start, $categoryslugs, null, false, false, 1000),
				qa_db_category_nav_selectspec($categoryslugs, false, false, true),
				$countslugs ? qa_db_slugs_to_category_id_selectspec($categoryslugs) : null,
				($countslugs && isset($userid)) ? qa_db_is_favorite_selectspec($userid, QA_ENTITY_CATEGORY, $categoryslugs) : null
			);
			
		//	get user information for display	
			$usershtml=qa_userids_handles_html(qa_any_get_userids_handles($questions));
			
			if ($page=="all"){
				$qa_content['navigation']['sub'] = array(
					'qmap-'.$page.'-submenu=popular' => array(
						'url' => qa_path_html('qmap-'.$page).'-submenu=popular',
						'label' => 'Popular',
						'selected' => $submenu=="popular",
					),
					'qmap-'.$page.'-submenu=compliance' => array(
						'url' => qa_path_html('qmap-'.$page).'-submenu=compliance',
						'label' => 'Timing',
						'selected' => $submenu=="compliance",
					),
					'qmap-'.$page.'-submenu=initiative' => array(
						'url' => qa_path_html('qmap-'.$page).'-submenu=initiative',
						'label' => 'Initiator',
						'selected' => $submenu=="initiative",
					),
					'qmap-'.$page.'-submenu=issue' => array(
						'url' => qa_path_html('qmap-'.$page).'-submenu=issue',
						'label' => 'Issues',
						'selected' => $submenu=="issue",
					),
				);
				$page=$submenu;
			}
			if ($page=="all-except-issues"){
				$qa_content['navigation']['sub'] = array(
					'qmap-'.$page.'-submenu=popular' => array(
						'url' => qa_path_html('qmap-'.$page).'-submenu=popular',
						'label' => 'Popular',
						'selected' => $submenu=="popular",
					),
					'qmap-'.$page.'-submenu=compliance' => array(
						'url' => qa_path_html('qmap-'.$page).'-submenu=compliance',
						'label' => 'Timing',
						'selected' => $submenu=="compliance",
					),
					'qmap-'.$page.'-submenu=initiative' => array(
						'url' => qa_path_html('qmap-'.$page).'-submenu=initiative',
						'label' => 'Initiator',
						'selected' => $submenu=="initiative",
					),
				);
				$page=$submenu;
			}
			
						
			$qa_content['q_list']['qs']=array();
			
			if ($page=="initiative"){
				$generated_htmls = array("government" => "","both" => "","public" => "","no votes yet" => "");
			}else{
				if ($page=="compliance"){
					$generated_htmls = array("2014-2015" => "","2016-2018" => "","2019-2023" => "","2024-2030" => "");
				}else{
					if ($page=="issue"){
						$generated_htmls = array("Role of CCS" => "","Research and Development" => "","Economic conditions" => "","Project conditions" => "","Long-term certainty" => "");
					}else{
							$generated_htmls = array();
					}
				}
			}
			
			if (count($questions)) {
			
				$options=qa_post_html_defaults('Q');
				if (isset($categorypathprefix))
					$options['categorypathprefix']=$categorypathprefix;
					
				$generated_html = '';
				
				$poll_array = qa_db_read_all_assoc(
					qa_db_query_sub(
						'SELECT * FROM ^postmeta WHERE meta_key=$',
						'is_poll'
					)
				);
				foreach($poll_array as $q) {
					$poll[(int)$q['post_id']] = $q['meta_value'];
				}
				$question_counter=0;
				foreach ($questions as $question){
					$qa_content['q_list']['qs'][]=qa_any_to_q_html_fields($question, $userid, qa_cookie_get(), $usershtml, null, $options);
					$title = explode(":",$question['title']);
					$shortitle = $title[0];
					if(strlen($shortitle)>50){
						$shortitle=trim(substr($shortitle,0,51));
						$shortitle=substr($shortitle,0,-strpos(strrev($shortitle)," "))."...";
					}
					$shortitle.=" &nbsp; ";
					$qid=$question['postid'];
					$poll_answers = qa_db_read_all_assoc(
						qa_db_query_sub(
							'SELECT BINARY content as content, votes, id FROM ^polls WHERE parentid=# ORDER BY content',
							$qid
						)
					);
					$maxvotes_initiative = 0;
					$totalvotes_initiative = 0;
					$poll_answer_initiative = '';
					$maxvotes_compliance = 0;
					$totalvotes_compliance = 0;
					$poll_answer_compliance = 'no votes yet';
					$poll_answer_initiative = 'no votes yet';
					if ($page=="compliance") $votes_tooltip = '<div style="text-align:left">Compliance votes on this action:<br>';
					if ($page=="initiative") $votes_tooltip = '<div style="text-align:left">Initiative votes on this action:<br>';
					foreach ($poll_answers as $poll_answer){
						$poll_answer_splitted = explode(":",$poll_answer['content']);
						if (strlen($poll_answer['votes'])==0){
							$poll_answer_num_votes = 0;
						}else{
							$poll_answer_num_votes = count(explode(',',$poll_answer['votes']));
						}
						if ($poll_answer_splitted[0]=='initiative'){
							$totalvotes_initiative += $poll_answer_num_votes;
							if ($maxvotes_initiative < $poll_answer_num_votes 
							 || ($maxvotes_initiative == $poll_answer_num_votes && $poll_answer_splitted[1]==" both") ){
								$maxvotes_initiative = $poll_answer_num_votes;
								$poll_answer_initiative = $poll_answer_splitted[1];
								if ($page=="initiative") $votes_tooltip .= "<span style=\"\"> - ".$poll_answer_splitted[1]." : ".$poll_answer_num_votes." votes</span><br>";
							}else{
								if ($page=="initiative") $votes_tooltip .= " - ".$poll_answer_splitted[1]." : ".$poll_answer_num_votes." votes<br>";
							}
							
						}
						if ($poll_answer_splitted[0]=='compliance'){
							$totalvotes_compliance += $poll_answer_num_votes;
							if ($maxvotes_compliance < $poll_answer_num_votes){
								$maxvotes_compliance = $poll_answer_num_votes;
								$poll_answer_compliance = $poll_answer_splitted[1];
								if ($page=="compliance") $votes_tooltip .= "<span style=\"\"> - ".$poll_answer_splitted[1]." : ".$poll_answer_num_votes." votes</span><br>";
							}else{
								if ($page=="compliance") $votes_tooltip .= " - ".$poll_answer_splitted[1]." : ".$poll_answer_num_votes." votes<br>";
							}
						}					
					};
					if ($page=="initiative"||$page=="compliance") $votes_tooltip .= "</div>";
					$number_of_votes = '0%';
					
					$poll_answer_icon="initiative_".$poll_answer_initiative."_icon.png";
					if ($page=="compliance") {
						$column_title = $poll_answer_compliance;
						if ($totalvotes_compliance!=0) {$number_of_votes = round( $maxvotes_compliance/$totalvotes_compliance*100,0)."%";}
					}
					if ($page=="initiative") {
						$column_title = $poll_answer_initiative;
						if ($totalvotes_initiative!=0) {$number_of_votes = round( $maxvotes_initiative/$totalvotes_initiative*100,0)."%";}
						$poll_answer_icon="compliance_".$poll_answer_compliance."_icon.png";
					}
					if ($page=="issue") {
						$column_title = $question['categoryname'];
						if (!array_key_exists($column_title,$generated_htmls_hover)){
							$categories=qa_db_read_all_assoc(qa_db_query_sub(
								'SELECT content FROM ^categories WHERE title=#',
								$question['categoryname']));
							foreach ($categories as $category) {
								$generated_htmls_hover[$column_title]=$category['content'];
							}
						}
					}
					if ($page=="popular") {
						if($question_counter==0){
							$max_votes_current_bucket=$question['netvotes'];
							$column_title_old = '';
						}else{						
							$column_title_old = $column_title;
						}
						$column_title = ''.floor($question_counter/count($questions)*3);
						
						if ($column_title!=$column_title_old&&$question_counter>0){
							$index = $min_votes_current_bucket." to ".$max_votes_current_bucket." likes";
							if (array_key_exists($index,$generated_htmls)){
								$generated_htmls[$index] .= $generated_htmls[$column_title_old];
							}else{
								$generated_htmls[$index] = $generated_htmls[$column_title_old];
							}
							unset($generated_htmls[$column_title_old]);
							$max_votes_current_bucket=$question['netvotes'];
						}
						$question_counter++;
						$number_of_votes = $question['netvotes'];
						$votes_tooltip = $question['upvotes'].' likes -'.$question['downvotes'].' dislikes ='.$question['netvotes'].' netvotes';
						$min_votes_current_bucket=$question['netvotes'];
					}
					
					if (!isset($generated_htmls[$column_title])){ $generated_htmls[$column_title] = "";};

// 			<i style='background-image:url(qa-plugin/qmap/".$poll_answer_icon.")'></i> -> 			<i></i>
					$generated_htmls[$column_title] .= "
<div id='widget'><div class='btn-o'>
		<a href='index.php?qa=".$qid."' class='btn tGrabber' id='qmap-q".$qid."'>
			<i></i><span class='label' id='l'>".$shortitle."</span>
		</a>
	</div>";
					if ($page!="issue"&&$number_of_votes!="0%") {
					$generated_htmls[$column_title] .= "
	<div class='count-o enabled' id='c'>
		<i></i><u></u><a href='index.php?qa=".$qid."' id='count' target='_blank' title='".$votes_tooltip."'>".$number_of_votes."</a>
</div>";
					}
					$generated_htmls[$column_title] .= "</div>";
				}
			} 
			if ($page=="popular") {				
				$index = $min_votes_current_bucket." to ".$max_votes_current_bucket." likes";
				if (array_key_exists($index,$generated_htmls)){
					$generated_htmls[$index] .= $generated_htmls[$column_title_old];
				}else{
					$generated_htmls[$index] = $generated_htmls[$column_title_old];
				}
				unset($generated_htmls[$column_title_old]);
			}
				
			$qa_content['title']=qa_lang_html('plugin_qmap/title-qmap-'.$page); //Use $qa_content['title'] 
			$qa_content['custom_2']="<div class='hcount Itr ready count-ready' style=''>";
//			print_r($generated_htmls);
			foreach ($generated_htmls as $key => $generated_html){
				if (strlen($generated_html) > 0){
					$key_hover='';
					if (array_key_exists($key,$generated_htmls_hover)){
						$key_hover=$generated_htmls_hover[$key];
					}
					$strImage="";
					if($page=="issue"){
						$strAdjustedKey=str_replace(" ","-",strtolower($key));
						$strImage="<a href='".$strAdjustedKey."'><img src='".$strAdjustedKey.".png' style='width:100px;'></a>";
					}
					$qa_content['custom_2'].="
					<div style='display: inline-block;vertical-align:top;margin-right:15px;margin-bottom:20px;'>
						".$strImage."<a href='/ImplementationPlan/".$key."' title='".$key_hover."' style='text-align:center;font-size: 17px;color: #393;margin-bottom:5px;'>".$key."</a>
						<div style='border: 2px solid #00B344;border-radius: 10px;-moz-border-radius: 10px;box-shadow: 10px 10px 5px #BBB;background: #E5FFE5;width: 345px;padding: 4px;'>".$generated_html."</div>
					</div>";
				};
			}	
			$qa_content['custom_2'].="</div>";


			return $qa_content;
		}
	
	}
	

/*
	Omit PHP closing tag to help avoid accidental output
*/