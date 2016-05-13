<?php
	// SPECIAL CHARACTERS
	define('ESC', chr(27));	// Command Character
	define('LF', chr(10));  // Line feed (Or break line)

	define('BOLD', ESC . 'E ');
	define('SBOLD', ESC . 'F ');
	define('BIG', ESC . chr(14));
	define('SBIG', ESC .chr(20));
	define('UNDER', ESC . chr(45) . '3');	// TODO : TEST THIS
	define('SUNDER', ESC . chr(45) . '0');	// TODO : TEST THIS

	function sendToPrinter( $data ){
		for( $i=0; $i<strlen($data);$i++){
			file_put_contents(PRINTERPATH, substr($data, $i, 1));
			sleep(0.1);
		}

		file_put_contents(PRINTERPATH, LF);
	}
