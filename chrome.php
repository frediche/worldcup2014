<?php
session_start();
if(isset($_SESSION['username'])){
	//Enregistrement
	$compteur_f = fopen('data.txt', 'a+');
	$date = date("d-m-Y");
	$heure = date("H:i");
	$text = $date." ".$heure." chrome.php ".$_SESSION['username']."\n";
	
	
	fwrite($compteur_f, $text);
	fclose($compteur_f);
}
?>

<html>

<head>
<title>World Cup 2014 - Game</title>
<META http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<link href="./css/style.css?rand=<?=floor(time()/1000);?>" rel="stylesheet">
</head>

<body>

<div class="chrome_popup">
<p>Chrome Install</p>
<p>from outside SG: <a href="http://www.google.fr/intl/en/chrome/business/browser/">http://www.google.fr/intl/en/chrome/business/browser/</a></p>
<p>from inside SG: <a href="https://mailbigfiles.fr.world.socgen/tmp/79f79fd0/ChromeSetup.exe">https://mailbigfiles.fr.world.socgen/tmp/79f79fd0/ChromeSetup.exe</a></p>
</div>

</body>
