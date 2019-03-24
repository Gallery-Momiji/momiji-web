<?php
	require_once('../config.php');
	require_once('../inc/mysql.php');

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
          <a class="navbar-brand" href="#">Gallery Momiji - Silent Auction Bidding</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">

        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <!-- Main jumbotron -->
    <div class="jumbotron">
      <div class="container">
        <p id="bannermessage">Items for Bidding</p>
      </div>
    </div>

    <div class="container">
      <div class="row">
      <!-- Alert Messages -->
	  <div class="alert alert-danger" style="display:none" role="alert" id="fail_error">
        <strong>Error:</strong> Unable to show selected item, please try again.
      </div>
	  <div class="alert alert-warning" style="display:none" role="alert" id="fail_over">
        <strong>Sorry!</strong> The auction is now closed!
      </div>
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
	if ($itemsForBidding[0]['AuctionEnd'] == "1"){
		//TODO use better output than plain text
		echo "<script>$('#fail_over').show();</script>";
	}
?>
    </body>
</html>
