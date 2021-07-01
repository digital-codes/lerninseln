<?php

require 'vendor/autoload.php';
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;


function makeQr($msg) {

	$qrCode = QrCode::create($msg)
    ->setEncoding(new Encoding('UTF-8'))
    ->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh())
    ->setSize(300)
    ->setMargin(20)
    ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
    ->setForegroundColor(new Color(0, 0, 0))
    ->setBackgroundColor(new Color(255, 255, 255));

	$writer = new PngWriter();
	$result = $writer->write($qrCode);

	// Generate a data URI to include image data inline (i.e. inside an <img> tag)
	$dataUri = $result->getDataUri();
	return $dataUri;
}	

//echo makeQr( hash("sha256","test123"));

?>

