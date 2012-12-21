// COPY HTML content qa-q-list-item items to title to be able to show it as tooltip

(function( $ ){
	$.fn.copyHTMLtoTitleToolTip = function() {	
	var $this = $(this)	
	$this.each(function(index) {		
		var $myitem =$(this)
		var $associate = $("#qmap-"+$myitem.attr('id'))
		if ($associate.length>0) {
			$(".qa-q-item-main").width(250)
			$(".qa-q-item-title").css("font-size","12px")
			$(".qa-q-item-meta").css("font-size","10px")			
			$(".qa-downvote-count-data,.qa-upvote-count-data,.qa-a-count-data").css("font-size","12px")
			$(".qa-downvote-count-pad,.qa-upvote-count-pad,.qa-a-count-pad").css("font-size","10px")	
			$associate.attr("title",$myitem.html())
			//$myitem.hide()
			$myitem.remove()
		}
	})
  }
})( jQuery )

