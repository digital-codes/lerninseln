<?php
header("Access-Control-Allow-Methods: GET, HEAD, POST, PUT, DELETE, CONNECT, OPTIONS, TRACE, PATCH");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access-control-allow-origin, headers, origin, callback, content-type");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");
//header("Content-Type: */*;encoding=gzip, deflate, br");
//https://ionicframework.com/docs/troubleshooting/cors



// --------------------------------------------------
  // log function
  // --------------------------------------------------

  define("LOG", "pay.log");
  define("LPRIO", 0); // minimal log priority
  // log function to file
  function mlog($msg, $prio = 0)
  {
      if ($prio >= LPRIO) {
          $ts = date(DATE_RFC2822);
          file_put_contents(LOG, $ts . " : " . $msg . PHP_EOL, FILE_APPEND);
      }
  }
  



mlog("Request: " . print_r($_SERVER,true));

$input = json_decode(file_get_contents('php://input'), true);
mlog("Input: " . print_r($input,true));

mlog("Post: " . print_r($_POST,true));

mlog("Get: " . print_r($_GET,true));

//header("Location: https://lerninseln.ok-lab-karlsruhe.de/tickets");
//header("Location: https://lerninseln.ok-lab-karlsruhe.de/shop");
header("Location: http://localhost:8100/shop");

die();

?>
