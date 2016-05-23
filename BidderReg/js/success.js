var bidder = $.urlParam('bidder');
if ( bidder ){
	$('#bannermessage').append("<strong>"+bidder+"</strong>");
} else {
	window.location.href="index.html?error=1";
}