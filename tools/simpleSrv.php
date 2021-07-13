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

define("DRYRUN",false); // default: false

define("USER_PENDING_LIMIT",3);

define("DUMMY_PWD","dummy");

define("RESERVATION_STATUS",[
    "ERROR" => 0,
    "GOOD" => 1,
    "PENDING" => 2,
    "IDENTIFIED" => 3,
]);
// --------------------------------------------------
  // mail hash functions
  // --------------------------------------------------

function mailHash($cfg,$email) {
    $prefix="lerninseln";
    if (array_key_exists("mprefix",$cfg)) {
        $prefix=$cfg["mprefix"];
    }
    $h = hash("sha256",$prefix . $email);;
    mlog("Hashed mail: " . $h);
    return $h;
}


// --------------------------------------------------
  // ticket functions
  // --------------------------------------------------

// PDO statements
define ("DBCALL", array(
    "GET_USER_BY_NAME" => "SELECT * from user where username = ?;",
    "ADD_USER" => "insert into user set username = ?, pwdOrTotp = ?, emailOrHash = ?;",
    "SET_USER_ACCESS" => "update user set access = ? where id = ?;",
    "SET_USER_PENDINGS" => "update user set pendings = ? where id = ?;",
    "SET_USER_BOOKINGS" => "update user set bookings = ? where id = ?;",
    "SET_USER_PWD" => "update user set pwdOrTotp = ? where id = ?;",
    "SELECT_USER_BY_NAME" => "SELECT * from user where username = ? for update;",
    "GET_EVENT" => "SELECT * from event where id = ?;",
    "SELECT_EVENT" => "SELECT * from event where id = ? for update;",
    "UPDATE_EVENT" => "update event set avail = ? where id = ?;",
    "GET_PROVIDER" => "SELECT * from provider where id = ?;",
    "GET_PENDING" => "SELECT * from pending where user_id = ? and ticket_id = ?;",
    "GET_PENDING_BY_USER" => "SELECT * from pending where user_id = ?;",
    "ADD_PENDING" => "insert into pending set user_id = ?, ticket_id = ?, code = ?, count = ?, date = ?, remote = ?;",
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
    $r["status"] = RESERVATION_STATUS["ERROR"];

    // on get function, return all data
    if (strstr($mode, "GET_")) {
        $d = $sth->fetchAll();
        //mlog("Data:" . print_r($d, true));
        $r["data"] = $d;
        $r["status"] = RESERVATION_STATUS["GOOD"];
        return $r;
    } else {
        // special processing follows
        $r["data"] = array();
        switch ($mode) {
            case "SELECT_TICKET":
            case "SELECT_USER_BY_NAME":
            case "SELECT_EVENT":
                $d = $sth->fetchAll();
                $r["data"] = $d;
                $r["status"] = RESERVATION_STATUS["GOOD"];
                break;
            default:
                $d = array();
                $r["data"] = $d;
                break;
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


function reserveTicket($ticket,$count,$email,$pwd,$remote){
    // returns: status, array(email, code, label, text)
    /* procedure
        create user (ignore error if exists)
        get user. break if not exists
        start transaction
            select ticket, user and event for update
            check is user is pending for this ticket => break 1 if yes
            check if tickets avail => break 2 if not
            create reservation code, label
            add pending for user, code and ticket
            update user with access and pendings
            decrement ticket and event
        commit transaction
        if error somewhere => break 3
        text => OK
        return status + data(email, code, label, text)

        update 20210709: if pwd is set and matches user password
        then return code via response immediately and don't send email
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
    $r["status"] = RESERVATION_STATUS["ERROR"];
    $r["text"] = "Leider ein Fehler: versuche es bitte später noch einmal.";
    // create user name
    $user = mailHash($cfg,$email);
    $dummyPass = DUMMY_PWD; // not needed yet
    dbAccess($pdo,"ADD_USER",array($user,$dummyPass,$user)); // in this mode, name and email are same

    // start transaction
    $pdo->beginTransaction();

    //$u = dbAccess($pdo,"GET_USER_BY_NAME",array($user ));
    // select + lock user
    $u = dbAccess($pdo,"SELECT_USER_BY_NAME",array($user));
    //mlog("user" . print_r($u,true));
    if (count($u["data"]) == 0) {
        mlog("Missing user");
        $r["text"] = "Leider ein Problem mit der Anmeldung";
        $r["status"] = RESERVATION_STATUS["ERROR"];
        $pdo->rollback();
        return $r;
    }
    $uid = $u["data"][0]["id"];
    $pendings = $u["data"][0]["pendings"];
    mlog("Found user: " . $uid);

    // lock ticket
    $t = dbAccess($pdo,"SELECT_TICKET",array($ticket));
    mlog("Ticket: " . print_r($t,true));
    if (count($t["data"]) == 0) {
        mlog("Missing Ticket");
        $r["status"] = RESERVATION_STATUS["ERROR"];
        $r["text"] = "Leider ein Problem mit Buchung";
        $pdo->rollback();
        return $r;
    }
    $avail =  $t["data"][0]["avail"];

    // lock event for this ticket
    $e = dbAccess($pdo,"SELECT_EVENT",array($t["data"][0]["event_id"]));
    mlog("Event: " . print_r($e,true));
    if (count($e["data"]) == 0) {
        mlog("Missing Event");
        $r["status"] = RESERVATION_STATUS["ERROR"];
        $r["text"] = "Leider ein Problem mit Buchung";
        $pdo->rollback();
        return $r;
    }
    $evAvail =  $e["data"][0]["avail"];
    $evId =  $e["data"][0]["id"];

    // check pendings for user
    $p = dbAccess($pdo,"GET_PENDING_BY_USER",array($uid));
    mlog("Pending for user: " . count($p["data"]) . ", " . print_r($p,true));
    if (count($p["data"]) > USER_PENDING_LIMIT) {
        mlog("Too many pendings");
        $r["text"] = "Bitte schließe Deine Bestellungen ab";
        $r["status"] = RESERVATION_STATUS["PENDING"];
        $pdo->rollback();
        return $r;
    }
    // check pendings for ticket and user
    $p = dbAccess($pdo,"GET_PENDING",array($uid,$ticket));
    mlog("Pending: " . print_r($p,true));
    if (count($p["data"]) > 0) {
        mlog("Already pending");
        $r["text"] = "Du hast schon eine Reservierung";
        $r["status"] = RESERVATION_STATUS["PENDING"];
        $pdo->rollback();
        return $r;
    }

    // check ticket count and event avail count
    if (($avail < $count) || ($evAvail < $count)) {
        mlog("Sold out");
        $r["status"] = RESERVATION_STATUS["ERROR"];
        $r["text"] = "Leider kein Ticket mehr da";
        $pdo->rollback();
        return $r;
    }     

    // reservation code and label
    $code = random_int(100000,999999);
    $label = "Lerninsel Ticket"; // dummy
    // add pending
    $date = new DateTime();
    dbAccess($pdo,"ADD_PENDING",array($uid,$ticket,$code,$count,$date->getTimestamp(),$remote));
    // update user time
    dbAccess($pdo,"SET_USER_ACCESS",array($date->getTimestamp(),$uid));
    // update user pendings
    dbAccess($pdo,"SET_USER_PENDINGS",array($pendings+1,$uid));
    // update ticket
    dbAccess($pdo,"UPDATE_TICKET",array($avail - $count,$ticket)); // id is last ...
    // update event
    dbAccess($pdo,"UPDATE_EVENT",array($evAvail - $count,$evId)); // id is last ...

    // finally
    $pdo->commit();

    //$t = dbAccess($pdo,"GET_TICKET",array($ticket));
    //$r["email"] = "ak@akugel.de"; //$email;
    $r["email"] = $email;
    $r["code"] = $code;
    // check pwd 
    if (password_verify ($pwd,$u["data"][0]["pwdOrTotp"])) {
        $r["text"] = "Bitte sende den vorbereiteten Code ab";
        $r["status"] = RESERVATION_STATUS["IDENTIFIED"];
        mlog("User verified");
    } else {
        $r["text"] = "Bitte schau in Deinen Mails nach dem Code";
        $r["status"] = RESERVATION_STATUS["GOOD"];
        mlog("User not verified");
    }
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
        update user with access and tickets
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
    $r["status"] = RESERVATION_STATUS["ERROR"];
    $r["text"] = "";
    $d = array();

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

    $user = mailHash($cfg,$email);
    //$u = dbAccess($pdo,"GET_USER_BY_NAME",array($user ));
    $u = dbAccess($pdo,"SELECT_USER_BY_NAME",array($user));
    if (count($u["data"]) == 0) {
        mlog("Missing user");
        $r["text"] = "Leider ein Problem mit der Anmeldung";
        $pdo->rollback();
        return $r;
    }
    $uid = $u["data"][0]["id"];
    mlog("Found user: " . $uid);
    $bookings = $u["data"][0]["bookings"]; // need this later
    
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
    $qr = uniqid ("Lerninseln-Karlsruhe-") . "-" . $uid . "-" . $pid;
    // add qr
    $p = dbAccess($pdo,"ADD_QR",array($uid,$ticket,$qr,$pcnt));
    // delete pending
    dbAccess($pdo,"DELETE_PENDING",array($pid));
    //  update user
    $date = new DateTime();
    // update user time
    dbAccess($pdo,"SET_USER_ACCESS",array($date->getTimestamp(),$uid));
    // update user pendings
    dbAccess($pdo,"SET_USER_BOOKINGS",array($bookings+1,$uid));
    // update user passwd, if not set
    if ($u["data"][0]["pwdOrTotp"] == DUMMY_PWD) {
        mlog("Setting new pwd");
        $pwd = uniqid();
        $pwHash = password_hash($pwd,PASSWORD_BCRYPT);
        // verify with password_verify ( string $password , string $hash );
        dbAccess($pdo,"SET_USER_PWD",array($pwHash,$uid));
        // add pwd to return data
        $r["pwd"] = $pwd;
    } else {
        mlog("No pwd set");
        $r["pwd"] = ""; // no pwd if already registered
    }


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

    //$r["email"] = "ak@akugel.de"; //$email;
    $r["email"] = $email;
    $d["provider"] = $provider["name"];
    $d["name"] = $event["title"];
    $d["date"] = $event["date"];
    $d["time"] = $event["time"];
    $d["count"] = $pcnt;
    $d["event_id"] = $eid;  // add event for feedback
    $d["location1"] = $event["location1"];
    $d["location2"] = $event["location2"];

    $d["qr"] = $qr;
    $r["data"] = $d;
    $r["status"] = RESERVATION_STATUS["GOOD"];
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
        
        define("TABLES", array("config","provider","category","audience","event","feature","ticket","code"));
        
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
                // pwd can be blank here
                if (!(array_key_exists("ticket", $payload)) || 
                !(array_key_exists("email", $payload)) ||
                !(array_key_exists("pwd", $payload)) ||
                !(array_key_exists("count", $payload))
                    ) {
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

                // remote address
                $remote = "";
                if (array_key_exists("REMOTE_ADDR",$_SERVER))
                    $remote = $_SERVER["REMOTE_ADDR"];
                mlog("Remote: " . $remote);
                mlog("processing req 1");
                $r = reserveTicket($payload["ticket"],$payload["count"],$email,$payload["pwd"],$remote);
                // returns: status, email, code, label, text
                if ($r["status"] == RESERVATION_STATUS["GOOD"]) {
                    // send mail only when all OK
                    if (!DRYRUN) {
                        $mailing["request"] = $task;
                        $mailing["payload"] = array("email" => $r["email"], "code" => $r["code"]); 
                    } else {
                        mlog("Dryrun1 for " . $r["email"]);
                    }
                }
                // clear code after putting into mailing unless identified
                if ($r["status"] != RESERVATION_STATUS["IDENTIFIED"]) {
                    $r["code"] = 0;
                    mlog("Code cleared for " . $r["email"]);
                }
                //$result = array("data" => $r["data"],"status" => $r["status"],"text" => $r["text"]);
                $result = array("status" => $r["status"],"text" => $r["text"],"code" => $r["code"]);
                break;
            // ---------------------------------------------------
            // ---------------------------------------------------
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
                
                $to = $r["email"];
                $event = $r["data"];

                $qr = makeQr( hash("sha256",$r["data"]["qr"]));

                //$logo = file_get_contents("logo.png", false); //, stream_context_create($opciones_ssl));
                $logo = file_get_contents("insel-logo.png", false); //, stream_context_create($opciones_ssl));
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
                    $m = sendSmtp($cfg, $to, $subj, $msg, $pdf);
                    mlog("Send ticket returned " . $m);
                    } else {
                        mlog("Dryrun2 for " . $to);
                    }
                $result = array("data" => array(
                    "event" => $event, 
                    "qr" => $qr),
                    "text" => $r["text"],
                    "pwd" => $r["pwd"],
                    "status" => RESERVATION_STATUS["GOOD"]);
                break;
            // ---------------------------------------------------
            // ---------------------------------------------------
            case 9:  // remote log
                if (!(array_key_exists("text", $payload))) {
                    mlog("Req 9 keys missing");
                    $result = array("status" => 0);
                } else {
                    $result = array("status" => 1);
                    $text = $payload["text"];
                    mlog("Device: " . substr($text,0,256));
                }
                $task = 0; // clear request to indicate error
                break;

    
            // ---------------------------------------------------
            // ---------------------------------------------------
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

