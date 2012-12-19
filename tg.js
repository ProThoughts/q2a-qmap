// ORIGINAL
// titleGrabber - roXon 
(function( $ ){
	$.fn.tg = function() {	
	
	// **** div tG setup	
	$(document).mousemove(function(mTG){
		$("#tG").css({
			top:(mTG.pageY+12)+"px",
			left:(mTG.pageX+15)+"px"
		});
	});
	$('body').append('<div id="tG"></div>');
	if ( $.browser.msie ) { $('#tG').css({background:'#000'}); }
	if ( $.browser.msie && $.browser.version.substr(0,1)<7 ) {  $('#tG').css({width:'150px'}); };
	
	// **** titleGrabber
	var $this = $(this);		
	$this.live("mouseover mouseout",function(ev){
		var el = $(this);		
		if ( el.attr('title') ) {
			if (ev.type === 'mouseover'){
				el.data('tt',el.attr('title')).attr('title','');			
				elSrc = '';
				if( el.attr('src') ){
					var imgSrc = el.attr('src');
					var elSrc = '<img src="'+imgSrc+'"style="height:13px;"/>';
				}				
				$('#tG').html( elSrc +' '+ el.data('tt')).stop(true,true).slideToggle(300);
			} 
		}
		else {
			$('#tG').hide().html('');
			el.attr('title',el.data('tt'));
		}		
	});
	// ****	
  };
})( jQuery );

// DUPLICATE OF ORIGINAL IN A SEPARATE NAMESPACE TO SOLVE PROBLEM WITH SPAN WITHIN A

(function( $ ){
	$.fn.tgDuplicate = function() {	
	
	// **** div tG setup	
	$(document).mousemove(function(mTG){
		$("#tGDuplicate").css({
			top:(mTG.pageY+12)+"px",
			left:(mTG.pageX+15)+"px"
		});
	});
	$('body').append('<div id="tGDuplicate"></div>');
	if ( $.browser.msie ) { $('#tGDuplicate').css({background:'#000'}); }
	if ( $.browser.msie && $.browser.version.substr(0,1)<7 ) {  $('#tGDuplicate').css({width:'150px'}); };
	
	// **** titleGrabber
	var $this = $(this);		
	$this.live("mouseover mouseout",function(ev){
		var el = $(this);		
		if ( el.attr('title') ) {
			if (ev.type === 'mouseover'){
				el.data('tt',el.attr('title')).attr('title','');			
				elSrc = '';
				if( el.attr('src') ){
					var imgSrc = el.attr('src');
					var elSrc = '<img src="'+imgSrc+'"style="height:13px;"/>';
				}				
				$('#tGDuplicate').html( elSrc +' '+ el.data('tt')).stop(true,true).slideToggle(300);
			} 
		}
		else {
			$('#tGDuplicate').hide().html('');
			el.attr('title',el.data('tt'));
		}		
	});
	// ****	
  };
})( jQuery );


// DUPLICATE OF ORIGINAL WITH SOME CHANGED TO MAKE TOOLTIP CLICKABLE

var gloBooTGSelected = 0, gloBooElSelected = 0, gloIntX, gloIntY, gloDivSelected;
var t = new Date();var tStart=t.getTime();

function delayedFunc(){
	if (gloBooTGSelected==0&&gloBooElSelected==0) {
//		var t = new Date();console.log('['+(t.getTime()-tStart)+'] '+'delayedFunc start hide');
		$('#tGClickable').hide().html('');
		gloDivSelected.attr('title',gloDivSelected.data('tt'));
//		var t = new Date();console.log('['+(t.getTime()-tStart)+'] '+'delayedFunc finished hide');
	}							
}
(function( $ ){
	$.fn.tgClickable = function() {	
	
	// **** div tG setup	
	$(document).mousemove(function(mTG){
		$("#tGClickable").css({
//			top:(mTG.pageY+12)+"px",
//			left:(mTG.pageX+15)+"px"
			top:(gloIntY+12)+"px",
			left:(gloIntX-10)+"px"
		});
	});
	$('body').append('<div id="tGClickable"></div>');
	
	if ( $.browser.msie ) { $('#tGClickable').css({background:'#000'}); }
	if ( $.browser.msie && $.browser.version.substr(0,1)<7 ) {  $('#tGClickable').css({width:'150px'}); };
	
	// **** titleGrabber
	var $this = $(this);		
	$this.live("mouseover mouseout",function(ev){
			var el = $(this);		
			if (ev.type === 'mouseover'&&!el.attr('title')){gloBooElSelected = 1;}
			if ( el.attr('title')) {
				if (ev.type === 'mouseover'&&!($('#tGClickable').is(":visible"))){
					gloDivSelected = el;
					el.data('tt',el.attr('title')).attr('title','');			
					elSrc = '';
					if( el.attr('src') ){
						var imgSrc = el.attr('src');
						var elSrc = '<img src="'+imgSrc+'"style="height:13px;"/>';
					}				
//					var t = new Date();console.log("["+(t.getTime()-tStart)+"] "+"el."+ev.type+" start unhide, tgvisible: "+$('#tGClickable').is(":visible"));
					$('#tGClickable').html( elSrc +' '+ el.data('tt')).stop(true,true).slideToggle(300);
//					var t = new Date();console.log("["+(t.getTime()-tStart)+"] "+"el."+ev.type+" finished unhide, tgvisible: "+$('#tGClickable').is(":visible"));
					gloIntX = el[0].offsetLeft;
					gloIntY = el[0].offsetTop;
				} 
			} else {
				if (ev.type === 'mouseout'){
					gloBooElSelected = 0;
					if(gloDivSelected){setTimeout("delayedFunc();", 50);};
				}
			}		
//			var t = new Date();console.log("["+(t.getTime()-tStart)+"] "+"el."+ev.type+"  gloBooTGSelected:"+gloBooTGSelected+"  gloBooElSelected:"+gloBooElSelected+", tgvisible: "+$('#tGClickable').is(":visible"));
//		}
	});
	$('#tGClickable').live("mouseover mouseout",function(ev){
				if (ev.type === 'mouseover'){
					gloBooTGSelected = 1;
				}else{
					gloBooTGSelected = 0;
					if($('#tGClickable').is(":visible")){
						if(gloDivSelected){setTimeout("delayedFunc();", 200);};
					}
				}
//			}
//		var t = new Date();console.log("["+(t.getTime()-tStart)+"] "+"TG."+ev.type+"  gloBooTGSelected:"+gloBooTGSelected+"  gloBooElSelected:"+gloBooElSelected+", tgvisible: "+$('#tGClickable').is(":visible"));
	});
	// ****	
  };
})( jQuery );