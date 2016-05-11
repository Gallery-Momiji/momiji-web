$.urlParam = function(name) {
  var results = new RegExp('[\?&amp;]' + name + '=([^&amp;#]*)').exec(window.location.search);
  if (results === null) return -1;
  return results[1] || -1;
}

if ($.urlParam('bidder') !== "-1"){
	$('#bannermessage').append("<strong>"+$.urlParam('bidder')+"</strong>");
}