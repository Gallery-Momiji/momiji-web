<?php
	function generateReceiptInfo( $connection,  $receiptID ){
		$info = getReceiptInfo($connection, $receiptID);

		$sales = array();

		$items = trim($info['itemArray'], '#');
		$items = explode( '#', $items );

		$prices = trim($info['priceArray'], '#');
		$prices = explode( '#', $prices);

		foreach ($items as $key => $item ){
			if (strlen($item)==0){
				echo "WARNING : Found an empty entry in receipt #$receiptID, ignoring...\n";
				continue;
			}
			$identifier = evalUID($item);
			if (isGS($item)){
				$temp = getGSMerchInfo($connection, $identifier['artist'], $identifier['piece']);
				$sales[] = forceStringLength($item . LF. substr($temp['PieceTitle'], 0, 16), 26). " $" . number_format($prices[$key],2) ;
			} else {
				$temp = getMerchInfo($connection, $identifier['artist'], $identifier['piece']);
				$sales[] = forceStringLength($item . LF. substr($temp['MerchTitle'], 0, 16), 26). " $" . number_format($prices[$key],2) ;
			}
		}

		return $sales;
	}

	function evalUID( $UID ){
		return array( 'artist' => substr($UID, 2,3), 'piece' => substr($UID,6,3) );
	}

	// identified wether or not an item is Gallery store or not.
	function isGS( $UID ){
		return (bool)(strpos(strtolower($UID), 'pn') === 0);
	}

	function forceStringLength( $str, $len, $char=' ', $fill_before = false ){
		if ( strlen($str) > $len) {
			return substr($str,0,$len);
		} else {
			$spaces = $len-strlen($str);
			if ($fill_before){
				return str_repeat($char, $spaces) . $str;
			} else {
				return $str . str_repeat($char, $spaces);
			}
		}
	}

	// checks if an item code belongs to a certain artist:
	// $item - What item we're checking (Ex : AN001-002, PN004-123);
	// $id   - Artist ID number
	function compareItemCodeWithID($item, $id){
		$temp_artist_id = forceStringLength($id, 3, 0, true);
		return ( strpos($item, (isGS($item) ? "PN" : "AN") . $temp_artist_id) === 0 ? true : false);
	}
