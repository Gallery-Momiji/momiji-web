<?php
	require_once('../config.php');
	require_once('../inc/mysql.php');

	if (isset($_GET['error']) and $_GET['error'] == "1"){
		//TODO use better error ouput than plain text
		echo "Unable to show selected item, please try again.";
	}
?>
