<?php
	require_once('../config.php');
	require_once('../inc/mysql.php');

	if (isset($_GET['error']) and $_GET['error'] == "1"){
		//TODO use better error ouput than plain text
		echo "Unable to show selected item, please try again.";
	}

	$itemsForBidding = getItemsForBidding($connection);
	if ($itemsForBidding[0]['AuctionEnd'] == "1"){
		//TODO use better output than plain text
		echo "Sorry! The auction is now closed!";
	}
?>
