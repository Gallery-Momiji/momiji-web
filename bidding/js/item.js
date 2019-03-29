switch($.urlParam('error')) {
  case "1":
    $('#fail_error1').show();
    break;
  case "2":
    $('#fail_error2').show();
    break;
  case "3":
    $('#fail_error3').show();
    break;
  case "4":
    $('#fail_error4').show();
    break;
  default:
    if ($.urlParam('success') !== false){
      $('#success_bid').show();
    }
}
