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
?>
