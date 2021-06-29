<?php 
// https://github.com/dompdf/dompdf
// somewhere early in your project's loading, require the Composer autoloader
// see: http://getcomposer.org/doc/00-intro.md
require 'vendor/autoload.php';

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
$event["name"] = "Exta Veranstaltung";
$event["date"] = "2021-07-20";
$event["time"] = "19:00";
$event["qrdata"] = $img_data;
$txt = render_php($template,$event);


$dompdf->loadHtml($txt);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();

?>
