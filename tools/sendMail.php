<?php
//session_start();

require 'vendor/autoload.php';

// --------------------------------------------------
// email functions
// --------------------------------------------------

// send text and pdf data. pdf is contentm file name is "ticket.pdf"
function composeMsg($text, $pdf)
{

   // create attachment parms
    $atts = array("name" => "ticket.pdf", "data" => $pdf);
 
    // there are only two supported content types:
    // application/pdf and text/plain

    $mime_boundary = "-----=" . md5(time());

    // add lines to header for multipart after MIME-Version: 1.0 !
    $header = "Content-Type: multipart/mixed;\r\n";
    $header .= " boundary=\"".$mime_boundary."\"\r\n";
 
    // create content. text part is text/plain
    $content  = "This is a multi-part message in MIME format.\r\n\r\n";
    $content .= "--".$mime_boundary."\r\n";
    $content .= "Content-Type: text/plain; charset=utf-8\r\n";
    $content .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $content .= $text."\r\n\r\n";
 
    // create attachement
 
    // load file, b64 encode and make 76 chacter long lines
    $data = chunk_split(base64_encode($atts['data']));
    $content.= "--".$mime_boundary."\r\n";
    $content.= "Content-Type: application/pdf;\r\n";
    //$content.= "\t name=\"".$a['name']."\"\r\n";
    $content.= "\t name=\"" . "=?utf-8?b?".base64_encode($atts['name'])."?=" . "\";\r\n";
    $content.= "Content-Transfer-Encoding: base64\r\n";
    $content.= "Content-Disposition: attachment;\r\n";
    // $content.= "\tfilename=\"".$a['name']."\";\r\n\r\n";
    $content.= "\tfilename=\"" . "=?utf-8?b?".base64_encode($atts['name'])."?=" . "\";\r\n\r\n";
    $content.= $data."\r\n\r\n";

    $content .= "--".$mime_boundary."--";

    $msg = array();
    $msg["hdr"] = $header;
    $msg["body"] = $content;
   
    return $msg;
}


// send mail, plain text in body, optional pdf
function sendSmtp($cfg, $to, $subj, $body, $pdf = Null)
{
    //global $cfg;
    // set time zone for date usage later on
    date_default_timezone_set("Europe/Berlin");

    $host = $cfg["smtphost"];
    $port = $cfg["smtpport"];
    $from = $cfg["from"];
    $fromName = $cfg["fromName"];

    // test only
    //$to = "ak@akugel.de";

    try {
        //$socket_options = array('ssl' => array('verify_peer_name' => true));
        // for flatbooster we need to disable verify peer options
        $socket_options = array('ssl' => array('verify_peer_name' => false, 'verify_peer' => false));

        // 'crypto_method' => STREAM_CRYPTO_METHOD_TLS_CLIENT
        /* Create a new Net_SMTP object. */
        /* set timeout to 15 seconds (from 0) */
        /* must be smaller than fetch timeout on client ! */
        /* param after port is local host name */
        if (! ($smtp = new Net_SMTP($host, $port, null, false, 15, $socket_options))) {
            // if (! ($smtp = new Net_SMTP($host, $port, "spitaler.uberspace.de", false, 15, $socket_options))) {
            throw new Exception("Unable to instantiate Net_SMTP object\n");
        }

        // Debug-Modus einschalten
      $smtp->setDebug(false, "smtpDebugHandler"); // without handler, goes to stdout. doesn't help much probably

    /* Connect to the SMTP server. */
    if (PEAR::isError($e = $smtp->connect())) {
        throw new Exception($e->getMessage() . "\n");
    }

    // authenticate
    if (PEAR::isError($e = $smtp->auth($cfg["smtpuser"], $cfg["smtppass"]))) {
        //mlog("Auth failed " . $cfg["smtpuser"] . "," . $cfg["smtppass"],99);
        throw new Exception("Unable to authenticate: " . $e->getMessage(), 99);
    }

        /* Send the 'MAIL FROM:' SMTP command. */
        if (PEAR::isError($smtp->mailFrom($from))) {
            throw new Exception("Unable to set sender to <$from>\n");
        }
        /* Address the message to each of the recipients. */
        if (PEAR::isError($res = $smtp->rcptTo($to))) {
            throw new Exception("Unable to add recipient <$to>: " . $res->getMessage() . "\n");
        }

        // set headers
        // options: "Content-Transfer-Encoding: 8bit \r\n";
        // "MIME-Version: 1.0 \r\n";
        $header = "From: " . $fromName . " <" . $from. ">\r\n";
        $header .= "To: ".$to ."\r\n";
        $date = date(DATE_RFC2822);
        $header .= "Date: ".$date . "\r\n";
        // subject needs to be encoded separately
        $header .= "Subject: "."=?utf-8?b?".base64_encode($subj)."?="."\r\n";
        $header .= "MIME-Version: 1.0\r\n";

        if ($pdf != Null) {
            // if we have attachements, compose message
            $msg = composeMsg($body, $pdf);
            $header .= $msg["hdr"];
            $body = $msg["body"];
        } else {
            $header .= "Content-Encoding: 8bit\r\n"; // don't use quoted printable here ....
            $header .= "Content-Type: text/plain; charset=utf-8\r\n";
            // leave body unchanged
        }

        $header .= "\r\n"; // terminating header

        /* Send the message. */
        
        if (PEAR::isError($smtp->data($body, $header))) {
            throw new Exception("Unable to send email\n");
        }



        /* Disconnect from the SMTP server. */
        $smtp->disconnect();
    } catch (Exception $e) {
        return false;
    }

    return true;
}


?>
