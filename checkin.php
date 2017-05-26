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
	$owed = floatval($artistinfo['ArtistDue']);

	if (isset($_GET['fee']) and $_GET['fee'] != ""){
		$owed += floatval($_GET['fee']);
	}
?>
<!doctype html>
<html>
<head>
 <meta charset="utf-8">
 <title>Artist Control Sheet Summary</title>
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
	echo "<h1>Artist #" . $artistid . " Control Sheet Summary</h1>\n"
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
		echo "<tr class=\"item\">\n<td>" . $item['MerchID'] . "</td>\n";
		echo "<td>" . $item['MerchTitle'] . "</td>\n";
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
		echo "<tr class=\"item\">\n<td>" . $item['PieceID'] . "</td>\n";
		echo "<td>" . $item['PieceTitle'] . "</td>\n";
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
  By signing below, you, the artist, agrees to the following terms:
  <ul>
   <li>All of the above information is correct and requires no additional changes.
   <li>Your art has been hung correctly on your panel and/or placed correctly on your table.</li>
   <li>Gallery Momiji will not take responsibility for any art that becomes damaged due to incorrect hanging or table placement.</li>
   <li>All bid sheets are visible and are not hanging off the side or bottom of a panel or table.</li>
   <li>All prints for the Gallery Store have been counted correctly (if applicable).</li>
   <li>A 10% commission will be charged on all sales, both in the Gallery and the Gallery Store.</li>
   <li>All profits will be given in the form of a cheque upon successful checkout on Sunday.</li>
<?php
	if ($owed > 0){
		echo"   <li>You have a unpaid balance of <b><i>$" . number_format($owed,2) . "</b></i>, which will be deducted from your sales at check-out on Sunday.</li>";
		echo"   <li>In the event of unpaid fees, Gallery Momiji reserves the right to withhold your artwork until fees are paid.</li>";
	}
?>
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
