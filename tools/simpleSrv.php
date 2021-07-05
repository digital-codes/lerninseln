<?php
header("Access-Control-Allow-Methods: GET, HEAD, POST, PUT, DELETE, CONNECT, OPTIONS, TRACE, PATCH");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access-control-allow-origin, headers, origin, callback, content-type");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");
//header("Content-Type: */*;encoding=gzip, deflate, br");
//https://ionicframework.com/docs/troubleshooting/cors

require_once "mlog.php";
require "makeQr.php";
require "pdfGen.php";
require "sendMail.php";

// --------------------------------------------------
// error reasons
// --------------------------------------------------
define("REASON", [
    "AUTH" => "AUTH",
    "KEY" => "KEY",
    "PAY" => "PAY",
    "REQ" => "REQ",
    "SERV" => "SERV",
    "SOLD" => "SOLD"]
);

define("DRYRUN",true); // default: false

// --------------------------------------------------
  // ticket functions
  // --------------------------------------------------

// PDO statements
define ("DBCALL", array(
    "GET_USER" => "SELECT * from user where username = ?;",
    "ADD_USER" => "insert into user set username = ?, pwdOrTotp = ?, emailOrHash = ?;",
    "SET_USER_ACCESS" => "update user set access = ? where id = ?;",
    "SET_USER_PWD" => "update user set pwdOrTotp = ? where id = ?;",
    "GET_EVENT" => "SELECT * from event where id = ?;",
    "GET_PROVIDER" => "SELECT * from provider where id = ?;",
    "GET_PENDING" => "SELECT * from pending where user_id = ? and ticket_id = ?;",
    "ADD_PENDING" => "insert into pending set user_id = ?, ticket_id = ?, code = ?, count = ?, date = ?;",
    "DELETE_PENDING" => "delete from pending where id = ?;",
    "GET_QR" => "SELECT * from code where user_id = ? and ticket_id = ?;",
    "ADD_QR" => "insert into code set user_id = ?, ticket_id = ?, label = ?, count = ?;",
    "GET_TICKET" => "SELECT * from ticket where id = ?;",
    "SELECT_TICKET" => "SELECT * from ticket where id = ? for update;",
    "UPDATE_TICKET" => "update ticket set avail = ? where id = ?;",
    "GET_TABLE" => "select * from ")
);

function dbAccess($pdo, $mode, $parms)
{
    if (!array_key_exists($mode, DBCALL)) {
        mlog("Invalid db mode: ", $mode);
        die();
    }
    //mlog("Parms " . print_r($parms,true));

    $query = DBCALL[$mode];
    // read table needs special handling. cannot use table name as parameter
    if (strstr($mode, "GET_TABLE")) {
        $query = DBCALL[$mode] . $parms[0] . ";";
    }

    mlog("Action: " . $query . ", " . print_r($parms,true));
    // prepare and execute request
    $sth = $pdo->prepare($query);
    $action = $sth->execute($parms);
    if (!$action) {
        mlog("Action Error");
        // add user may intentionally fail
        if (!$mode == "ADD_USER")
            die();
        else
            return array();
    }
    
    // default results
    $r = array();
    $r["status"] = 0;

    // on get function, return all data
    if (strstr($mode, "GET_")) {
        $d = $sth->fetchAll();
        //mlog("Data:" . print_r($d, true));
        $r["data"] = $d;
        $r["status"] = 1;
        return $r;
    } else {
        // special processing follows
        $r["data"] = array();
        switch ($mode) {
            case "SELECT_TICKET":
                $d = $sth->fetchAll();
                $r["data"] = $d;
                $r["status"] = 1;
                break;
            default:
                $d = array();
                $r["data"] = $d;
                break;
            /*    
            case "ADD_USER":
                $d = array();
                $r["data"] = $d;
                break;
            case "SET_USER_ACCESS":
                $d = array();
                $r["data"] = $d;
                break;
            case "SET_USER_PWD":
                $d = array();
                $r["data"] = $d;
                break;
            case "ADD_QR":
                $d = array();
                $r["data"] = $d;
                break;
            case "UPDATE_TICKET":
                $d = array();
                $r["data"] = $d;
                break;
            case "ADD_PENDING":
                $d = array();
                $r["data"] = $d;
                break;
            case "DELETE_PENDING":
                $d = array();
                $r["data"] = $d;
                break;
            */
            }
    }
    return $r;
}

