<?php

	// TO REPLACE:
	// %TRANSID% - Transaction ID / Receipt No.
	// %PURCHASES% - obvious
	// %TTLITEMS% - number of total items sold
	// %TOTAL% - Obvious
	// %CHANGE% - Obvious
	// %USER%  - Person who processed this transaction.




	$template =   BIG . "****************" .SBIG . LF;
	$template .=  BIG . " GALLERY MOMIJI " .SBIG . LF;
	$template .=  BIG . "****************" .SBIG ;
	$template .=  LF.LF;
	$template .=  BIG . "TRANSACTION #%TRANSID%" .SBIG . LF;
	$template .=  '      ' . date('dS M Y, H:i') . LF;
	$template .=  '     Toronto Congress Center' . LF;
	$template .=  LF;
	$template .=  '***** Your Purchases Today *****' . LF;
	$template .=  '%PURCHASES%' . LF;
	$template .=  'ITEMS SOLD : %TTLITEMS%' . LF;
	$template .=   BIG . "****************" .SBIG . LF;
	$template .=   BIG . "TOTAL : $%TOTAL%" . SBIG . LF;
	$template .=   BIG . "PAID : $%PAID%" . SBIG . LF;
	$template .=   BIG . "CHANGE : $%CHANGE%" . SBIG . LF;
	$template .=   BIG . "****************" .SBIG . LF;
	$template .=   " Please let us know how we did:" . LF;
	$template .=   "      http://svy.mk/1FDgcig  " . LF;
	$template .=   BIG . "****************" .SBIG . LF;
	$template .=   LF . "Processed by : %USER%".LF;
	$template .=   LF.LF.LF.LF.LF;