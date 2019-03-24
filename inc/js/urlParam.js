/**
*	Function that reads get parameters returns their values in strings.
*	@RETURN {string} - Actual get parameter
*	@RETURN {boolean} - Returns false if it cannot find the get parameter
*	@PARAM {string} - What get parameter to look for.
*/
$.urlParam = function(name) {
	var results = new RegExp('[\?&amp;]' + name + '=([^&amp;#]*)').exec(window.location.search);
	if (results === null) return false;
	return results[1] || false;
}

