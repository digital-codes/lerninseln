<!DOCTYPE html>
<html>
<head>
<style>
body {
    font-family: sans-serif;
}
h1 {
    line-height: 2em;
    font-size: 1.5em;
    color: rgb(0,0,255);
    margin-bottom: 1em;
    padding: 0;
}
h2 {
    line-height: 1.6em;
    font-size: 1.2em;
    margin-bottom: 1em;
    padding: 1em;
}
p {
    font-size:1em;
    padding: 1em;
}

.im {
    width: 400px;
    margin: 4em 2em 2em 8em;
    padding: 0;
    display: block;
}

.bold {
    font-weight: 800;
    color: rgb(255,0,0);
}

.date {
    margin-bottom: 2em;
}

.bg {
    background-color: rgb(220,220,220);
}

</style>
</head>
<body>

<h1>Lerninseln Karlsruhe</h1>
<div class="bg">
<h2>Ticket für die Veranstaltung: <span class="bold"><?php echo $event["name"];?></span></h2>
<p class="date">Datum <?php echo $event["date"];?>, <?php echo $event["time"];?> Uhr</p>
<hr>
</div>
<img class="im" src="<?php echo $event["qrdata"];?>">
<div class="bg">
<hr>
<p class="footer">Wir freuen uns auf Dich</p>
</div>
</body>
</html>
