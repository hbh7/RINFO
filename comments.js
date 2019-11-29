$("replybutton").click(function() {
	// If the form isn't already open
	if ($(this).parents().find("form").length == 0) {
		$(this).closest(".comment").append(
			"<div class='reply_box'>" +
			"<form method='post' action=>" +
			"</div>"
		);
	}
});