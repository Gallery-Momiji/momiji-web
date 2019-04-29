<?php
	require_once('../config.php');
	require_once('../inc/mysql.php');
	require_once('../inc/util.php');

	$itemsForBidding = getItemsForBidding($connection);
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
        <p id="bannermessage">Select an item below to start bidding</p>
      </div>
    </div>
	
	<div class="container">
		<div class="row">
				<form method="post" action="index.php" >
					<div class="input-append">
						<input class="search-query input-medium" name="search_query" type="text" placeholder="Search..." >
						<button type = "submit "class="btn btn-large" type="button">🔍</button>
					</div>
				</form>
		
		</div>
	</div>
    <div class="container">
      <div class="row">
      <!-- Alert Messages -->
	  <div class="alert alert-danger" style="display:none" role="alert" id="fail_error">
        <strong>Error:</strong> Unable to show selected item, please try again.
      </div>
	  <div class="alert alert-warning" style="display:none" role="alert" id="warn_over">
        <strong>Sorry!</strong> The auction is now closed!
      </div>
	  <div class="alert alert-warning" style="display:none" role="alert" id="warn_search">
        <strong>Sorry!</strong> We couldn't find any article under that name!
      </div>
<?php
	#TODO implement "search" function to filter generated content below 
	foreach ($itemsForBidding as $key => $item){

		$itemid='AN'.forceStringLength($item['ArtistID'],3,0,true).'-'.forceStringLength($item['MerchID'],3,0,true);
		echo '<form class="form-horizontal" action="item.php?artistid='.$item['ArtistID'].'&merchid='.$item['MerchID'].'" method="post" id="item'.$itemid.'">
<div class="form-group">
<div class="col-sm-1"><button style="width:80px" class="btn btn';
		if ($item['MerchSold'] == "1"){
			echo '">Sold!';
		} else {
			echo '-primary">Select';
		}
		echo'</button></div>
<div class="col-sm-2 control-label"><label">'.$itemid.'</label></div>
<div class="col-sm-2 control-label"><label">'.$item['MerchTitle'].'</label></div>
</div></form>';
	}
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
        <script src="js/index.js"></script>
<?php
	if ( false === empty( $itemsForBidding ) ){
		if ( $itemsForBidding[0]['AuctionEnd'] == "1" ){
			echo "<script>$('#warn_over').show();</script>";
		}
	}
	
	if ( isset( $_POST['search_query'] ) && empty( $itemsForBidding ) ) {
		echo "<script>$('#warn_search').show();</script>";
	}
?>
    </body>
</html>
