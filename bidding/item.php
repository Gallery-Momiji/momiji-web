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
	if ( $infoForBidding['EnableDigitalBid'] == "0" ){
		die('Digital Bidding has been disabled.');
	}
	$itemBids = getBidsForMerch($connection, $artistid, $merchid);

	#Commonly used values
	$MerchMinBid = (int)$infoForBidding['MerchMinBid'];
	$MerchQuickSale = $infoForBidding['MerchQuickSale'];
	$MerchSold = $infoForBidding['MerchSold'];

	$nextbid=$MerchMinBid;
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
        <p id="bannermessage">
          <a href="index.php"><button type="button" class="btn btn-primary">Back</button></a>
          <a href="item.php?artistid=<?php echo $artistid."&merchid=".$merchid;?>"><button type="button" class="btn btn-primary">Refresh</button></a>
          <p>AN<?php
            echo forceStringLength($artistid,3,0,true).'-'.forceStringLength($merchid,3,0,true);
          ?></p>
        </p>
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
        <strong>Error:</strong> The bidder number you provided was not found. If you have not registered yet, please click "Back" and then click "Register to Bid" on the main page.
      </div>
	  <div class="alert alert-danger" style="display:none" role="alert" id="fail_error3">
        <strong>Oops,</strong> looks like you might have been out bid!
      </div>
	  <div class="alert alert-danger" style="display:none" role="alert" id="fail_error4">
        <strong>Sorry,</strong> looks like you already have the highest bid!
      </div>
	  <div class="alert alert-warning" style="display:none" role="alert" id="warn_over">
        <strong>Sorry!</strong> The auction is now closed!
      </div>
	  <div class="alert alert-warning" style="display:none" role="alert" id="warn_sold">
        <strong>Sorry!</strong> This piece has already been sold!
      </div>
	  <div class="alert alert-warning" style="display:none" role="alert" id="warn_live">
        <strong>This piece is going to live auction!</strong> Ask a staff member for when the live auction will happen.
      </div>
    <div class="container">
      <form class="form-horizontal">

<?php
	if ($MerchQuickSale > 0 && count($itemBids) < 1 && $MerchSold != "1"){
		echo '<div class="form-group">
<div class="col-sm-2 control-label"><label></label></div>
<div class="col-sm-2 control-label">This piece has no bids and can be bought outright for <strong>$'.$MerchQuickSale.'</strong>. See a staff member for details.</div>
</div>';
	}
?>
    <div class="form-group">
      Reproduction rights are not included.
    </div>
	<div class="form-group">
          <div class="col-sm-2 control-label"><label>Name of Piece:</label></div>
          <div class="col-sm-2 control-label"><?php echo $infoForBidding['MerchTitle'];?>
          </div>
        </div>
	<div class="form-group">
          <div class="col-sm-2 control-label"><label>Medium of the Piece:</label></div>
          <div class="col-sm-2 control-label"><?php echo $infoForBidding['MerchMedium'];?>
          </div>
        </div>
      </form>
    </div>

  <form class="form-horizontal" id="bidInfo" style="display:none">
<?php
	if ($MerchMinBid > 0){
		echo '<div class="form-group">
<div class="col-sm-2 control-label"><label><strong>Minimum Bid ($):</strong></label></div>
<div class="col-sm-2 control-label">'.$MerchMinBid.'</div>
</div>';
	}

	function get_starred($str) {
	    $len = strlen($str);
	    return substr($str, 0, 1).str_repeat('*', $len - 2).substr($str, $len - 1, 1);
	}
	echo '<div class="form-group">
      <label class="col-sm-2 control-label">Current Bids:</label></div>';

	if (count( $itemBids ) > 0){
		echo '<div class="form-group">
	<div class="col-sm-2 control-label"><label>#</label></div>
	<div class="col-sm-2 control-label"><label>Bidder</label></div>
	<div class="col-sm-2 control-label"><label>Bid Amount</label></div>
	</div>';
		$i=1;
		foreach ($itemBids as $key => $item){
			echo '<div class="form-group">
	<div class="col-sm-2 control-label">'.$i.'</div>
	<div class="col-sm-2 control-label">'.get_starred($item['name']).'</div>
	<div class="col-sm-2 control-label">$'.$item['value'].'</div>
	</div>';
			$i++;
			if($nextbid <= $item['value']) {
				$nextbid=$item['value']+1;
			}
		}
	} else {
		echo '<div class="form-group">
	      <div class="col-sm-2 control-label"></div>
	      <div class="col-sm-2 control-label">No bids have been placed</div></div>';
	}
