#!/usr/bin/php
<?php
require 'vendor/autoload.php';
require_once "sendMail.php" ;
require_once "mlog.php";

//$handle = fopen('php://stdin', 'r');
$fp = fopen("lock.txt", 'r+');
$r = fgets($fp, 4096);
ftruncate($fp, 0); // kürze Datei after reading value
fclose($fp);

mlog("Sleep start. " . $r);

$data = json_decode($r,true);
if (!(array_key_exists("email", $data)) || !(array_key_exists("code", $data))) {
    mlog("Wrong parms");
    die();
}

sleep(15);
mlog("Sleep end. ");

$cfg = parse_ini_file("config.ini", false);
//$cfg = parse_ini_file("/home/akugel/files/lerninseln/config.ini", false);

$to = $data["email"];

$subj = "Lerninsel Ticket Code";
$msg = "Bitte gib diesen Code zur Bestätigung für das Ticket ein:" . PHP_EOL. PHP_EOL;
$msg .= $data["code"]  . PHP_EOL . PHP_EOL;
$msg .= "Wenn Du kein Ticket bestellt hast, kannst Du diese Mail ignorieren."  . PHP_EOL;
$msg .=  PHP_EOL . "--" . PHP_EOL . "Das Lerninsel Team"  . PHP_EOL;

$r = sendSmtp($cfg,$to, $subj, $msg);
//echo "Send without pdf returned: " . $r . PHP_EOL . PHP_EOL;


mlog("Mail sent. " . $r);

exit(0);

?>

