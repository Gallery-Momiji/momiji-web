<img src="logo.png">
<?php
		require_once('config.php');
		require_once('inc/mysql.php');
		require_once('inc/util.php');

		$cashsales = 0;
		$creditcardsales = 0;
		$commission = 0;
		$cashadjust = 0;
		$cashbalance = 0;
		$fees = 0;
		$line = 0;
		$pncount = 0;
		$ancount = 0;
		$pns = array();
		$ans = array();
		$ads = array();

		$receipts = getReceiptsSummary($connection);
		foreach ($receipts as $receipt){
			if($receipt['isGalleryStoreSale'] != '0'){
				if($receipt['Last4digitsCard'] == '0'){
					$cashbalance += $receipt['price'];
					$cashsales += $receipt['price'];
				} else{
					$creditcardsales += $receipt['price'];
				}
			} elseif(($receipt['isAuctionSale'] != '0')
				|| ($receipt['isQuickSale'] != '0')){
				if($receipt['Last4digitsCard'] == '0'){
					$cashbalance += $receipt['price'];
					$cashsales += $receipt['price'];
				} else{
					$creditcardsales += $receipt['price'];
				}
			} elseif($receipt['Last4digitsCard'] == '0'){
				$cashbalance += $receipt['price'];
				$cashadjust += $receipt['price'];
			}
			$items = explode("#", trim($receipt['itemArray'], '#'));
			$prices = explode("#", trim($receipt['priceArray'], '#'));
			$date = substr($receipt['date'],0,10);
			foreach ($items as $key => $item){
				if (isGS($item)){
					$pncount++;
					$pns[$date][] = array($item =>$prices[$key]);
					$commission += ($prices[$key] / 100.0) * getArtistGSCommission($connection,$item);
				} elseif (isAN($item)){
					$ancount++;
					$ans[$date][] = array($item =>$prices[$key]);
					$commission += ($prices[$key] / 100.0) * getArtistASCommission($connection,$item);
				} else{
					$ads[$date][] = array($item =>$prices[$key]);
				}
			}
		}
		$total_money = $cashsales + $creditcardsales;

		foreach ($ans as $day => $sales){
			$td = 0;
			$total = 0;
			echo "<h1>Auction/Quick Sales on " . $day . "</h1><table border=1><tr>";
			foreach ($sales as $key => $sale){
				echo "<td>" . key($sale) . " - <b>$" . number_format($sale[key($sale)],2) . "</b></td>";
				$td++;
				$total +=$sale[key($sale)];
				if ($td % 6 == 0){ echo "</tr><tr>";}
			}
			echo "</tr></table>Total sales for this day: $<b>" . $total . "</b>";
		}

		echo "<hr>";

		foreach ($pns as $day => $sales){
			$td = 0;
			$total = 0;
			echo "<h1>Gallery Store Sales on " . $day . "</h1><table border=1><tr>";
			foreach ($sales as $key => $sale){
				echo "<td>" . key($sale) . " - <b>$" . number_format($sale[key($sale)],2) . "</b></td>";
				$td++;
				$total +=$sale[key($sale)];
				if ($td % 6 == 0){ echo "</tr><tr>";}
			}
			echo "</tr></table>Total sales for this day: $<b>" . $total . "</b>";
		}

		echo "<hr>";

		foreach ($ads as $day => $sales){
			$td = 0;
			$total = 0;
			echo "<h1>Cash Adjustments on " . $day . "</h1><table border=1><tr>";
			foreach ($sales as $key => $sale){
				echo "<td>" . key($sale) . " - <b>$" . number_format($sale[key($sale)],2) . "</b></td>";
				$td++;
				$total +=$sale[key($sale)];
				if ($td % 6 == 0){ echo "</tr><tr>";}
			}
			echo "</tr></table>Total adjustments for this day: $<b>" . $total . "</b>";
		}

		$artistfees = findFees($connection);
		foreach ($artistfees as $artistfee){
			$fees += $artistfee['ArtistPaid'];
			if($artistfee['ArtistcheckOut'] != '0'){
				$fees += $artistfee['ArtistDue'];
			}
		}
?>

<h1>Statistics</h1>

<?php
	echo "<b>Total prints sold</b>: " . $pncount. "<br>";
	echo "<b>Total auction pieces sold </b>: " . $ancount . "<br>";
	echo "<b>Total number of transactions </b>:" . count($receipts). "<br>";
	echo "<b>Total gross cash sales</b>: $" .  number_format($cashsales,2). "<br>";
	echo "<b>Total gross credit card sales</b>: $" .  number_format($creditcardsales,2). "<br>";
	echo "<b>Total gross combined sales</b>: $" .  number_format($total_money,2). "<br>";
	echo "<b>Total commission made</b>: $" .  number_format($commission,2). "<br>";
	echo "<b>Total charged via fees</b>: $" .  number_format($fees,2). "<br>";
	echo "<b>Total cash adjustments</b>: $" .  number_format($cashadjust,2). "<br>";
	echo "<b>Total cash</b>: $" .  number_format($cashbalance,2);
?>
