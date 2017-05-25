<?php
	require_once('config.php');
	require_once('inc/util.php');
	require_once('inc/mysql.php');

	if (!isset($_GET['id']) or $_GET['id'] == ""){
		die("ERROR : specify the receipt <b><i>id</i></b> as a get parameter!");
	}

	$receipt = $_GET['id'];
	$receiptInfo = getReceiptInfo($connection, $receipt);

	$time = $receiptInfo['timestamp'];
	$date = $receiptInfo['date'];
	$total = $receiptInfo['price'];
	$paid = $receiptInfo['paid'];
	$creditcardnumber= $receiptInfo['Last4digitsCard'];
	if ($creditcardnumber != '0'){
		//Not technically correct for AMEX, but good enough:
		$creditcardnumber = forceStringLength(forceStringLength($creditcardnumber,4,0,true),16,'*',true);
	}
	$change = floatval($receiptInfo['paid']) - floatval($receiptInfo['price']);

	$items = trim($receiptInfo['itemArray'], '#');
	$items = explode( '#', $items );

	$prices = trim($receiptInfo['priceArray'], '#');
	$prices = explode( '#', $prices);

	setReceiptAsPrinted($connection, $receipt);
?>
<!doctype html>
<html>
<head>
 <meta charset="utf-8">
 <title>Sales Receipt</title>
 <style>
  .invoice-box{
   font-size:16px;
   line-height:24px;
   font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
   color:#555;
  }

  .invoice-box table{
   width:100%;
   line-height:inherit;
   text-align:left;
  }

  .invoice-box table td{
   padding:5px;
   vertical-align:top;
  }
  .invoice-box table tr.top table td{
   padding-bottom:20px;
  }

  .invoice-box table tr.top table td.title{
   font-size:45px;
   line-height:45px;
   color:#333;
  }

  .invoice-box table td.information{
   width:60%;
  }

  .invoice-box table tr td:nth-child(3){
   text-align:right;
  }


  .invoice-box table tr.heading td{
   background:#eee;
   border-bottom:1px solid #ddd;
   font-weight:bold;
  }

  .invoice-box table tr.details td{
   padding-bottom:20px;
  }

  .invoice-box table tr.item td{
   border-bottom:1px solid #eee;
  }

  .invoice-box table td.moreinfo{
   text-align:right;
  }

  .invoice-box table tr.total td{
   border-bottom:2px solid #eee;
   font-weight:bold;
  }

  .invoice-box table tr.total td:nth-child(2){
   text-align:right;
  }

  .invoice-box table tr.paid td{
   border-bottom:2px solid #eee;
  }

  .invoice-box table tr.paid td:nth-child(2){
   text-align:right;
  }

  @media only screen and (max-width: 600px) {
   .invoice-box table tr.top table td{
    width:100%;
    display:block;
    text-align:center;
   }

   .invoice-box table td.information table td{
    width:100%;
    display:block;
    text-align:center;
   }
  }
 </style>
</head>

<body>
 <div class="invoice-box">
  <table cellpadding="0" cellspacing="0">
   <tr class="top">
    <td colspan="2">
     <table>
      <tr>
       <td class="title">
        <img src="header.jpg" width=100%>
       </td>
      </tr>
     </table>
    </td>
   </tr>
   <tr>
    <td colspan="2">
     <table>
      <tr>
       <td class="information">
        <b>Gallery Momiji</b><br>
        Anime North<br>
        <i>www.animenorth.com</i>
       </td>
       <td>
        <table>
         <tr>
          <td>
           <b>Receipt Number:</b>
          </td>
          <td class="moreinfo">
<?php
	echo "           " . $receipt . "\n";
?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Date:</b>
          </td>
          <td class="moreinfo">
<?php
	echo "           " . $date . "\n";
?>
          </td>
         </tr>
         <tr>
          <td>
           <b>Time:</b>
          </td>
          <td class="moreinfo">
<?php
	echo "           " . date('H:i', strtotime($time)) . "\n";
?>
          </td>
         </tr>
        </table>
       </td>
      </tr>
     </table>
    </td>
   </tr>
   <tr>
   <td>
    <br>
   </td>
   </tr>
   <tr class="heading">
    <td>
     Item number
    </td>
    <td>
     Description
    </td>
    <td>
     Price
    </td>
   </tr>
<?php
	foreach ($items as $key => $item ){
		if (strlen($item)==0){
			//WARNING : Found an empty entry in receipt #$receiptID, ignoring
			continue;
		}
		echo '   <tr class="item"><td>' . $item . "</td>\n";
		$identifier = evalUID($item);
		if (isGS($item)){
			$temp = getGSMerchInfo($connection, $identifier['artist'], $identifier['piece']);
			echo '   <td>' . $temp['PieceTitle'] . "</td>\n";
		} else {
			$temp = getMerchInfo($connection, $identifier['artist'], $identifier['piece']);
			echo '   <td>' . $temp['MerchTitle'] . "</td>\n";
		}
		echo '   <td>$' . number_format($prices[$key],2) . "</td></tr>\n";
	}
?>
   <tr>
    <td class="information">
    </td>
    <td colspan="2">
     <table>
      <tr class="total">
       <td>
        Total:
       </td>
       <td>
<?php
	echo "        $" . number_format($total,2) . "\n";
?>
       </td>
      </tr>
      <tr class="paid">
       <td>
<?php
	if ($creditcardnumber == '0'){
		echo "        Paid (Cash):\n";
	} else {
		echo "        Paid (Credit):\n";
	}
?>
       </td>
       <td>
<?php
	echo "        $" . number_format($paid,2) . "\n";
?>
       </td>
      </tr>
      <tr class="paid">
       <td>
<?php
	if ($creditcardnumber == '0'){
		echo "        Change:\n";
	} else {
		echo "        Credit Card Number:\n";
	}
?>
       </td>
       <td>
<?php
	if ($creditcardnumber == '0'){
		echo "        $" . number_format($change,2) . "\n";
	} else {
		echo "        " . $creditcardnumber . "\n";
	}
?>
       </td>
      </tr>
     </table>
    </td>
   </tr>
  </table>
 </div>
</body>
</html>

