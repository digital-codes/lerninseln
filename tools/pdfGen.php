<?php 
// https://github.com/dompdf/dompdf
// somewhere early in your project's loading, require the Composer autoloader
// see: http://getcomposer.org/doc/00-intro.md
require 'vendor/autoload.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;

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


$txt = "
<html>
<head>
<style>
h1 {
    line-height: 2em;
    font-size: 1.5em;
    color: \"#f00\";
    margin-bottom: 1em;
}
p {
    font-size:1em;
}

.im {
    width: 400px;
    margin: 2em;
}
</style>
</head>
<body>
<h1>hello world</h1>
<img class=\"im\" src=\"". $img_data . "\">
<p>Footer</p>
</body>
</html>
";

$dompdf->loadHtml($txt);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();

?>