function readTable($table){
    // returns: data
    global $cfg;

    try {
        // setting utf-8 here is IMPORTANT !!!!
        $pdo = new PDO(
            'mysql:host=' . $cfg["dbserv"] . ';dbname=' . $cfg["dbname"],
            $cfg["dbuser"],
            $cfg["dbpass"],
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
    } catch (Exception $e) {
        mlog("DB error", 9);
        die("DB Error");
    }
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    return dbAccess($pdo,"GET_TABLE",array($table));
}


function reserveTicket($ticket,$email){
    // returns: status, array(email, code, label, text)
    /* procedure
        create user (ignore error if exists)
        get user. break if not exists
        start transaction
            select ticket for update
            check is user is pending for this ticket => break 1 if yes
            check if tickets avail => break 2 if not
            create reservation code, label
            add pending for user, code and ticket
            update user with access
            decrement ticket
        commit transaction
        if error somewhere => break 3
        text => OK
        return status + data(email, code, label, text)
    */
    global $cfg;

    try {
        // setting utf-8 here is IMPORTANT !!!!
        $pdo = new PDO(
            'mysql:host=' . $cfg["dbserv"] . ';dbname=' . $cfg["dbname"],
            $cfg["dbuser"],
            $cfg["dbpass"],
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
    } catch (Exception $e) {
        mlog("DB error", 9);
        die("DB Error");
    }
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $r = array();
    $r["status"] = 0;
    $r["text"] = "";
    $d = array();
    $r["data"] = $d;
    // create user name
    $user = hash("sha256",$email);
    $pwd = "dummy"; // not needed yet
    dbAccess($pdo,"ADD_USER",array($user,$pwd,$user)); // in this mode, name and email are same
    $u = dbAccess($pdo,"GET_USER",array($user ));
    //mlog("user" . print_r($u,true));
    if (count($u["data"]) == 0) {
        mlog("Missing user");
        $r["text"] = "Leider ein Problem mit der Anmeldung";
        return $r;
    }
    $uid = $u["data"][0]["id"];
    mlog("Found user: " . $uid);

    // start transaction
    $pdo->beginTransaction();
    // lock ticket
    $t = dbAccess($pdo,"SELECT_TICKET",array($ticket));
    mlog("Ticket: " . print_r($t,true));
    if (count($t["data"]) == 0) {
        mlog("Missing Ticket");
        $r["text"] = "Leider ein Problem mit Buchung";
        $pdo->rollback();
        return $r;
    }
    $avail =  $t["data"][0]["avail"];

    // check pending
    $p = dbAccess($pdo,"GET_PENDING",array($uid,$ticket));
    mlog("Pending: " . print_r($p,true));
    if (count($p["data"]) > 0) {
        mlog("Already pending");
        $r["text"] = "Du hast schon eine Reservierung";
        $pdo->rollback();
        return $r;
    }
    // check ticket count
    if ($t["data"][0]["avail"] < 1) {
        mlog("Sold out");
        $r["text"] = "Leider kein Ticket mehr da";
        $pdo->rollback();
        return $r;
    }     
    // reservation code and label
    $code = random_int(100000,999999);
    $label = "Lerninsel Ticket"; // dummy
    // add pending
    $date = new DateTime();
    dbAccess($pdo,"ADD_PENDING",array($uid,$ticket,$code,1,$date->getTimestamp()));
    // update user time
    dbAccess($pdo,"SET_USER_ACCESS",array($date->getTimestamp(),$uid));
    // update ticket
    dbAccess($pdo,"UPDATE_TICKET",array($avail - 1,$ticket)); // id is last ...

    // finally
    $pdo->commit();

    //$t = dbAccess($pdo,"GET_TICKET",array($ticket));
    $d["email"] = "ak@akugel.de"; //$email;
    $d["label"] = $label;
    $d["code"] = $code;
    $r["data"] = $d; // update data
    $r["text"] = "Ticket ist reserviert";
    $r["status"] = 1;
    return $r;
}


function purchaseTicket($ticket,$email,$label){
    // returns: status, text, 
    /* procedure
        get user
        start transaction
            select ticket for update !! prevent other session to interfere
            check if pending for this ticket, user and code exists => break 0 if no
            create qr based upon pending id and user id, add qr
            delete pending
        commit transaction
        update qr with event data (outside transaction)
        if error somewhere => break
        text => OK
        return status + data(email, qr, text)
    */
    global $cfg;

    try {
        // setting utf-8 here is IMPORTANT !!!!
        $pdo = new PDO(
            'mysql:host=' . $cfg["dbserv"] . ';dbname=' . $cfg["dbname"],
            $cfg["dbuser"],
            $cfg["dbpass"],
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
    } catch (Exception $e) {
        mlog("DB error", 9);
        die("DB Error");
    }
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $r = array();
    $r["status"] = 0;
    $r["text"] = "";
    $d = array();

    $user = hash("sha256",$email);
    $u = dbAccess($pdo,"GET_USER",array($user ));
    if (count($u["data"]) == 0) {
        mlog("Missing user");
        $r["text"] = "Leider ein Problem mit der Anmeldung";
        return $r;
    }
    $uid = $u["data"][0]["id"];
    mlog("Found user: " . $uid);

    // start transaction
    $pdo->beginTransaction();
    // lock ticket
    $t = dbAccess($pdo,"SELECT_TICKET",array($ticket));
    mlog("Ticket: " . print_r($t,true));
    if (count($t["data"]) == 0) {
        mlog("Missing Ticket");
        $r["text"] = "Leider ein Problem mit Buchung";
        $pdo->rollback();
        return $r;
    }
    
    // check pending
    $p = dbAccess($pdo,"GET_PENDING",array($uid,$ticket));
    mlog("Pending: " . print_r($p,true));
    if (count($p["data"]) == 0) {
        mlog("No reservation");
        $r["text"] = "Du hast noch keine Reservierung";
        $pdo->rollback();
        return $r;
    }
    // check pending code
    if ($p["data"][0]["code"] != $label) {
        mlog("invalid reservation code");
        $r["text"] = "Der Code ist ungültig";
        $pdo->rollback();
        return $r;
    }
    $pid = $p["data"][0]["id"];
    $pcnt = $p["data"][0]["count"];
    // create qr
    $qr = uniqid ("Lerninseln-Karlsruhe") . "-" . $uid . "-" . $pid;
    // add qr
    $p = dbAccess($pdo,"ADD_QR",array($uid,$ticket,$qr,$pcnt));
    // delete pending
    dbAccess($pdo,"DELETE_PENDING",array($pid));

    // finally
    $pdo->commit();

    // need to collect event description
    $eid = $t["data"][0]["event_id"];
    $e = dbAccess($pdo,"GET_EVENT",array($eid));
    mlog("Event: " . print_r($e,true));
    if (count($e["data"]) == 0) {
        mlog("No event");
        $r["text"] = "Leider gibt es ein Problem. Versuche es später noch einmal";
        return $r;
    }
    $event = $e["data"][0];
    $provid = $event["provider_id"];
    $pv = dbAccess($pdo,"GET_PROVIDER",array($provid));
    mlog("Provider: " . print_r($pv,true));
    if (count($pv["data"]) == 0) {
        mlog("No provider");
        $r["text"] = "Leider gibt es ein Problem. Versuche es später noch einmal";
        return $r;
    }
    $provider = $pv["data"][0];

    $d["email"] = "ak@akugel.de"; //$email;
    $d["provider"] = $provider["name"];
    $d["name"] = $event["title"];
    $d["date"] = $event["date"];
    $d["time"] = $event["time"];
    $d["count"] = $pcnt;
    $d["location1"] = $event["location1"];
    $d["location2"] = $event["location2"];

    $d["qr"] = $qr;
    $r["data"] = $d;
    $r["status"] = 1;
    $r["text"] = "Buchung erfolgreich";
    return $r;
}


// --------------------------------------------------
  // log function
  // --------------------------------------------------

  /*
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
  */


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
$mailing = array("request" => 0);

switch ($meth) {
    case "GET":
        mlog("GET");
        $parms = array("table" => FILTER_SANITIZE_STRING);
        $args = filter_input_array(INPUT_GET, $parms, true);

        if ($args & ($args["table"] !== null)) {
            $table = $args["table"];
        }
        
        define("TABLES", array("config","provider","category","audience","event","ticket","code"));
        
        if (array_search($table, TABLES) === false) {
            mlog("Invalid table");
            header("HTTP/1.1 400 Bad request");
        }  else {
            $result = readTable($table);
        }
        break;

    case "POST":
        mlog("POST");
        $input = json_decode(file_get_contents('php://input'), true);
        mlog("Input: " . json_encode($input));
        // we expect a request type and a payload
        if (!(array_key_exists("request", $input)) || !(array_key_exists("payload", $input))) {
            mlog("Keys missing");
            $result = array("data" => array(),"text" => REASON["KEY"],"status" => 0);
            break;
        }
        $task = $input["request"];
        $payload = $input["payload"];
        switch ($task) {
            case 1:
                if (!(array_key_exists("ticket", $payload)) || !(array_key_exists("email", $payload))) {
                    mlog("Req 1 keys missing");
                    $result = array("data" => array(),"text" => REASON["KEY"],"status" => 0);
                    $task = 0; // clear request to indicate error
                    break;
                }

                $email = trim($payload["email"]);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    mlog("Invalid email");
                    $result = array("data" => array(),"text" => REASON["KEY"],"status" => 0);
                    $task = 0; // clear request to indicate error
                    break;
                }

                mlog("processing req 1");
                $r = reserveTicket($payload["ticket"],$email);
                // returns: status, email, code, label, text
                if ($r["status"] == 1) {
                    // send mail only when all OK
                    if (!DRYRUN) {
                        $mailing["request"] = $task;
                        $mailing["payload"] = $r["data"]; // extra data here, but doesn't matter 
                    }
                }
                //$result = array("data" => $r["data"],"status" => $r["status"],"text" => $r["text"]);
                $result = array("status" => $r["status"],"text" => $r["text"]);
                break;
            case 2:
                if (!(array_key_exists("ticket", $payload))
                || !(array_key_exists("email", $payload))
                || !(array_key_exists("code", $payload))
                ) {
                    mlog("Req 2 keys missing");
                    $result = array("data" => array(),"text" => REASON["KEY"],"status" => 0);
                    $task = 0; // clear request to indicate error
                    break;
                }

                $email = trim($payload["email"]);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    mlog("Invalid email");
                    $result = array("data" => array(),"text" => REASON["KEY"],"status" => 0);
                    $task = 0; // clear request to indicate error
                    break;
                }

                mlog("processing req 2");
                $r = purchaseTicket($payload["ticket"],$email,$payload["code"]);

                if ($r["status"] == 0) {
                    mlog("Booking failed");
                    $result = array("data" => array(),"text" => $r["text"],"status" => 0);
                    $task = 0; // clear request to indicate error
                    break;
                }
                
                $to = "ak@akugel.de";
                $event = $r["data"];

                $qr = makeQr( hash("sha256",$r["data"]["qr"]));

                $logo = file_get_contents("logo.png", false); //, stream_context_create($opciones_ssl));
                $logo_base_64 = base64_encode($logo);
                $event["logo"] = 'data:image/png;base64,' . $logo_base_64;
                $bg = file_get_contents("bg.jpg", false); //, stream_context_create($opciones_ssl));
                $bg_base_64 = base64_encode($bg);
                $event["bg"] = 'data:image/jpeg;base64,' . $bg_base_64;
            
                $pdf = pdfGen($event,$qr);
                // clear some fields after pdf gen
                $event["bg"] = "";
                $event["logo"] = "";
                $event["qr"] = "";
                $subj = "Dein Lerninsel Ticket";
                $msg = "Vielen Dank, dass Du an unserer Veranstaltung teilnimmst. Hier ist Dein Ticket." . PHP_EOL. PHP_EOL;
                $msg .= "Du kannst es ausdrucken und mitbringen. Oder das Ticket auf Deinem Smartphone anzeigen."  . PHP_EOL;
                $msg .=  PHP_EOL . "--" . PHP_EOL . "Das Lerninsel Team"  . PHP_EOL;
                if (!DRYRUN) {
                    $r = sendSmtp($cfg, $to, $subj, $msg, $pdf);
                    mlog("Send ticket returned " . $r);
                }
                $result = array("data" => array("text" => $r["text"], "event" => $event, "qr" => $qr),"status" => 1);
                break;
            default:
                mlog("Invalid request");
                $result = array("data" => array(),"text" => REASON["REQ"],"status" => 0);
                $task = 0; // clear request to indicate error
                break;
        }
        break;

    default:
        mlog("Other");
        $result = array("data" => array(),"text" => REASON["SERV"],"status" => 0);
        break;
}

echo json_encode($result);
ob_end_flush();

if ($mailing["request"] > 0) {
    // acquire lock
    //$lock = "/var/www/virtual/akugel/html/lerninseln/lock.txt";
    // don't echo anything here!
    // might need to create lockfile beforehand
    $lock = "lock.txt";
    $fp = fopen($lock, "r+");
    if (flock($fp, LOCK_EX)) { // exklusive Sperre
        ftruncate($fp, 0); // kürze Datei
        //$handle = popen('./a.php 2>&1', 'w');
        // strangely, this does not work with exec ...
        $w = json_encode($mailing["payload"]); //array("data" => "aslslfqölwfmqö"));
        fputs($fp, $w);
        fflush($fp);
        $h = popen('./bgpipe.php & >/dev/null', 'w');
        //fwrite($h,"test"); // consume some time
        // allow bgpip to truncate
        for ($i = 0;$i < 5; $i++) {
            clearstatcache();
            if (fstat($fp)[7] < 1) break;
            sleep(1);
        }
        fclose($h);
        flock($fp, LOCK_UN); // Gib Sperre frei
    } else {
        mlog("Lock failed",9);
    }

    fclose($fp);
}

