<?php
	// function list:
	// findUnprintedReceipts($conn);
	// getReceiptInfo($conn, $receiptID);
	// getUserInfo($conn, $id);
	// queryDatabase($conn, $sql);

	$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

	if ($connection->connect_errno){
		die('ERROR : Connection to DB failed : ' . $connection->connect_error);
	}

	function queryDatabase( $conn, $query ){
		$return = array();
		$result = $conn->query($query);
		if (!$result){
			return false;
		}
		if ($result === true){
			return true;
		}
		while($row = $result->fetch_assoc()){
			$return[] = $row;
		}
		mysqli_free_result($result);
		unset ($row);
		return $return;
	}

	function removeQuotes($str){
		return str_replace('"',"",$str);
	}

	function addBidder($conn, $fields){
		$values = '"'.removeQuotes($fields['name']).'","'.$fields['pnumber'].'","'.$fields['eaddress'].'","'.removeQuotes($fields['maddress']).'"';
		$query =  'INSERT INTO `bidders` (`name`, `phoneno`, `eaddress`, `maddress`) VALUES ( ' . $values .  '); ';
		$result = queryDatabase($conn, $query);
		if ( false === $result){
			header('Location: index.html?error=2&values='.$values);
			die();
		}

		$result = queryDatabase($conn, "SELECT LAST_INSERT_ID() as `id`;");
		if ( false === $result){
			header('Location: index.html?error=3');
			die();
		}

		return $result[0]['id'];
	}

	function getArtistInfo($connection, $artistID){
		$database = queryDatabase($connection,"select `ArtistName`,`ArtistDue` from `artists` where `ArtistID`= ". $connection->real_escape_string($artistID)  .";");
		if (!count($database)){
			die("ERROR : Attempted to query artist with ID $artistID but nothing was returned");
		} else {
			return $database[0];
		}
	}

	function listDaysFromReceipts($connection){
		$database = queryDatabase("select count(date(`date`)) as `total`, date(`date`) from `receipts` GROUP BY date(`date`);");
	}

	function getReceiptInfo( $connection, $receiptID ){
		$database = queryDatabase( $connection, "select `userID`,`price`,`paid`,`itemArray`,`priceArray`,`timestamp`,`date`,`Last4digitsCard` from `receipts` where `id` = $receiptID;" );
		if (!count( $database ) ){
			die("ERROR : Attempted to query info for receipt #$receiptID but nothing was returned");
		}
		return $database[0];
	}

	// Description : Finds unprinted receipts in database, returns in an array
	// Parameters :
	//	$connection - MySQLi connection object.
	function findUnprintedReceipts( $connection ){
		$database = queryDatabase( $connection, 'select `id` from `receipts` where `isPrinted` = 0;');
		$return = array();
		foreach ($database as $key => $value){
			$return[] = $value['id'] ;
		}
		unset ($database, $key, $value);
		return $return;
	}

	// Description : Returns information of a merchandise item based on his/her artist's ID number and its own ID number
	// Parameters :
	//	$connection 	- MySQLi connection object.
	//	$ArtistID	- ID number of Artist
	//	$MerchID	- ID number of the art piece
	function getMerchInfo($connection, $ArtistID, $MerchID){
		$database = queryDatabase( $connection, "select `MerchTitle` from `merchandise` where `ArtistID` = $ArtistID AND `MerchID` = $MerchID;" );
		if (!count( $database ) ){
			die("ERROR : Attempted to query merchandise info from AN$ArtistID-$MerchID but nothing was returned");
		}
		return $database[0];
	}

	function getGSMerchInfo($connection, $ArtistID, $PieceID){
		$database = queryDatabase( $connection, "select `PieceTitle` from `gsmerchandise` where `ArtistID` = $ArtistID AND `PieceID` = $PieceID;" );
		if (!count( $database ) ){
			die("ERROR : Attempted to query gallery store merchandise info from PN$ArtistID-$PieceID but nothing was returned");
		}
		return $database[0];
	}

	function getReceiptsSummary($conn){
		$database = queryDatabase( $conn, "Select `price`,`itemArray`,`priceArray`,`date` from `receipts`;" );
		return $database;
	}

	function getUserWithMostSales($conn){
		$database = queryDatabase( $conn, "Select `userID`, count(`userID`) as `sales` from `receipts` GROUP BY `userID` limit 0,1;");
		$userID = $database[0]['userID'];
		return getUserInfo($conn, $userID)['name'];
	}

	// Description : Returns information of a user based on his/her ID number.
	// Parameters :
	//	$connection 	- MySQLi connection object.
	//	$id		- ID number of user
	function getUserInfo( $connection, $id ){
		$return = queryDatabase( $connection, "select `userID`,`name` from `users` where `id` = $id;");
		if ( !count( $return ) ) {
			die("ERROR : Failed to query user info from user ID $id in function 'getUserInfo' of inc/mysql.php");
		}
		unset($query);
		return $return[0];
	}

	function setReceiptAsPrinted($connection, $receipt){
		$return = queryDatabase( $connection, "UPDATE `receipts` SET `isPrinted`=1 WHERE `id` = $receipt;");
	}

	function findSales($connection, $artistID){
		$query = "select `itemArray`,`priceArray` from `receipts` where `itemArray` LIKE '%AN".forceStringLength($artistID,3,0,true)."%' OR `itemArray` LIKE '%PN".forceStringLength($artistID,3,0,true)."%';";
		$database = queryDatabase($connection, $query);
		return $database;
	}

	function findAuctionItems($connection, $ArtistID){
		$database = queryDatabase( $connection, "select `MerchID`, `MerchTitle`,`MerchMinBid`,`MerchAAMB`,`MerchQuickSale` from `merchandise` where `ArtistID` = $ArtistID;" );
		return $database;
	}

	function findGSItems($connection, $ArtistID){
		$database = queryDatabase( $connection, "select `PieceID`, `PieceTitle`,`PiecePrice`,`PieceInitialStock`,`PieceSDC` from `gsmerchandise` where `ArtistID` = $ArtistID;" );
		return $database;
	}

	function findUnsoldAuctionItems($connection, $ArtistID){
		$database = queryDatabase( $connection, "select `MerchTitle`,`MerchID` from `merchandise` where `ArtistID` = $ArtistID AND `MerchSold` != 1;" );
		return $database;
	}

	function findUnsoldGSItems($connection, $ArtistID){
		$database = queryDatabase( $connection, "select `PieceTitle`,`PieceID`,`PieceStock` from `gsmerchandise` where `ArtistID` = $ArtistID AND `PieceStock` > 0;" );
		return $database;
	}
