<?php
	function evalUID( $UID ){
		return array( 'artist' => substr($UID, 2,3), 'piece' => substr($UID,6,3) );
	}

	// identified wether or not an item is Gallery store or not.
	function isGS( $UID ){
		return (bool)(strpos(strtolower($UID), 'pn') === 0);
	}

	function isAN( $UID ){
		return (bool)(strpos(strtolower($UID), 'an') === 0);
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
