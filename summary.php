<img src="logo.png">
<?php
		require_once('config.php');
		require_once('inc/mysql.php');
		require_once('inc/util.php');

		$cashsales = 0;
		$creditcardsales = 0;
		$line = 0;
		$pncount = 0;
		$ancount = 0;
		$pns = array();
		$ans = array();
		$receipts = getReceiptsSummary($connection);
		foreach ($receipts as $receipt){
			if($receipt['Last4digitsCard'] != '0'){
				$creditcardsales = $creditcardsales + $receipt['price'];
			} else {
				$cashsales = $cashsales + $receipt['price'];
			}
			$items = explode("#", trim($receipt['itemArray'], '#'));
			$prices = explode("#", trim($receipt['priceArray'], '#'));
			$date = substr($receipt['date'],0,10);
			foreach ($items as $key => $item){
				if (isGS($item)){
					$pncount++;
					$pns[$date][] = array($item =>$prices[$key]);
				} else {
					$ancount++;
					$ans[$date][] = array($item =>$prices[$key]);
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
?>

<h1>Statistics</h1>

<?php
	echo "<b>Total prints sold</b>: " . $pncount. "<br>";
	echo "<b>Total auction pieces sold </b>: " . $ancount . "<br>";
	echo "<b>Total number of transactions </b>:" . count($receipts). "<br>";
	echo "<b>Total gross cash sales</b>: $" .  number_format($cashsales,2). "<br>";
	echo "<b>Total gross credit card sales</b>: $" .  number_format($creditcardsales,2). "<br>";
	echo "<b>Total commission made</b>: $" .  number_format($total_money * (1-((INT)COMMISSION_GS / 100)),2);
?>
