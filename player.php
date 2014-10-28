<?php
session_start();
if(!isset($_SESSION['timeout']) || ($_SESSION['timeout'] < time())){
session_unset();
session_destroy();
header( 'Location: http://worldcup2014.olympe.in/login.php' );
exit;
}
elseif($_SESSION['ok'] == 3){
	$_SESSION['timeout'] = time() + 1800;
}
if(!isset($_GET['username'])){
	header( 'Location: http://worldcup2014.olympe.in/index.php' );
	exit;
}

//Enregistrement
$compteur_f = fopen('data.txt', 'a+');
$date = date("d-m-Y");
$heure = date("H:i");
$text = $date." ".$heure." player.php ".$_SESSION['username']."\n";


fwrite($compteur_f, $text);
fclose($compteur_f);

try
{
	$bdd = new PDO('mysql:host=sql2.olympe.in;dbname=b1omsb6t', 'b1omsb6t', 'worldcup2014');
}
catch (Exception $e)
{
		die('Erreur : ' . $e->getMessage());
}
$req = $bdd->query('SET NAMES utf8');

$req = $bdd->prepare('SELECT * FROM users WHERE username = ? AND valid = 1');
$req->execute(array($_GET['username']));
if(!($donnees = $req->fetch())){
	header('Location: http://worldcup2014.olympe.in/index.php');
	exit;
}

$req4 = $bdd->prepare('SELECT * FROM teams WHERE id_team = ?');
$req4->execute(array($donnees['id_team']));
$donnees4 = $req4->fetch();
?>

<!doctype html>

<html>

<head>
<title><?=$donnees['firstname'];?> <?=$donnees['lastname'];?></title>
<META http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<link href="./css/style.css?rand=<?=floor(time()/1000);?>" rel="stylesheet">
</head>

<body bgcolor="#FACC2E">

<div class="player_fiche_general">

<div class="player_fiche_name">
<?=ucfirst(strtolower($donnees['firstname']));?> <?=ucfirst(strtolower($donnees['lastname']));?>
</div>

<div class="player_fiche_solde">
<?=number_format($donnees['solde_reel'], 1, ".", "");?> <img src="img/ball_medium.png" style="vertical-align:middle">
</div>

<?php if($donnees['id_team'] > 0):?>
<div class="player_fiche_team">
<p><a href="#" onClick="MyWindow=window.open('http://worldcup2014.olympe.in/team.php?id_team=<?=$donnees['id_team'];?>','Team<?=$donnees['id_team'];?>','width=600,height=600,scrollbars=1').focus(); return false;" class="player_name_link"><?=$donnees4['name'];?></a></p>
</div>
<?php endif; ?>

</div>

<?php
$req2 = $bdd->prepare('SELECT * FROM bets WHERE username = ? AND active = 1 ORDER BY id DESC');
$req2->execute(array($donnees['username']));
while($donnees2 = $req2->fetch()):
	$req3 = $bdd->prepare('SELECT * FROM matchs WHERE id = ?');
	$req3->execute(array($donnees2['id']));
	$donnees3 = $req3->fetch();
?>

<div class="player_fiche_bet" style="background-color:<?=(time() < $donnees3['timecode'] ? '#E1F5A9' : '#E6E6E6');?>;height:<?=(time() < $donnees3['timecode'] ? '100px' : '125px');?>;">

<div class="player_fiche_bet_pays1">
<img src="flags/<?=$donnees3['code1'];?>.png" style="vertical-align:middle;height:auto; width:auto; max-height:20px;"> &nbsp; <?=$donnees3['pays1'];?>
</div>

<div class="player_fiche_bet_pays2">
<?=$donnees3['pays2'];?> &nbsp; <img src="flags/<?=$donnees3['code2'];?>.png" style="vertical-align:middle;height:auto; width:auto; max-height:20px;">
</div>

<?php if($donnees3['timecode'] < time()):?>
<div class="player_fiche_bet_score1"><?=($donnees3['issue'] >= 0 ? $donnees3['score1'] : '-');?></div> &nbsp;  &nbsp; 
<div class="player_fiche_bet_score2"><?=($donnees3['issue'] >= 0 ? $donnees3['score2'] : '-');?></div>
<?php endif; ?>

<div class="player_fiche_bet_choice">
<b><?=$donnees2['size'];?></b> <img src="img/ball_small.png" style="vertical-align:middle"> 
<?php if($donnees3['timecode'] < time()):
if($donnees3['tie']==1):?>
on <?=($donnees2['choice'] == 1 ? $donnees3['pays1'] : ($donnees2['choice'] == 2 ? $donnees3['pays2'] : "Tie"));?>
<?php if($donnees2['choice'] > 0):?>
, goal spread : <?=$donnees2['goal'];?>
<?php endif; 
else: ?>
on <?=($donnees2['choice'] == 1 ? $donnees3['pays1'] : $donnees3['pays2']);?>, <?=$donnees2['score1'];?> - <?=$donnees2['score2'];?>
<?php endif;
endif; ?>
</div>

<?php if($donnees3['timecode'] < time()):?>
<div class="player_fiche_bet_gain">
Profit : <b><?=($donnees3['issue'] >= 0 ? ($donnees2['profit'] > 0 ? '+' : '').number_format($donnees2['profit'], 1, ".", "") : '-');?></b> <img src="img/ball_small.png" style="vertical-align:middle">
</div>
<?php endif; ?>

<?php if($donnees3['timecode'] < time() + 2000000 && $donnees3['open'] == 1):?>
<div class="player_fiche_bet_details">
<a href="#" onClick="MyWindow=window.open('http://worldcup2014.olympe.in/match.php?id=<?=$donnees3['id'];?>','Match<?=$donnees3['id'];?>','width=820,height=700,scrollbars=1').focus(); return false;" class="match1link">More info</a>
</div>
<?php endif; ?>

</div>

<?php endwhile; ?>

</body>

</html>
