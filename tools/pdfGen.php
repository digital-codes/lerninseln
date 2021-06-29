<?php 
// https://github.com/dompdf/dompdf
// somewhere early in your project's loading, require the Composer autoloader
// see: http://getcomposer.org/doc/00-intro.md
require 'vendor/autoload.php';

require_once "mail.php" ;

// reference the Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;

function render_php($path,$event)
{
    ob_start();
    include($path);
    $var=ob_get_contents(); 
    ob_end_clean();
    return $var;
}


// options first
$options = new Options();
$options->set('isRemoteEnabled', TRUE);
// maybe ...
$options->setIsHtml5ParserEnabled(true);
// instantiate and use the dompdf class
$dompdf = new Dompdf($options);
$contxt = stream_context_create([ 
    'ssl' => [ 
        'verify_peer' => FALSE, 
        'verify_peer_name' => FALSE,
        'allow_self_signed'=> TRUE
    ] 
]);
$dompdf->setHttpContext($contxt);

$img = file_get_contents("qr1.png", false); //, stream_context_create($opciones_ssl));
$img_base_64 = base64_encode($img);
$img_data = 'data:image/png;base64,' . $img_base_64;


$template = "pdfTemplate.php";
$event = array();
$event["name"] = "Extra Veranstaltung";
$event["date"] = "2021-07-20";
$event["time"] = "19:00";
$event["qrdata"] = $img_data;
$txt = render_php($template,$event);

// don't echo before render!
//echo $txt;

$dompdf->loadHtml($txt);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

$pdf = $dompdf->output();

$msg = composeMsg("Mail message", $pdf);

print_r($msg);

$to = "ak@akugel.de";
$subj = "Dein Lerninsel Ticket";
$msg = "Vielen Dank, dass Du an unserer Veranstaltung teil nimmst. Hier ist Dein Ticket." . PHP_EOL. PHP_EOL;
$msg .= "Du kannst es ausdrucken und mitbringen. Oder das Ticket auf Deinem Smartphone anzeigen."  . PHP_EOL;
$msg .=  PHP_EOL . "--" . PHP_EOL . "Das Lerninsel Team"  . PHP_EOL;


$cfg = array(
    "smtphost" => "rex23.flatbooster.com",
    "smtpport" => "587",
    "from" => "info@ok-lab-karlsruhe.de",
    "fromName" => "OK Lab Karlsruhe",
    "smtpuser" => "",
    "smtppass" => ""
);

$r = sendSmtp($cfg,$to, $subj, $msg, $pdf);

echo "Send with pdf returned: " . $r . PHP_EOL;

$subj = "Lerninsel Ticket Code";
$msg = "Bitte gib diesen Code zur Bestätigung für das Ticket ein:" . PHP_EOL. PHP_EOL;
$msg .= random_int(100000,999999)  . PHP_EOL . PHP_EOL;
$msg .= "Wenn Du kein Ticket bestellt hast, kannst Du diese Mail ignorieren."  . PHP_EOL;
$msg .=  PHP_EOL . "--" . PHP_EOL . "Das Lerninsel Team"  . PHP_EOL;

$r = sendSmtp($cfg,$to, $subj, $msg);

echo "Send without pdf returned: " . $r . PHP_EOL;



?>
