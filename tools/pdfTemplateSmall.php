<!DOCTYPE html>
<html>
<head>
<style>
body {
    font-family: sans-serif;
}
h2 {
    line-height: 24px;
    font-size: 20px;
    margin-bottom: 0;
    padding: 0;
    padding-left:10px;
}
p {
    font-size:16px;
    line-height: 16px;
    padding-top: 2px;
    padding-bottom: 2px;
    padding-left:10px;
}


.bold {
    font-weight: 700;
    font-family: sans-serif;
    color: rgb(255,0,0);
}

.date {
    margin-bottom: 4px;
}

.bg {
    background-color: rgb(220,220,220);
}

#back {
    width: 100%;
    height: 300px;
    background: rgb(200,240,200);
}
#header {
    position: absolute;
    top: 0px;
    left: 0px;
}

#logo {
    position: absolute;
    top: 20px;
    left: 600px;
    width:100px;
}

#event {
    position: absolute;
    top:100px;
    left:0;
}

#qr {
    position: fixed;
    top: 100px;
    left: 350px;
    width: 200px;
    border: solid 2px;
    background-color: rgb(255,255,255);
    padding: 20px;
}


#footer {
    position: absolute;
    top: 300px;
    left: 0;
    width:100%;
    padding-bottom:10px;
}

h1 {
    line-height: 50px;
    font-size: 24px;
    color: rgb(0,0,255);
}


</style>
</head>
<body>

<div id="back">
<h1 id="header" >Lerninseln Karlsruhe</h1>
<img id="logo" src="<?php echo $event["logo"];?>" class="logo">

<div id="event" class="bg">
    <h2>Ticket für die Veranstaltung:</h2>
    <h2><span class="bold"><?php echo $event["name"];?></span></h2>
    <p class="date">Datum <?php echo $event["date"];?>, 
        <?php echo $event["time"];?> Uhr
        </p>
        <p> 
        <?php echo $event["location"];?>
        </p>
        <p> 
        <?php echo $event["count"];?> Persone(n)
        </p>
    <hr>
</div>

<p id="footer" class="bg">Wir freuen uns auf Dich</p>

<img id="qr" src="<?php echo $event["qrdata"];?>">

</body>
</html>
