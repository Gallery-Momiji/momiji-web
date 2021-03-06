<img src="logo.png">

<?php
	echo "<pre>";

	require_once('config.php');
	require_once('inc/util.php');
	require_once('inc/mysql.php');

	if (!isset($_GET['id']) or $_GET['id'] == ""){
		die("ERROR : specify the artist's <b><i>id</i></b> as a get parameter!");
	}
	$artistid = $_GET['id'];
	$salesArray = findSales($connection, $artistid);
	$sales = array();
	$pns = array();
	foreach ($salesArray as $key => $sale){
		$items_sold_old = explode('#', trim($sale['itemArray'], "#"));
		$items_sold = array_map('strtoupper',$items_sold_old);
		$prices = explode('#', trim($sale['priceArray'], '#'));
		foreach ($items_sold as $item_sold_key => $item_sold){
			if(compareItemCodeWithID($item_sold, $artistid)){
				if(isGS($item_sold)){
					$sales[$item_sold] = $prices[$item_sold_key];
					$pns[$item_sold]++;
				} else {
					$sales[$item_sold] = $prices[$item_sold_key];
				}
			}
		}
	}

	$artistinfo = getArtistInfo($connection, $artistid);
	$ascommission = (float)$artistinfo['ArtistASCommission'];
	$gscommission = (float)$artistinfo['ArtistGSCommission'];

	echo "<h1>Artist #" . $artistid . " Sales Summary</h1>";
	$final_balance = 0.0;
?>

<h2>Auction/Quick Sales :</h2>
<table border=1><tr>
<?php
	$total = 0;
	$td = 0;
	foreach ($sales as $key => $sale){
		if (isAN($key)){
			echo "<td>" . $key . " - <b>$" . number_format($sale,2) . "</b></td>";
			$td++;
			$total +=$sale;
			if ($td % 6 == 0){ echo "</tr><tr>";}
		}
	}

	$total_after_commission = $total * (1-($ascommission / 100));
	$final_balance += $total_after_commission;
	echo "</tr></table>";
	echo "Total Sold : <b>$" . number_format($total,2) . "</b><br>"; 
	echo "Gallery Commission (<b>".$ascommission."%</b>): <b>$" . number_format($total - $total_after_commission,2) . "</b><br><hr>";
	echo "Final Balance : <b>$" . number_format($total_after_commission,2) . "</b><br>";
?>

<h2>Gallery Store Sales :</h2>
<table border=1><tr>
<?php
	$total = 0;
	$td = 0;
	foreach ($sales as $key => $sale){
		if (isGS($key)){
			echo "<td>" . $key . "<sup>x".$pns[$key]."</sup> - <b>$" . number_format($sale,2) . "</b></td>";
			$td++;

			$total +=$pns[$key]*$sale;
			if ($td % 6 == 0){ echo "</tr><tr>";}
		}
	}

	$total_after_commission = $total * (1-($gscommission / 100));
	$final_balance += $total_after_commission;
	echo "</tr></table>";
	echo "Total Sold : <b>$" . number_format($total,2) . "</b><br>";
	echo "Gallery Commission (<b>".$gscommission."%</b>): <b>$" . number_format($total - $total_after_commission,2) . "</b><br><hr>";
	echo "Final Balance : <b>$" . number_format($total_after_commission,2) . "</b><br>";
?>

<h2>Totals :</h2>
<?php
	echo "Total of Auction/Gallery Store Balances : <b>$" . number_format(	$final_balance,2) . "</b>";
	$total_due = $final_balance;
	if (floatval($artistinfo['ArtistDue']) != 0){
		echo "<br>Remaining Artist Balance : <b>$" . number_format(floatval($artistinfo['ArtistDue']),2) . "</b>";
		$total_due -= floatval($artistinfo['ArtistDue']);
	}
	if($total_due >= 0) {
		echo "<h3>Total Due To Artist : <b>$";
	} else {
		echo "<h3>Total <i>Owed</i> By Artist : <b>$";
		$total_due = -$total_due;
	}
	echo number_format(	$total_due,2) . "</b></h3>";

	echo "<hr><br><b>Artist Signature:</b><br><br><br>";
	echo "__________________________________________________";
	echo "<br><i>" . $artistinfo['ArtistName'] . "</i>";
?>
