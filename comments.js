$(".replybutton").click(function() {
	// If the form isn't already open
	if ($(this).parent().find("form").length == 0) {
		$(this).parent().find(".reply_box:last").slideDown("slow");
	} else {
		$(this).parent().find(".reply_box:last").slideToggle("slow");	
	}
});

var last_text = "";
$("textarea[name='comment_body'").focusin(function() {
	last_text = $(this).html();
	$(this).html("");
});
$("textarea[name='comment_body'").focusout(function() {
	$(this).html(last_text);
	last_text = "";
});
