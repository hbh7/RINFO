$(".replybutton").click(function() {
	// If the form isn't already open
	if ($(this).parent().find("form").length == 0) {
		$(this).parent().find(".reply_box:first").slideDown("slow");
	} else {
		$(this).parent().find(".reply_box:first").slideToggle("slow");	
	}
});
