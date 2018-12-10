<?php
	require_once('../config.php');
	require_once('../inc/mysql.php');

	if (!isset($_GET['artistid']) or $_GET['artistid'] == "" or !isset($_GET['merchid']) or $_GET['merchid'] == ""){
		header('Location: index.php?error=1');
	}
	$artistid = $_GET['artistid'];
	$merchid = $_GET['merchid'];

	if (!isset($_GET['bidddernumber']) or $_GET['bidddernumber'] == "" or !isset($_GET['biddername']) or $_GET['biddername'] == "" or !isset($_GET['bidvalue']) or $_GET['bidvalue'] == ""){
		header('Location: item.php?error=1&artistid='.$artistid.'&merchid='.$merchid);
	}
	$bidddernumber = $_GET['bidddernumber'];
	$biddername = $_GET['biddername'];
	$bidvalue = $_GET['bidvalue'];

	if (!ctype_digit($bidvalue)){
		header('Location: item.php?error=1&artistid='.$artistid.'&merchid='.$merchid);
	}

	if (!checkBidder($connection, $biddernumber)){
		header('Location: item.php?error=2&artistid='.$artistid.'&merchid='.$merchid);
	}

	$checkBid = checkPreBidInfo($connection, $artistid, $merchid, $bidvalue);
	if (!count( $checkBid )){
		header('Location: item.php?error=1&artistid='.$artistid.'&merchid='.$merchid);
	}
	$checkBid = $checkBid[0]

	if ($checkBid['AuctionEnd'] == "1" or $checkBid['MerchSold'] == "1" or $checkBid['bidcount'] >= $checkBid['AuctionCutoff']){
		header('Location: item.php?error=1&artistid='.$artistid.'&merchid='.$merchid);
	}
	if ($checkBid['currentbid'] >= $bidvalue or $checkBid['currentbid'] >= $checkBid['bidvalue']){
		header('Location: item.php?error=3&artistid='.$artistid.'&merchid='.$merchid);
	}

	if (submitBid($connection, $artistid, $merchid, $bidvalue, $name, $biddernumber)){
		header('Location: item.php?success=1&artistid='.$artistid.'&merchid='.$merchid);
	} else {
		header('Location: item.php?error=3&artistid='.$artistid.'&merchid='.$merchid);
	}
?>
