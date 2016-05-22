<img src="logo.png">

<?php
	echo "<pre>";

	require_once('config.php');
	require_once('inc/util.php');
	require_once('inc/mysql.php');

	if (!isset($_GET['id']) or $_GET['id'] == ""){
		die("ERROR : specify the artist's <b><i>id</i></b> as a get parameter!");
	}
	$salesArray = findSales($connection, $_GET['id']);
	$sales = array();
	$pns = array();
	foreach ($salesArray as $key => $sale){
		$items_sold_old = explode('#', trim($sale['itemArray'], "#"));
		$items_sold = array_map('strtoupper',$items_sold_old);
		$prices = explode('#', trim($sale['priceArray'], '#'));
		foreach ($items_sold as $item_sold_key => $item_sold){
			if(compareItemCodeWithID($item_sold, $_GET['id'])){
				if(isGS($item_sold)){
					$sales[$item_sold] = $prices[$item_sold_key];
					$pns[$item_sold]++;
				} else {
					$sales[$item_sold] = $prices[$item_sold_key];
				}
			}
		}
	}

	$artistinfo = getArtistInfo($connection, $_GET['id']);

	echo "<h1>Artist #" . $_GET['id'] . " Sales Summary</h1>"
?>

<h2>Auction/Quick Sales :</h2>
<table border=1><tr>
<?php
	$total = 0;
	$td = 0;
	foreach ($sales as $key => $sale){
		if (!isGS($key)){
			echo "<td>" . $key . " - <b>$" . number_format($sale,2) . "</b></td>";
			$td++;
			$total +=$sale;
			if ($td % 6 == 0){ echo "</tr><tr>";}
		}
	}

	$total_after_commission = $total * (1-((INT)COMMISSION_AS / 100));
	$final_balance += $total_after_commission;
	echo "</tr></table>";
	echo "Total made : <b>$" . number_format($total,2) . "</b><br>"; 
	echo "Commission taken (<b>".COMMISSION_AS."%</b>): <b>$" . number_format($total - $total_after_commission,2) . "</b><br><hr>";
	echo "Final Balance : <b>$" . number_format($total_after_commission,2) . "<br>";
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

	$total_after_commission = $total * (1-((INT)COMMISSION_GS / 100));
	$final_balance += $total_after_commission;
	echo "</tr></table>";
	echo "Total made : <b>$" . number_format($total,2) . "</b><br>";
	echo "Commission taken (<b>".COMMISSION_GS."%</b>): <b>$" . number_format($total - $total_after_commission,2) . "</b><br><hr>";
	echo "Final Balance : <b>$" . number_format($total_after_commission,2) . "<br>";
	echo "<hr><br><br><br>";
	echo "Total of Auction/Gallery store balances : <b>$" . number_format(	$final_balance,2) . "</b>";
	$total_due = $final_balance;
	if (floatval($artistinfo['ArtistDue']) != 0){
		echo "<br>Current Artist balance : <b>$" . number_format(floatval($artistinfo['ArtistDue']),2) . "</b>";
		$total_due -= floatval($artistinfo['ArtistDue']);
	}
	if($total_due >= 0) {
		echo "<br>Total due to artist : <b>$";
	} else {
		echo "<br>Total <i>owed</i> by artist : <b>$";
		$total_due = -$total_due;
	}
	echo number_format(	$total_due,2) . "</b>";

	echo "<br><br>Artist Signature:<br><br><br>";
	echo "__________________________________________________";
	echo "<br><i>" . $artistinfo['ArtistName'] . "</i>";
?>
