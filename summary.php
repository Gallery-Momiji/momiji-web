<img src="logo.png"><h1>Items sold:</h1>
<?php
		require_once('config.php');
		require_once('inc/mysql.php');
		require_once('inc/util.php');
		
		$total_money = 0;
		$line = 0;
		$pncount = 0;
		$ancount = 0;
		$pns = array();
		$ans = array();
		$receipts = getReceiptsSummary($connection);
		foreach ($receipts as $receipt){
			$total_money = $total_money + $receipt['price'];
			$items = explode("#", trim($receipt['itemArray'], '#'));
			$prices = explode("#", trim($receipt['priceArray'], '#'));
			$date = substr($receipt['date'],0,10);
			foreach ($items as $key => $item){
				if (isGS($item)){
					$pncount++;
					$pns[$date][] = array($item =>$prices[$key]);
				} else{ 
					$ancount++;
					$ans[$date][] = array($item =>$prices[$key]);
				}
				
			}
			
			
		}
		
		
		foreach ($ans as $day => $sales){
			$td = 0;
			$total = 0;
			echo "<h1>Auction/Quick Sales done on the " . $day . "</h1><table border=1><tr>";
			foreach ($sales as $key => $sale){
				echo "<td>" . key($sale) . " - <b>$" . number_format($sale[key($sale)],2) . "</b></td>";
				$td++;
				$total +=$sale[key($sale)];
				if ($td % 6 == 0){ echo "</tr><tr>";}
			}
			echo "</tr></table>Total for this day was : $<b>" . $total . "</b>";
		}
		
		echo "<hr>";
		
		foreach ($pns as $day => $sales){
			$td = 0;
			$total = 0;
			echo "<h1>Gallery Store done on the " . $day . "</h1><table border=1><tr>";
			foreach ($sales as $key => $sale){
				echo "<td>" . key($sale) . " - <b>$" . number_format($sale[key($sale)],2) . "</b></td>";
				$td++;
				$total +=$sale[key($sale)];
				if ($td % 6 == 0){ echo "</tr><tr>";}
			}
			echo "</tr></table>Total for this day was : $<b>" . $total . "</b>";
			
		}
?>  

<h1>Statistics</h1>

<?php

	echo "<b>Total Prints sold </b>: " . $pncount. "<br>";
	echo "<b>Total Auction Pieces sold </b>: " . $ancount . "<br>";
	echo "<b>Total money made </b>: $" .  number_format($total_money,2). "<br>";
	echo "<b>User who did most sales</b> : " . getUserWithMostSales($connection) . "<br>";
	echo "<b>Total number of transactions so far</b> :" . count($receipts);

	
	//
	


?>
