<?php
	require_once('config.php');
	require_once('inc/util.php');
	require_once('inc/printer.php');
	require_once('inc/mysql.php');
	require_once('template/an2015.php');
	set_time_limit(0);

//		--- HIGH PRIORITY ---
//TODO : Code to set transaction as printed.
//TODO : TEST THE FUCK OUT OF THIS
// 		--- LOW PRIORITY ---
//TODO : Fix the chmod 777 fix, [LOW PRIORITY]
//TODO : Make a GIT for this. Desperately needed!
//TODO : Test this on raspbian. Needs to work there.
//TODO : Make a cron?

//	sendToPrinter($template);

while( true ) {
	$receipts = findUnprintedReceipts($connection);
	if (count( $receipts ) === 0 ){
		//echo "Nothing to print, sleeping...\n";
		sleep(2);
	} else {
		foreach($receipts as $receipt){
			$itemsSold = generateReceiptInfo($connection, $receipt);
			$receiptInfo = getReceiptInfo($connection, $receipt);
			$user = getUserInfo($connection, $receiptInfo['userID']);

			$user = $user['name'];
			$purchases = implode(LF . " ". str_repeat('*-', 15) . LF, $itemsSold);
			$transid = $receipt;
			$ttlitems = count($itemsSold);
			$total = $receiptInfo['price'];
			$paid = $receiptInfo['paid'];
			$change = floatval($receiptInfo['paid']) - floatval($receiptInfo['price']);

			$final_receipt = $template;
			$final_receipt = str_replace('%USER%'		, $user				, $final_receipt);
			$final_receipt = str_replace('%PURCHASES%'	, $purchases			, $final_receipt);
			$final_receipt = str_replace('%TRANSID%'	, $transid			, $final_receipt);
			$final_receipt = str_replace('%TTLITEMS%'	, $ttlitems			, $final_receipt);
			$final_receipt = str_replace('%TOTAL%'		, number_format($total,2)	, $final_receipt);
			$final_receipt = str_replace('%PAID%'		, number_format($paid, 2)	, $final_receipt);
			$final_receipt = str_replace('%CHANGE%'		, number_format($change,2)	, $final_receipt);

			sendToPrinter($final_receipt);
			setReceiptAsPrinted($connection, $receipt);
			sleep(5);	// so we don't print too many at a time!
		}
	}
	//generateReceiptInfo($connection, 105);
}
