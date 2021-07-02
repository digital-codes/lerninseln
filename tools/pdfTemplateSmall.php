<!DOCTYPE html>
<html>
<head>
<style>
/* default dpi is 72.
A$ size is 8.3 * 11.7 inch 
= 600 * 840pt

one-third ~ 260pt. tbc
resolution can be changed by 
$dompdf->set_option( 'dpi' , '200' );

dpi maybe only affects px settings, but not pt settings
fixed resolution of a PDF, which is 72 PT per inch. 

page border can be set like this:
@page { margin: 0in; }
body { padding: .5in; border: 2in solid orange; }
*/

body {
    font-family: sans-serif;
    width:520pt;
    background-image: url(<?php echo $event["bg"];?>);
    background-repeat: no-repeat;
    /*background-attachment: fixed; */ 
    /* background-size: contain; */
    background-size: 520pt 290pt;
    /* <a href="https://www.freepik.com/photos/background">Background photo created by mrsiraphol - www.freepik.com</a> */

}
h2 {
    line-height: 20pt;
    font-size: 16pt;
    margin-bottom: 0;
    padding: 0;
    padding-left:10pt;
}
p {
    font-size:12pt;
    line-height: 14pt;
    padding-top: 2pt;
    padding-bottom: 2pt;
    padding-left:10pt;
}


.bold {
    font-weight: 700;
    font-family: sans-serif;
    color: rgb(255,0,0);
}

.date {
    margin-bottom: 4pt;
}

#back {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 290pt;
    background: rgba(240,240,240,.5);
}
#header {
    position: absolute;
    top: 0pt;
    left: 0pt;
}

#logo {
    position: absolute;
    top: 10pt;
    left: 460pt;
    width:60pt;
}

#event {
    position: absolute;
    top:50pt;
    left:0pt;
    width:280pt;
    height: 190pt;
    background-color: rgba(250,250,250,.6);
}

#qr {
    position: fixed;
    top: 50pt;
    left: 280pt;
    width: 160pt;
    border: solid 2pt;
    background-color: rgb(255,255,255);
    padding: 20pt;
}


#footer {
    position: absolute;
    top: 240pt;
    left: 0;
    width:100%;
    padding-top:0pt;
    padding-bottom:10pt;
    /*background-color: rgba(200,240,200,.8);*/
    background-color: rgba(250,250,250,.6);
    border-top: solid 2pt rgb(0,0,0);
}

#attribution {
    position: absolute;
    top: 270pt;
    left: 0;
    padding-top:0;
    padding-bottom:0;
    font-size: 8pt;
    margin: 0;
    font-style: italic;
    color: rgb(100,100,100);
}

h1 {
    line-height: 30pt;
    font-size: 24pt;
    color: rgb(0,0,255);
}


</style>
</head>
<body>

<div id="back">
<h1 id="header" >Lerninseln Karlsruhe</h1>
<img id="logo" src="<?php echo $event["logo"];?>" class="logo">

<div id="event">
    <h2>Ticket für die Veranstaltung:</h2>
    <h2><span class="bold"><?php echo $event["name"];?></span></h2>
    <p class="date">Datum <?php echo $event["date"];?>, 
        <?php echo $event["time"];?> Uhr
        </p>
        <p> 
        <?php echo $event["location1"];?>
        <br>
        <?php echo $event["location2"];?>
        </p>
        <p> 
        <?php echo $event["count"];?> Person(en)
        </p>
</div>

<div id="footer" class="bg">
<p >Wir freuen uns auf Dich</p>
</div>
<p id="attribution">
Background photo created by mrsiraphol - www.freepik.com
</p>

<img id="qr" src="<?php echo $event["qrdata"];?>">

</body>
</html>
