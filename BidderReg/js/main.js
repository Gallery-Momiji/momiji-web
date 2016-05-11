$.urlParam = function(name) {
  var results = new RegExp('[\?&amp;]' + name + '=([^&amp;#]*)').exec(window.location.href);
  if (results === null) return -1;
  return results[1] || -1;
}

if ($.urlParam('success') === "0"){
	$('#success_bidder').hide();
} else if ($.urlParam('success') === "1"){
	$('#fail_bidder').hide();
} else {
	$('#fail_bidder').hide();
	$('#success_bidder').hide();
}