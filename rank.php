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
$text = $date." ".$heure." rank.php ".$_SESSION['username']."\n";


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

$req = $bdd->query('SELECT * FROM users WHERE valid = 1 ORDER BY solde_reel DESC');

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

<div class="player_change_rank"><a href="teamrank.php" class="change_rank_link">Team ranking</a></div>

<div class="form">Only player who have bet on at least one match are ranked.</div>

<div class="player" style="background-color: white;">

<div class="player_name" style="font-size:15px">Name</div>
<div class="player_credit" style="font-size:15px">Nb of balls</div>

<div class="rank_nb" style="font-size:15px;top:5px;">Rank</div>

<div class="succes_issue" style="top:2px;right:200px;">Succes %<br>issue</div>
<div class="succes_score" style="top:2px;right:130px;">Succes %<br>score</div>

</div>

<?php 
$i = 0;
$previous = 0;
$prev_nb = 0;
while($donnees = $req->fetch()): 
if($donnees['solde_reel'] != $donnees['solde_init']):
$i = $i + 1;
$current_nb = ($previous != $donnees['solde_reel'] ? $i : $prev_nb);
?>
<div class="player" <?=($donnees['username'] == 'Goldman.Sachs' || $donnees['username'] == 'random' ? "style=\"background-color:#FE9A2E;\"" : ($donnees['username'] == $info['username'] ? "style=\"background-color:#04B404;\"" : ""));?>>

<div class="player_name"><a href="#" onClick="MyWindow=window.open('http://worldcup2014.olympe.in/player.php?username=<?=$donnees['username'];?>','User<?=$donnees['username'];?>','width=500,height=400,scrollbars=1').focus(); return false;" class="player_name_link">
<?=ucfirst(strtolower($donnees['firstname']));?> <?=ucfirst(strtolower($donnees['lastname']));?>
</a></div>
<div class="player_credit"><?=number_format($donnees['solde_reel'], 1, ".", "");?> <img src="img/ball_small.png" style="vertical-align:middle"></div>

<div class="rank_nb"><?=$current_nb;?></div>

<div class="succes_issue"><?=number_format($donnees['succes_issue'], 1, ".", "");?>%</div>
<div class="succes_score">(<?=number_format($donnees['succes_score'], 1, ".", "");?>%)</div>

</div>
<?php 
$previous = $donnees['solde_reel'];
$prev_nb = $current_nb;
endif;
endwhile; ?>

</body>
</html>