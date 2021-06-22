<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

/* fill database paramteres in config.ini */
/*$cfg = parse_ini_file("../../files/iot/config.ini", false);*/
$cfg = parse_ini_file("config.ini", false);

/* we can check parameters via a filter like so: */
/* multiple parms: see https://www.php.net/manual/de/function.filter-input-array.php */
/* for testing with http, call like:
http GET  "http://127.0.0.1:8080" sens==123 last==2
When add_empty (3. parm) is true, all parms will be returned
missing are null
values failing the filter condition (e.g. validate int) are FALSE
*/

$parms = array(
	"table" => FILTER_VALIDATE_STRING
);

$args = filter_input_array(INPUT_GET, $parms,TRUE);

if ($args & ($args["table"] !== Null)) {
	$table = $args["table"];
} 

define("TABLES", array("provider","category","audience","event","ticket","code"));

if (array_search($table,TABLES) === false) {
	header("HTTP/1.1 400 Bad request");
	exit();
}

/*
 missing or wrong paramters are False or Null. Must check with triple ===
*/
/*
if (!$args) 
	echo "No args".PHP_EOL;
else {
	print_r($args);
	// the following works only with triple === 
	if ($args["last"] === 0) echo "Last 0" . PHP_EOL;
	if ($args["sens"] === 0) echo "Sens 0" . PHP_EOL;
	if ($args["days"] === 0) echo "days 0" . PHP_EOL;
}
exit();
*/
/*
$last = ($args  & ($args["days"] === 0));

// default period: 14 
$per = ($args  & ($args["days"] > 0)) ? $args["days"] : 14; // days 
*/
/*
// date computation
$fmt = "Y-m-d h:m:s";
date_default_timezone_set("Europe/Berlin");
// now
$d0 = date($fmt);
//echo "Now: " . $d0 . PHP_EOL;
// target date
$d1 = date_create($d0);
date_sub($d1, date_interval_create_from_date_string($per . " days"));
$d2 = date_format($d1, $fmt);
//echo "New: " . $d2 . PHP_EOL;
*/

// setting utf-8 here is IMPORTANT !!!!
$pdo = new PDO('mysql:host=' . $cfg["dbserv"] . ';dbname=' . $cfg["dbname"] , $cfg["dbuser"], $cfg["dbpass"],
	array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );

$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
/* $query = "SELECT * from sensors order by `index` asc"; */
/*$query = "SELECT id,count,co2,bat,pres,hum,temp,light,rssi,rfu,date,pkt,rep from sensors order by `index` asc"; */

$query = "SELECT * from " . $table;

/* options to select sensor and to get only the last entry */
/*	
if ($args & ($args["sens"] !== Null) & ($args["sens"] !== False)) 
	$query .= " where id = " . $args["sens"];
else
	$query .= " where id >= 0"; // defaults to all

// period
$query .= " and `date` > \"" . $d2 . "\"";

$query .= " order by `date`";

if ($last) 
	$query .= " desc limit 1";
else
	$query .= " asc";
*/
$statement = $pdo->query($query);

$data = array();
foreach ($statement as $row) {
	//echo("row").PHP_EOL;
	//print_r($row);
	array_push($data,$row);
}
//print_r($data);
//$result = json_encode($data, JSON_INVALID_UTF8_SUBSTITUTE);
//$result = json_encode($data);
//echo($result);
echo json_encode($data);

?>
