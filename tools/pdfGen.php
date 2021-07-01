<?php 
// https://github.com/dompdf/dompdf
// somewhere early in your project's loading, require the Composer autoloader
// see: http://getcomposer.org/doc/00-intro.md
require 'vendor/autoload.php';

require_once "makeQr.php" ;

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

function pdfGen($event) {
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

    /*
    $img = file_get_contents("qr1.png", false); //, stream_context_create($opciones_ssl));
    $img_base_64 = base64_encode($img);
    $img_data = 'data:image/png;base64,' . $img_base_64;
    */

    $template = "pdfTemplateSmall.php";
    $txt = render_php($template,$event);

    // don't echo before render!
    //echo $txt;

    $dompdf->loadHtml($txt);

    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    $pdf = $dompdf->output();

    return $pdf;

}


// test
function testPdf() {

    $event = array();
    $event["name"] = "Extra Veranstaltung";
    $event["date"] = "2021-07-20";
    $event["time"] = "19:00";
    $event["count"] = "1";
    $event["location"] = "Digitallabor Rathaus Karlsruhe";
    $qr = makeQr( hash("sha256","test123"));
    $event["qrdata"] = $qr;

    $logo = file_get_contents("logo.jpg", false); //, stream_context_create($opciones_ssl));
    $logo_base_64 = base64_encode($logo);
    $event["logo"] = 'data:image/jpeg;base64,' . $logo_base_64;

    $pdf = pdfGen($event);
    file_put_contents("ticket.pdf",$pdf);
}



?>
