<?php
	require_once('../config.php');
	require_once('../inc/mysql.php');

	if (isset($_GET['error'])){
		//TODO use better error ouput than plain text
		switch ($_GET['error']){
			case "1":
				echo "Unable to submit bid, please try again.";
			case "2":
				echo "The bidder number you provided was not found. If you have not registered yet, please contact a staff member.";
			case "3":
				echo "Oops, looks like you might have been out bid!";
		}
	}

	if (isset($_GET['success']) and $_GET['success'] == "1"){
		//TODO use better output than plain text
		echo "Your bid has been submitted! Good luck!";
	}

	if (!isset($_GET['artistid']) or $_GET['artistid'] == "" or !isset($_GET['merchid']) or $_GET['merchid'] == ""){
		header('Location: index.php?error=1');
	}
	$artistid = $_GET['artistid'];
	$merchid = $_GET['merchid'];

	$infoForBidding = getInfoForBidding($connection, $artistid, $merchid);
	if (!count( $infoForBidding )){
		header('Location: index.php?error=1');
	}
	$infoForBidding = $infoForBidding[0];

	if ($infoForBidding['AuctionEnd'] == "1"){
		//TODO use better output than plain text
		echo "Sorry! The auction is now closed!";
	}

	if ($infoForBidding['MerchSold'] == "1"){
		//TODO use better output than plain text
		echo "This piece has already been sold!";
	} else {
		$itemBids = getBidsForMerch($connection, $artistid, $merchid);
		if (count( $itemBids ) >= $infoForBidding['AuctionCutoff']){
			//TODO use better output than plain text
			echo "This piece is going to live auction! Ask the staff for when the, live auction will happen.";
		}
	}
?>
