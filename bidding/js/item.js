switch($.urlParam('error')) {
  case "1":
    $('#fail_error1').show();
    setTimeout(function(){$('#fail_error1').fadeOut()}, 10000);
    break;
  case "2":
    $('#fail_error2').show();
    setTimeout(function(){$('#fail_error2').fadeOut()}, 10000);
    break;
  case "3":
    $('#fail_error3').show();
    setTimeout(function(){$('#fail_error3').fadeOut()}, 10000);
    break;
  case "4":
    $('#fail_error4').show();
    setTimeout(function(){$('#fail_error4').fadeOut()}, 10000);
    break;
  default:
    if ($.urlParam('success') !== false){
      $('#success_bid').show();
    }
}
