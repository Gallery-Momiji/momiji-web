<?php
	require_once('../config.php');
	require_once('../inc/mysql.php');

	$fields = array();
	var_dump($_POST);

	if (!isset($_GET['artistid']) or $_GET['artistid'] == "" or !isset($_GET['merchid']) or $_GET['merchid'] == ""){
		header('Location: index.php?error=1');
		return;
	}
	$artistid = $_GET['artistid'];
	$merchid = $_GET['merchid'];

	$biddernumber = $connection->real_escape_string($_POST['bnumber']);
	$biddername = $connection->real_escape_string($_POST['name']);
	$bidvalue = $connection->real_escape_string($_POST['bamount']);

	if (!ctype_digit($bidvalue)){
		header('Location: item.php?error=1&artistid='.$artistid.'&merchid='.$merchid);
		return;
	}

	if (!checkBidder($connection, $biddernumber)){
		header('Location: item.php?error=2&artistid='.$artistid.'&merchid='.$merchid);
		return;
	}

	$checkBid = checkPreBidInfo($connection, $artistid, $merchid, $bidvalue);
	if (!count( $checkBid )){
		header('Location: item.php?error=1&artistid='.$artistid.'&merchid='.$merchid);
		return;
	}
	$checkBid = $checkBid[0];

	if ($checkBid['AuctionEnd'] == "1" or $checkBid['MerchSold'] == "1" or $checkBid['bidcount'] >= $checkBid['AuctionCutoff']){
		header('Location: item.php?error=1&artistid='.$artistid.'&merchid='.$merchid);
		return;
	}
	if ($checkBid['currentbid'] >= $bidvalue){
		header('Location: item.php?error=3&artistid='.$artistid.'&merchid='.$merchid);
		return;
	}

	$checkLastBidder = checkLastBid($connection, $artistid, $merchid);
	if (count( $checkLastBidder ) and $checkLastBidder[0]['bidderno'] == "$biddernumber"){
		header('Location: item.php?error=4&artistid='.$artistid.'&merchid='.$merchid);
		return;
	}

	if (submitBid($connection, $artistid, $merchid, $bidvalue, $biddername, $biddernumber)){
		header('Location: item.php?success=1&artistid='.$artistid.'&merchid='.$merchid);
	} else {
		header('Location: item.php?error=3&artistid='.$artistid.'&merchid='.$merchid);
	}
?>
