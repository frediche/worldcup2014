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

//Enregistrement
$compteur_f = fopen('data.txt', 'a+');
$date = date("d-m-Y");
$heure = date("H:i");
$text = $date." ".$heure." teamrank.php ".$_SESSION['username']."\n";


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

$req = $bdd->query('SELECT * FROM teams WHERE id_team > 0');

while($donnees = $req->fetch()){
	$req2 = $bdd->prepare('SELECT * FROM users WHERE id_team = ? AND valid = 1');
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
}

$req = $bdd->query('SELECT * FROM teams WHERE nb_player > 0 AND id_team > 0 ORDER BY solde_moyen DESC');

$reqinfo = $bdd->prepare('SELECT * FROM users WHERE username = ?');
$reqinfo->execute(array($_SESSION['username']));
$info = $reqinfo->fetch();
?>

<!doctype html>

<html>

<head>
<title>World Cup 2014 - Game</title>
<META http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<link href="./css/style.css?rand=<?=floor(time()/1000);?>" rel="stylesheet">
</head>

<body>
<div class="chrome">
Website is optimized for Chrome. Install from <a href="chrome.php" style="chrome">here</a>.
</div>

<div class="banner">
<a href="index.php"><img src="img/logo_coupe.jpg"></a>
</div>

<div class="logo_sol">
<img src="img/logo_sol.png" style="height:auto; width:auto; max-width:250px; max-height:250px;">
</div>

<div class="info">
<p>Hello<br><?=ucfirst(strtolower($info['firstname']));?> <?=ucfirst(strtolower($info['lastname']));?>,</p>
<p style="font-size:30px"><?=number_format($info['solde_credit'], 1, ".", "");?>  <img src="img/ball_medium.png" style="vertical-align:middle"></p>
<p><a href="options.php" class="infolink">Settings</a><br>
<a href="signout.php" class="infolink">Logout</a></p>
</div>

<div class="menu">
<a href="index.php" class="menulink">MATCHES</a> &nbsp; &nbsp;
<a href="histo.php" class="menulink">MY BETS</a> &nbsp; &nbsp;
<font size="5"><a href="rank.php" class="menulink">RANKING</a></font> &nbsp; &nbsp;
<a href="#" onClick="MyWindow=window.open('http://worldcup2014.olympe.in/tree.php','Tree','width=640,height=700,scrollbars=1').focus(); return false;" class="menulink">TREE</a> &nbsp; &nbsp;
<a href="#" onClick="MyWindow=window.open('http://worldcup2014.olympe.in/map.php','_blank').focus(); return false;" class="menulink">MAP</a> &nbsp; &nbsp;
<a href="rules.php" class="menulink">RULES</a> &nbsp; &nbsp;
</div>

<div class="team_change_rank"><a href="rank.php" class="change_rank_link">Player ranking</a></div>

<div class="form">Only teams of more than 4 players are ranked.</div>

<?php 
$i = 0;
$previous = 0;
$prev_nb = 0;
while($donnees = $req->fetch()): 
$i = $i + 1;
$current_nb = ($previous != $donnees['solde_moyen'] ? $i : $prev_nb);
?>
<div class="team">

<div class="player_name"><a href="#" onClick="MyWindow=window.open('http://worldcup2014.olympe.in/team.php?id_team=<?=$donnees['id_team'];?>','Team<?=$donnees['id_team'];?>','width=600,height=600,scrollbars=1').focus(); return false;" class="player_name_link">
<?=$donnees['name'];?>
</a></div>
<div class="player_credit">avg. &nbsp; <?=number_format($donnees['solde_moyen'], 1, ".", "");?> <img src="img/ball_small.png" style="vertical-align:middle"></div>

<div class="rank_nb"><?=$current_nb;?></div>

</div>
<?php 
$previous = $donnees['solde_reel'];
$prev_nb = $current_nb;
endwhile; ?>

</body>
</html>