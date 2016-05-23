if ($.urlParam('error') !== false){
	$('#fail_error').show();
}

/**
*	To save on querying later, I'm querying most known fields / elements
*	whithin the dom to re-use instead of constantly re-querying them!
*/
var termsCheckBox = $('#terms');
var submitBtn = $('#submit');
/**
*	This is an event listenner applied to the checkbox for terms and agreement.
*	For each click, a checked state is evaluated and applied as a disable property
*	to the submit button!
*/
termsCheckBox.click( 
	function(){
		submitBtn.prop( "disabled", !this.checked );
	}
);