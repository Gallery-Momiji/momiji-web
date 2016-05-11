$.urlParam = function(name) {
  var results = new RegExp('[\?&amp;]' + name + '=([^&amp;#]*)').exec(window.location.search);
  if (results === null) return -1;
  return results[1] || -1;
}

if ($.urlParam('success') === "0"){
	$('#fail_bidder').show();
} else if ($.urlParam('error') === "1"){
	$('#fail_error').show();
}
