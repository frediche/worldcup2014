<?php
try
{
	$bdd = new PDO('mysql:host=sql2.olympe.in;dbname=b1omsb6t', 'b1omsb6t', 'worldcup2014');
}
catch (Exception $e)
{
		die('Erreur : ' . $e->getMessage());
}
$req = $bdd->query('SET NAMES utf8');

$req = $bdd->query('SELECT * FROM bets WHERE active = 1 ORDER BY timebet DESC');
?>

<!doctype html>

<html>

<head>
<title>World Cup 2014 - Game</title>
<META http-equiv="Cache-Control" content="no-cache">
<META HTTP-EQUIV="Refresh" CONTENT="10; URL=http://worldcup2014.olympe.in/flow.php"> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<link href="./css/style.css?rand=<?=floor(time()/1000);?>" rel="stylesheet">
</head>

<body bgcolor="#5FB404">

<div class="flow_title">
Last bets (LIVE)
</div>

<?php 
$a = 0;
while(($donnees = $req->fetch()) && ($a < 50)):
$a = $a + 1;
$req2 = $bdd->prepare('SELECT * FROM matchs WHERE id = ?');
$req2->execute(array($donnees['id']));
$donnees2 = $req2->fetch();
$req3 = $bdd->prepare('SELECT * FROM users WHERE username = ?');
$req3->execute(array($donnees['username']));
$donnees3 = $req3->fetch();
?>
<div class="flow_bet" <?=($donnees['timebet']>time()-12 ? "style=\"background-color:#F78181\"" : "");?>>

<div class="flow_bet_flag1">
<img src="flags/<?=$donnees2['code1'];?>.png" style="vertical-align:middle;height:auto; width:auto; max-height:15px;">
</div>

<div class="flow_bet_flag2">
<img src="flags/<?=$donnees2['code2'];?>.png" style="vertical-align:middle;height:auto; width:auto; max-height:15px;">
</div>

<div class="flow_bet_teams">
<?=strtoupper($donnees2['code1'])." - ".strtoupper($donnees2['code2']);?>
</div>

<div class="flow_bet_username" style="font-size:20px">
<?=ucfirst(strtolower($donnees3['firstname']));?> <?=ucfirst(strtolower($donnees3['lastname']));?>
</div>

<div class="flow_bet_size">
<?=$donnees['size'];?> <img src="img/ball_small.png" style="vertical-align:middle">
</div>

</div>
<?php endwhile; ?>

</body>