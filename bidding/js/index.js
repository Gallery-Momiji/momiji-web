if ($.urlParam('error') !== false){
	$('#fail_error').show();
}
$('#select-anchor').on('change', function() {
	var aTag = $("a[name='"+ $(this).val() +"']");
	$('html,body').animate({scrollTop: aTag.offset().top-50},'slow');
	$(this).get(0).selectedIndex = 0;
});
