<?php
	// function list:
	// getReceiptInfo($conn, $receiptID);
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
		$database = queryDatabase($connection,"select `ArtistName`,`ArtistDue`,`ArtistASCommission`,`ArtistGSCommission` from `artists` where `ArtistID`= ". $connection->real_escape_string($artistID)  .";");
		if (!count($database)){
			die("ERROR : Attempted to query artist with ID $artistID but nothing was returned");
		} else {
			return $database[0];
		}
	}

	function getArtistASCommission($connection, $item){
		$temp_artist_id = (INT)substr($item,2,3);
		$database = queryDatabase($connection,"select `ArtistASCommission` from `artists` where `ArtistID`= ". $connection->real_escape_string($temp_artist_id)  .";");
		if (!count($database)){
			die("ERROR : Attempted to query artist with ID $temp_artist_id but nothing was returned");
		} else {
			return $database[0]['ArtistASCommission'];
		}
	}

	function getArtistGSCommission($connection, $item){
		$temp_artist_id = (INT)substr($item,2,3);
		$database = queryDatabase($connection,"select `ArtistGSCommission` from `artists` where `ArtistID`= ". $connection->real_escape_string($temp_artist_id)  .";");
		if (!count($database)){
			die("ERROR : Attempted to query artist with ID $temp_artist_id but nothing was returned");
		} else {
			return $database[0]['ArtistGSCommission'];
		}
	}

	function getReceiptInfo( $connection, $receiptID ){
		$database = queryDatabase( $connection, "select `userID`,`price`,`paid`,`itemArray`,`priceArray`,`timestamp`,`date`,`Last4digitsCard` from `receipts` where `id` = $receiptID;" );
		if (!count( $database ) ){
			die("ERROR : Attempted to query info for receipt #$receiptID but nothing was returned");
		}
		return $database[0];
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
		$database = queryDatabase( $conn, "Select `price`,`itemArray`,`priceArray`,`isGalleryStoreSale`,`isAuctionSale`,`isQuickSale`,`Last4digitsCard`,`date` from `receipts` order by `date`;" );
		return $database;
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

	function findFees($connection){
		$database = queryDatabase( $connection, "select `ArtistPaid`,`ArtistDue`,`ArtistcheckOut` from `artists`;" );
		return $database;
	}

	function getItemsForBidding($conn){
		$database = queryDatabase( $conn, "SELECT `ArtistID`,`MerchID`,`MerchTitle`,`AuctionEnd` FROM `merchandise` CROSS JOIN `options` WHERE `MerchMinBid` > 0;" );
		return $database;
	}

	function getInfoForBidding($conn, $artistid, $merchid){
		$database = queryDatabase( $conn, "SELECT `ArtistID`,`MerchID`,`MerchTitle`,`MerchMinBid`,`MerchQuickSale`,`MerchMedium`,`MerchSold`,`AuctionEnd`,`AuctionCutoff` FROM `merchandise` CROSS JOIN `options` WHERE `MerchMinBid` > 0 AND `ArtistID` = $artistid AND `MerchID` = $merchid;" );
		return $database;
	}

	function getBidsForMerch($conn, $artistid, $merchid){
		$database = queryDatabase( $conn, "SELECT `bidderno`,`value` FROM `bids` WHERE `ArtistID` = $artistid AND `MerchID` = $merchid;" );
		return $database;
	}

	function checkPreBidInfo($connection, $artistid, $merchid, $bidvalue){
		$database = queryDatabase( $connection, "SELECT `MerchSold`,MAX(`value`) AS `currentbid`,COUNT(`value`) AS `bidcount`,`AuctionEnd`,`AuctionCutoff` FROM `merchandise` LEFT JOIN `bids` USING (`ArtistID`,`MerchID`) CROSS JOIN `options` WHERE `ArtistID` = $artistid AND `MerchID` = $merchid AND `MerchMinBid` BETWEEN 1 AND $bidvalue GROUP BY `ArtistID`,`MerchID`;" );
		return $database;
	}

	function checkBidder($connection, $biddernumber){
		$database = queryDatabase( $connection, "SELECT `bidderno` FROM `bidders` WHERE `bidderno` == $biddernumber;" );
		return count($database);
	}

	function submitBid($connection, $artistid, $merchid, $bidvalue, $name, $biddernumber){
		return queryDatabase( $connection, "INSERT INTO `bids` (`name`, `value`, `bidderno`, `ArtistID`, `MerchID`) VALUES ( \"".removeQuotes($name)."\", $bidvalue, $biddernumber, $artistid, $merchid );" );
	}
