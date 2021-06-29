<?php
header("Access-Control-Allow-Methods: GET, HEAD, POST, PUT, DELETE, CONNECT, OPTIONS, TRACE, PATCH");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access-control-allow-origin, headers, origin, callback");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");
//header("Content-Type: */*;encoding=gzip, deflate, br");
//https://ionicframework.com/docs/troubleshooting/cors
  // --------------------------------------------------
  // log function
  // --------------------------------------------------
  define("LOG", "srv.log");
  define("LPRIO", 0); // minimal log priority

  // log function to file
  function mlog($msg, $prio = 0)
  {
      if ($prio >= LPRIO) {
          $ts = date(DATE_RFC2822);
          file_put_contents(LOG, $ts . " : " . $msg . PHP_EOL, FILE_APPEND);
      }
  }

/* fill database paramteres in config.ini */
/*$cfg = parse_ini_file("../../files/iot/config.ini", false);*/
//$cfg = parse_ini_file("/home/akugel/files/lerninseln/config.ini", false);
//$cfg = parse_ini_file("config.ini", false);

// ini file on uberspace is elsewhere
$cfg = array();
//$cfg = parse_ini_file("/home/akugel/files/kdg/kdg.ini",false);
// $cfg = parse_ini_file("kdg.ini",false);
try {
//	mlog("SRV " . print_r($_SERVER,true));
    if (!isset($_SERVER['HTTP_HOST']) or !isset($_SERVER['HTTPS'])) {
        $cfg = parse_ini_file("config.ini", false);
        $cfg["local"] = true;
	mlog("Local config");
    } else {
		// uberspace
        $cfg = parse_ini_file("/home/akugel/files/lerninseln/config.ini", false);
        //$cfg = parse_ini_file("news.ini", false);
        $cfg["local"] = false;
	mlog("Host config");
    }

} catch (Exception $e) {
    die("Config Error");
}


$meth = $_SERVER["REQUEST_METHOD"];
mlog("Method: " . $meth);

$result = array();

switch ($meth) {
    case "GET":
        mlog("GET");
		$parms = array("table" => FILTER_SANITIZE_STRING);
        $args = filter_input_array(INPUT_GET, $parms, true);

        if ($args & ($args["table"] !== null)) {
            $table = $args["table"];
        }
        
        define("TABLES", array("provider","category","audience","event","ticket","code"));
        
        if (array_search($table, TABLES) === false) {
			mlog("Invalid table");
            header("HTTP/1.1 400 Bad request");
        } else {
			try {
                // setting utf-8 here is IMPORTANT !!!!
                $pdo = new PDO(
                    'mysql:host=' . $cfg["dbserv"] . ';dbname=' . $cfg["dbname"] ,
                    $cfg["dbuser"],
                    $cfg["dbpass"],
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
                );
            } catch  (Exception $e) {
					mlog("DB error",9);
					die("DB Error");
			}
            
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            /* $query = "SELECT * from sensors order by `index` asc"; */
            /*$query = "SELECT id,count,co2,bat,pres,hum,temp,light,rssi,rfu,date,pkt,rep from sensors order by `index` asc"; */
            
            $query = "SELECT * from " . $table;
            
            $statement = $pdo->query($query);
            
            foreach ($statement as $row) {
                //echo("row").PHP_EOL;
                //print_r($row);
                array_push($result, $row);
            }
        }
		break;
        
        // no break
    default:
        mlog("Other");
        break;
}

echo json_encode($result);

?>

