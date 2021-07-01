#!/usr/bin/php
<?php
require_once "mlog.php";

//$handle = fopen('php://stdin', 'r');
$handle = fopen("lock.txt", 'r');
$r = fgets($handle, 4096);

mlog("Sleep start. " . $r);
sleep(15);
mlog("Sleep end. " . $r);
exit(0);

?>

