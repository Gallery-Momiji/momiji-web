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
				$valid = validate_terms($value);
			break;
			default:
				$valid = validate_general($value);
			break;
		}
		
		if ($valid){
			$fields[$field_name] = $connection->real_escape_string($value);
		}
	}
	
	
	addBidder( $connection, $fields );
	
	
	function validate_general($value){
		return !empty($value);		
	}
	
	function validate_email($value){
		if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
			return true;
		}
		return false;
	}
	
	function validate_terms($value){
		if ($value !== '1'){
			header('Location: index.html?success=0');
		} else {
			$valid = true;
		}
	}
	header('Location: index.html?success=1');
	exit;
	