?>
  </form>
  <form class="form-horizontal" action="submit.php?artistid=<?php
echo $artistid.'&merchid='.$merchid;
?>" method="post" id="bidForm" style="display:none">
    <fieldset>

    <div class="form-group">
      <label class="col-md-4 control-label"><strong>You can register to bid by clicking the button on the previous page.<br>
Please bid in whole dollars only.<br>
After <?php echo $infoForBidding['AuctionCutoff'];?> bids, this piece will be sent to live auction on Sunday.</strong></label>
    </div>
    <div class="form-group">
      <label class="col-md-4 control-label"><h2>Place a bid below</h2></label>
    </div>

    <!-- Bid Number input-->
    <div class="form-group">
      <label class="col-md-4 control-label" for="bnumber">Bidder Number</label>
      <div class="col-md-2">
      <input autocomplete="off" id="bnumber" name="bnumber" type="number" min=1 class="form-control input-md" onkeyup="validateBid()" required="">
      </div>
    </div>

    <!-- Name input-->
    <div class="form-group">
      <label class="col-md-4 control-label" for="name">Name</label>
      <div class="col-md-4">
      <input autocomplete="off" id="name" name="name" type="text" placeholder="First and Last" minlength=2 class="form-control input-md" onkeyup="validateBid()" required="">
      </div>
    </div>

    <!-- Bid Number input-->
    <div class="form-group">
      <label class="col-md-4 control-label" for="bamount">Bid amount ($)</label>
      <div class="col-md-2">
      <input autocomplete="off" id="bamount" name="bamount" type="number" min=<?php
echo $nextbid?> placeholder="<?php echo $nextbid?> or more" onkeyup="validateBid()" class="form-control input-md" required="">
      <script>
        function validateBid() {
          var bt = document.getElementById('submit');
            if (document.getElementById('bnumber').value == "" || document.getElementById('name').value == "" || document.getElementById('bamount').value < <?php echo $nextbid ?>) {
			bt.disabled = true;
		}
		else {
			bt.disabled = false;
		}
	}
      </script>
      </div>
    </div>

    <!-- Submit Button -->
    <!-- TODO add a confirmation, e.g. "Are you sure?" -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="submit"></label>
      <div class="col-md-4">
      <button id="submit" name="submit" class="btn btn-primary" disabled>Submit Bid</button>
      </div>
    </div>

    </fieldset>
  </form>
	  </div>

      <hr>

      <!-- Foot Note -->
      <footer>
        <p>&copy; 2022 Anime North, Gallery Momiji</p>
        <p>This is open source! Find the source code on <a href=https://github.com/Gallery-Momiji>GitHub!</a></p>
      </footer>
    </div> <!-- Scripts -->
        <script>window.jQuery || document.write('<script src="../inc/js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

        <script src="../inc/js/vendor/bootstrap.min.js"></script>
		<script src="../inc/js/urlParam.js"></script>
        <script src="../inc/js/idletimer.js"></script>
        <script src="js/item.js"></script>
<script><?php
	if ($MerchSold == "1"){
		echo "$('#warn_sold').show();";
	} else if ($infoForBidding['AuctionEnd'] == "1"){
		echo "$('#warn_over').show();";
	} else if (count( $itemBids ) >= $infoForBidding['AuctionCutoff']){
		echo "$('#warn_live').show();";
	} else if ($MerchMinBid > 0){
		echo "$('#bidForm').show();";
	}
	if ($MerchMinBid > 0){
		echo "$('#bidInfo').show();";
	}
?></script>
    </body>
</html>
