<!doctype html>
<html>
<head>
 <meta charset="utf-8">
 <title>Artist Check-In Summary</title>
 <style>

  .misc-box table{
   width:100%;
   line-height:inherit;
   text-align:left;
  }

  .misc-box table td{
   vertical-align:top;
  }

  .misc-box table tr.top table td{
   padding-bottom:20px;
  }

  .misc-box table tr.heading td{
   background:#eee;
   border-bottom:1px solid #ddd;
   font-weight:bold;
  }

  .misc-box table tr.item td{
   border-bottom:1px solid #eee;
  }
 }
 </style>
</head>

<body>
 <img src="logo.png">
 <div class="misc-box">
 <?php

	require_once('config.php');
	require_once('inc/util.php');
	require_once('inc/mysql.php');

	if (!isset($_GET['id']) or $_GET['id'] == ""){
		die("ERROR : specify the artist's <b><i>id</i></b> as a get parameter!");
	}
	$artistid = $_GET['id'];
	$auctionitems = findAuctionItems($connection, $artistid);
	$gsitems = findGSItems($connection, $artistid);

	$artistinfo = getArtistInfo($connection, $artistid);

	echo "<h1>Artist #" . $artistid . " Check-In Item Summary</h1>\n"
?>
  <h3>Auction Items:</h3>
  <table>
   <tr class="heading">
    <td>Piece Title</td>
    <td>Min Bid</td>
    <td>Quick Sale</td>
    <td>AAMB?</td>
   </tr>
<?php
	foreach ($auctionitems as $key => $item){
		echo "<tr class=\"item\">\n<td>" . $item['MerchTitle'] . "</td>\n";
		echo "<td>$" . number_format($item['MerchMinBid'],2) . "</td>\n";
		echo "<td>$" . number_format($item['MerchQuickSale'],2) . "</td>\n";
		if ($item['MerchAAMB'] == "0")
			echo "<td>No</td>\n</tr>\n";
		else
			echo "<td>Yes</td>\n</tr>\n";
	}
	echo "</table>\n";
	echo "<b>Total Items: " . count($auctionitems) . "</b>\n";
?>
  <h3>Gallery Store Items:</h3>
  <table>
   <tr class="heading">
    <td>Piece Title</td>
    <td>Price</td>
    <td>Stock</td>
    <td>SDC?</td>
   </tr>
   <tr>
<?php
	foreach ($gsitems as $key => $item){
		echo "<tr class=\"item\">\n<td>" . $item['PieceTitle'] . "</td>\n";
		echo "<td>$" . number_format($item['PiecePrice'],2) . "</td>\n";
		echo "<td>" . $item['PieceInitialStock'] . "</td>\n";
		if ($item['PieceSDC'] == "0")
			echo "<td>No</td>\n</tr>\n";
		else
			echo "<td>Yes</td>\n</tr>\n";
	}
	echo "</table>\n";
	echo "<b>Total Items: " . count($gsitems) . "</b>\n";
?>
  <h2>Agreement:</h2>
  By signing below, the artist agrees to the following terms:
  <ul>
   <li>That all items listed above are accounted for and have been handed over to the procession of Gallery Momiji.</li>
   <li>Any mistake made by the artist prior to the check-in process, including, but not limited to, missing and damaged items, does infers any liability to Gallery Momiji.</li>
   <li>Our terms are clear and are acknowledged by the artist; the artist has read, understands, and consents to our artist contract.</li>
  </ul>   
<?php
	echo "<p><b>Date: " . date('dS M Y') . "</b></p>\n";
	echo "<b>Artist Signature:</b><br><br><br>\n";
	echo "__________________________________________________\n";
	echo "<br><i>" . $artistinfo['ArtistName'] . "</i>\n";
?>
 </div>
</body>
</html>
