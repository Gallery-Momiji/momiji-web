var results = new RegExp('[\?&amp;]bidder=([^&amp;#]*)').exec(window.location.search);
if (results === null) {
	window.location.href="index.html?error=1";
} else {
	$('#bannermessage').append("<strong>"+results[1]+"</strong>");
}