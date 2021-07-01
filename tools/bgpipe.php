#!/usr/bin/php
<?php
require 'vendor/autoload.php';
require_once "pdfGen.php" ;
require_once "mlog.php";

//$handle = fopen('php://stdin', 'r');
$handle = fopen("lock.txt", 'r');
$r = fgets($handle, 4096);

mlog("Sleep start. " . $r);
sleep(15);
mlog("Sleep end. " . $r);


$cfg = parse_ini_file("config.ini", false);
//$cfg = parse_ini_file("/home/akugel/files/lerninseln/config.ini", false);

$to = "ak@akugel.de";

$subj = "Lerninsel Ticket Code";
$msg = "Bitte gib diesen Code zur Bestätigung für das Ticket ein:" . PHP_EOL. PHP_EOL;
$msg .= random_int(100000,999999)  . PHP_EOL . PHP_EOL;
$msg .= "Wenn Du kein Ticket bestellt hast, kannst Du diese Mail ignorieren."  . PHP_EOL;
$msg .=  PHP_EOL . "--" . PHP_EOL . "Das Lerninsel Team"  . PHP_EOL;

$r = sendSmtp($cfg,$to, $subj, $msg);
//echo "Send without pdf returned: " . $r . PHP_EOL . PHP_EOL;


mlog("Mail sent. " . $r);

exit(0);

?>

