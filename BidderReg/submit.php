<?php
	require_once('../config.php');
	require_once('../inc/mysql.php');

	//$connection

	$fields = array();
	var_dump($_POST);

	// simple validations
	foreach ($_POST as $field_name => $value ){
		$valid = false;

		switch($field_name){
			case 'eaddress':
				$valid = validate_email($value);
			break;
			case 'terms':
			case 'terms2':
				$valid = validate_terms($value);
			break;
			default:
				$valid = validate_general($value);
			break;
		}

		if ($valid){
			$fields[$field_name] = $connection->real_escape_string($value);
		} else {
			header('Location: index.html?error=1');
			exit;
		}
	}

	$biddernum = addBidder( $connection, $fields );

	function validate_general($value){
		return !empty($value);
	}

	function validate_email($value){
		return filter_var($value, FILTER_VALIDATE_EMAIL);
	}

	function validate_terms($value){
		return $value == 'on';
	}
	header('Location: success.html?bidder='.$biddernum);
	exit;
