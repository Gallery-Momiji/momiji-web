<!doctype html>
<html>
<head>
 <meta charset="utf-8">
 <title>Artist Inventory Summary</title>
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
	$auctionitems = findUnsoldAuctionItems($connection, $artistid);
	$gsitems = findUnsoldGSItems($connection, $artistid);

	$artistinfo = getArtistInfo($connection, $artistid);

	echo "<h1>Artist #" . $artistid . " Inventory Summary</h1>\n";
?>
  <h3>Auction Items:</h3>
  <table>
   <tr class="heading">
    <td>Piece ID</td>
    <td>Piece Title</td>
   </tr>
<?php
	foreach ($auctionitems as $key => $item){
		echo "<tr class=\"item\">\n<td>AN" . forceStringLength($artistid,3,0,true) . "-" . forceStringLength($item['MerchID'],3,0,true) . "</td>\n";
		echo "<td>" . $item['MerchTitle'] . "</td>\n</tr>\n";
	}
	echo "</table>\n";
	echo "<b>Total Items: " . count($auctionitems) . "</b>\n";
?>
  <h3>Gallery Store Items:</h3>
  <table>
   <tr class="heading">
    <td>Price ID</td>
    <td>Piece Title</td>
    <td>Stock</td>
   </tr>
   <tr>
<?php
	foreach ($gsitems as $key => $item){
		echo "<tr class=\"item\">\n<td>PN" . forceStringLength($artistid,3,0,true) . "-" . forceStringLength($item['PieceID'],3,0,true) . "</td>\n";
		echo "<td>" . $item['PieceTitle'] . "</td>\n";
		echo "<td>" . $item['PieceStock'] . "</td>\n</tr>\n";
	}
	echo "</table>\n";
	echo "<b>Total Items: " . count($gsitems) . "</b>\n";
?>
 </div>
</body>
</html>
