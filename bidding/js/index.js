if ($.urlParam('error') !== false){
	$('#fail_error').show();
}
$('#select-anchor').on('change', function() {
	var aTag = $("a[name='"+ $(this).val() +"']");
	$('html,body').animate({scrollTop: aTag.offset().top-50},'slow');
	$(this).get(0).selectedIndex = 0;
});

var topbtn = document.getElementById("TopBtn");
function showTopBtn() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    topbtn.style.display = "block";
  } else {
    topbtn.style.display = "none";
  }
}
window.onscroll = function() {showTopBtn()};

function GoToTop() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}
