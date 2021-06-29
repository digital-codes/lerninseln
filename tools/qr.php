<?php
/*
New library: endroid. PHP7.1 requires 3.3.0
https://github.com/endroid/qr-code/tree/3.3.0

PHP7.2 can use latest

*/

	require 'vendor/autoload.php';

	$msg = hash("sha256","test123");
	echo $msg . PHP_EOL;

	use Endroid\QrCode\Color\Color;
	use Endroid\QrCode\Encoding\Encoding;
	use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
	use Endroid\QrCode\QrCode;
	use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
	use Endroid\QrCode\Writer\PngWriter;
	
	$qrCode = QrCode::create($msg)
    ->setEncoding(new Encoding('UTF-8'))
    ->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh())
    ->setSize(300)
    ->setMargin(20)
    ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
    ->setForegroundColor(new Color(0, 0, 0))
    ->setBackgroundColor(new Color(255, 255, 255));

	// Save it to a file
	//$file = "qrcode_" + (string)getEpoche() + ".png"
	$file = __DIR__."/qrcode.png";
	$writer = new PngWriter();
	$result = $writer->write($qrCode);

	/*
	header('Content-Type: '.$result->getMimeType());
	echo $result->getString();
	*/
	// Save it to a file
	$result->saveToFile(__DIR__.'/qrcode.png');

	// Generate a data URI to include image data inline (i.e. inside an <img> tag)
	$dataUri = $result->getDataUri();
	echo $dataUri;


?>

