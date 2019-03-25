<?php
	require_once('../config.php');
	require_once('../inc/mysql.php');
	require_once('../inc/util.php');

	if (!isset($_GET['artistid']) or $_GET['artistid'] == "" or !isset($_GET['merchid']) or $_GET['merchid'] == ""){
		header('Location: index.php?error=1');
	}
	$artistid = $_GET['artistid'];
	$merchid = $_GET['merchid'];

	$infoForBidding = getInfoForBidding($connection, $artistid, $merchid);
	if (!count( $infoForBidding )){
		header('Location: index.php?error=1');
	}
	$infoForBidding = $infoForBidding[0];
	$itemBids = getBidsForMerch($connection, $artistid, $merchid);
?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="../inc/css/bootstrap.min.css">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="../inc/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="../inc/css/main.css">

        <!--[if lt IE 9]>
            <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
            <script>window.html5 || document.write('<script src="../inc/js/vendor/html5shiv.js"><\/script>')</script>
        <![endif]-->
    </head>
    <body>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">Gallery Momiji - Silent Auction Bidding</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">

        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <!-- Main jumbotron -->
    <div class="jumbotron">
      <div class="container">
        <p id="bannermessage">AN<?php
echo forceStringLength($artistid,3,0,true).'-'.forceStringLength($merchid,3,0,true);
?></p>
      </div>
    </div>

    <div class="container">
      <div class="row">
      <!-- Alert Messages -->
	  <div class="alert alert-success" style="display:none" role="alert" id="success_bid">
        <strong>Your bid has been submitted!</strong> Good luck!
      </div>
	  <div class="alert alert-danger" style="display:none" role="alert" id="fail_error1">
        <strong>Error:</strong> Unable to submit bid, please try again.
      </div>
	  <div class="alert alert-danger" style="display:none" role="alert" id="fail_error2">
        <strong>Error:</strong> The bidder number you provided was not found. If you have not registered yet, please contact a staff member.
      </div>
	  <div class="alert alert-danger" style="display:none" role="alert" id="fail_error3">
        <strong>Oops,</strong> looks like you might have been out bid!
      </div>
	  <div class="alert alert-warning" style="display:none" role="alert" id="warn_over">
        <strong>Sorry!</strong> The auction is now closed!
      </div>
	  <div class="alert alert-warning" style="display:none" role="alert" id="warn_sold">
        <strong>Sorry!</strong> This piece has already been sold!
      </div>
	  <div class="alert alert-warning" style="display:none" role="alert" id="warn_live">
        <strong>This piece is going to live auction!</strong> Ask the staff for when the, live auction will happen.
      </div>
<?php
#TODO fill information
?>
	  </div>

      <hr>

      <!-- Foot Note -->
      <footer>
        <p>&copy; 2019 Anime North, Gallery Momiji</p>
        <p>This is open source! Find the source code on <a href=https://github.com/Gallery-Momiji>GitHub!</a></p>
      </footer>
    </div> <!-- Scripts -->
        <script>window.jQuery || document.write('<script src="../inc/js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

        <script src="../inc/js/vendor/bootstrap.min.js"></script>
		<script src="../inc/js/urlParam.js"></script>
        <script src="js/item.js"></script>
<script><?php
	if ($infoForBidding['MerchSold'] == "1"){
		echo "$('#warn_sold').show();";
	} else if ($infoForBidding['AuctionEnd'] == "1"){
		echo "$('#warn_over').show();";
	} else if (count( $itemBids ) >= $infoForBidding['AuctionCutoff']){
		echo "$('#warn_live').show();";
	} else {
		#TODO Remove comment once button is added
		#echo "$('#submit').prop("disabled",!this.checked);";
	}
?></script>
    </body>
</html>
