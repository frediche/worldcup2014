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
if(!isset($_GET['id_team'])){
	header( 'Location: http://worldcup2014.olympe.in/index.php' );
	exit;
}

//Enregistrement
$compteur_f = fopen('data.txt', 'a+');
$date = date("d-m-Y");
$heure = date("H:i");
$text = $date." ".$heure." team.php ".$_SESSION['username']."\n";


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

$req = $bdd->prepare('SELECT * FROM teams WHERE id_team = ?');
$req->execute(array($_GET['id_team']));
if(!($donnees = $req->fetch())){
	header('Location: http://worldcup2014.olympe.in/index.php');
	exit;
}

//Recalcul des données de la team
$req2 = $bdd->prepare('SELECT * FROM users WHERE id_team = ?');
$req2->execute(array($donnees['id_team']));
$sum_solde = 0;
$nb_player = 0;
while($donnees2 = $req2->fetch()){
	$sum_solde = $sum_solde + $donnees2['solde_reel'];
	$nb_player = $nb_player + 1;
}
$solde_moyen = ( $nb_player > 0 ? $sum_solde/$nb_player : 0 );
$req3 = $bdd->prepare('UPDATE teams SET nb_player = ?, solde_moyen = ? WHERE id_team = ?');
$req3->execute(array($nb_player, $solde_moyen, $donnees['id_team']));

$req2 = $bdd->prepare('SELECT * FROM users WHERE id_team = ? AND valid = 1 ORDER BY solde_reel DESC');
$req2->execute(array($donnees['id_team']));
?>

<!doctype html>

<html>

<head>
<title><?=$donnees['name'];?></title>
<META http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<link href="./css/style.css?rand=<?=floor(time()/1000);?>" rel="stylesheet">
</head>

<body bgcolor="#D0A9F5">

<div class="player_fiche_general" style="width:500px">

<div class="player_fiche_name">
<?=$donnees['name'];?>
</div>

<div class="player_fiche_solde">
avg. <?=number_format($solde_moyen, 1, ".", "");?> <img src="img/ball_medium.png" style="vertical-align:middle">
</div>

</div>

<?php
while($donnees2 = $req2->fetch()):
?>

<div class="player" style="width:500px;">

<div class="player_name"><a href="#" onClick="MyWindow=window.open('http://worldcup2014.olympe.in/player.php?username=<?=$donnees2['username'];?>','User<?=$donnees2['username'];?>','width=500,height=400,scrollbars=1').focus(); return false;" class="player_name_link">
<?=ucfirst(strtolower($donnees2['firstname']));?> <?=ucfirst(strtolower($donnees2['lastname']));?>
</a></div>
<div class="player_credit"><?=number_format($donnees2['solde_reel'], 1, ".", "");?> <img src="img/ball_small.png" style="vertical-align:middle"></div>

</div>

<?php endwhile; ?>

</body>

</html>
