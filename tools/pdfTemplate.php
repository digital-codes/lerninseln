<!DOCTYPE html>
<html>
<head>
<style>
h1 {
    line-height: 2em;
    font-size: 1.5em;
    color: \"#f00\";
    margin-bottom: 1em;
}
h2 {
    line-height: 1.6em;
    font-size: 1.2em;
    color: rgb(0,0,255);
    margin-bottom: 1em;
}
p {
    font-size:1em;
}

.im {
    width: 400px;
    margin: 2em 2em 2em 8em;
    padding: 0;
}

.bold {
    font-weight: 800;
    color: rgb(255,0,0);
}

</style>
</head>
<body>

<h1>Lerninseln Karlsruhe</h1>
<h2>Ticket für die Veranstaltung: <span class="bold"><?php echo $event["name"];?></span></h2>
<p>Datum <?php echo $event["date"];?>, <?php echo $event["time"];?> Uhr</p>
<hr>
<img class="im" src="<?php echo $event["qrdata"];?>">
<hr>
<p class="footer">Wir freuen uns auf Dich</p>
</body>
</html>
