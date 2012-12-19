// COPY HTML content qa-q-list-item items to title to be able to show it as tooltip

(function( $ ){
	$.fn.copyHTMLtoTitleToolTip = function() {	
	var $this = $(this)
	$(".qa-q-item-main").width(250)
	$this.each(function(index) {
		var $myitem =$(this)
		var $associate = $("#qmap-"+$myitem.attr('id'))
		if ($associate.length>0) {
			$associate.attr("title",$myitem.html())
			//$myitem.hide()
			$myitem.remove()
		}
	})
  }
})( jQuery )

