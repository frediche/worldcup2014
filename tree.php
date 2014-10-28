<?php
session_start();
if(!isset($_SESSION['timeout']) || ($_SESSION['timeout'] < time())){
session_unset();
session_destroy();
header( 'Location: http://worldcup2014.olympe.in/login.php' );
exit;
}

//Enregistrement
$compteur_f = fopen('data.txt', 'a+');
$date = date("d-m-Y");
$heure = date("H:i");
$text = $date." ".$heure." tree.php ".$_SESSION['username']."\n";


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

function match($id, $bdd, $style) {
	$req = $bdd->prepare('SELECT * FROM matchs WHERE id = ?');
	$req->execute(array($id));
	$donnees = $req->fetch();
	echo "<a href=\"#\" onClick=\"MyWindow=window.open('http://worldcup2014.olympe.in/match.php?id=".$donnees['id']."','Match".$donnees['id']."','width=800,height=400,scrollbars=1').focus(); return false;\">";
	echo "<div class=\"tree_match\" style=\"".$style."background-color:".($donnees['timecode'] < time() ? '#F6CEEC' : ($donnees['open'] == 1 && $donnees['timecode'] < time() + 2000000 ? '#E1F5A9' : '#E6E6E6')).";\">";
	echo "<div class=\"tree_team1\"".($donnees['issue']==1 ? " style=\"font-weight:bold\"" : "")."><img src=\"flags/".$donnees['code1'].".png\" style=\"vertical-align:middle;height:auto; width:auto; max-height:10px;\"> ".$donnees['pays1']."</div>";
	echo "<div class=\"tree_team2\"".($donnees['issue']==2 ? " style=\"font-weight:bold\"" : "")."><img src=\"flags/".$donnees['code2'].".png\" style=\"vertical-align:middle;height:auto; width:auto; max-height:10px;\"> ".$donnees['pays2']."</div>";
	echo "<div class=\"tree_score1\">".($donnees['issue'] >= 0 ? $donnees['score1'] : '-' )."</div>";
	echo "<div class=\"tree_score2\">".($donnees['issue'] >= 0 ? $donnees['score2'] : '-' )."</div>";
	echo "</div>";
	echo "</a>";
}
?>

<!doctype html>

<html>

<head>
<title>World Cup 2014 - Game</title>
<META http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<link href="./css/style.css?rand=<?=floor(time()/1000);?>" rel="stylesheet">
</head>

<body bgcolor="#FACC2E">

<!-- Poules -->
<div class="tree_poule" style="top:20px;left:20px">
<div class="tree_poule_title">Group A</div>
<?php match(1, $bdd, "top:30px;left:0px;right:0px;margin:auto;"); ?>
<?php match(2, $bdd, "top:65px;left:0px;right:0px;margin:auto;"); ?>
<?php match(16, $bdd, "top:100px;left:0px;right:0px;margin:auto;"); ?>
<?php match(19, $bdd, "top:135px;left:0px;right:0px;margin:auto;"); ?>
<?php match(35, $bdd, "top:170px;left:0px;right:0px;margin:auto;"); ?>
<?php match(36, $bdd, "top:205px;left:0px;right:0px;margin:auto;"); ?>
</div>

<div class="tree_poule" style="top:20px;left:170px">
<div class="tree_poule_title">Group B</div>
<?php match(3, $bdd, "top:30px;left:0px;right:0px;margin:auto;"); ?>
<?php match(4, $bdd, "top:65px;left:0px;right:0px;margin:auto;"); ?>
<?php match(18, $bdd, "top:100px;left:0px;right:0px;margin:auto;"); ?>
<?php match(20, $bdd, "top:135px;left:0px;right:0px;margin:auto;"); ?>
<?php match(33, $bdd, "top:170px;left:0px;right:0px;margin:auto;"); ?>
<?php match(34, $bdd, "top:205px;left:0px;right:0px;margin:auto;"); ?>
</div>

<div class="tree_poule" style="top:20px;left:320px">
<div class="tree_poule_title">Group C</div>
<?php match(5, $bdd, "top:30px;left:0px;right:0px;margin:auto;"); ?>
<?php match(7, $bdd, "top:65px;left:0px;right:0px;margin:auto;"); ?>
<?php match(21, $bdd, "top:100px;left:0px;right:0px;margin:auto;"); ?>
<?php match(23, $bdd, "top:135px;left:0px;right:0px;margin:auto;"); ?>
<?php match(39, $bdd, "top:170px;left:0px;right:0px;margin:auto;"); ?>
<?php match(40, $bdd, "top:205px;left:0px;right:0px;margin:auto;"); ?>
</div>

<div class="tree_poule" style="top:20px;left:470px">
<div class="tree_poule_title">Group D</div>
<?php match(6, $bdd, "top:30px;left:0px;right:0px;margin:auto;"); ?>
<?php match(8, $bdd, "top:65px;left:0px;right:0px;margin:auto;"); ?>
<?php match(22, $bdd, "top:100px;left:0px;right:0px;margin:auto;"); ?>
<?php match(24, $bdd, "top:135px;left:0px;right:0px;margin:auto;"); ?>
<?php match(37, $bdd, "top:170px;left:0px;right:0px;margin:auto;"); ?>
<?php match(38, $bdd, "top:205px;left:0px;right:0px;margin:auto;"); ?>
</div>

<div class="tree_poule" style="top:280px;left:20px">
<div class="tree_poule_title">Group E</div>
<?php match(9, $bdd, "top:30px;left:0px;right:0px;margin:auto;"); ?>
<?php match(10, $bdd, "top:65px;left:0px;right:0px;margin:auto;"); ?>
<?php match(25, $bdd, "top:100px;left:0px;right:0px;margin:auto;"); ?>
<?php match(26, $bdd, "top:135px;left:0px;right:0px;margin:auto;"); ?>
<?php match(43, $bdd, "top:170px;left:0px;right:0px;margin:auto;"); ?>
<?php match(44, $bdd, "top:205px;left:0px;right:0px;margin:auto;"); ?>
</div>

<div class="tree_poule" style="top:280px;left:170px">
<div class="tree_poule_title">Group F</div>
<?php match(11, $bdd, "top:30px;left:0px;right:0px;margin:auto;"); ?>
<?php match(13, $bdd, "top:65px;left:0px;right:0px;margin:auto;"); ?>
<?php match(27, $bdd, "top:100px;left:0px;right:0px;margin:auto;"); ?>
<?php match(29, $bdd, "top:135px;left:0px;right:0px;margin:auto;"); ?>
<?php match(41, $bdd, "top:170px;left:0px;right:0px;margin:auto;"); ?>
<?php match(42, $bdd, "top:205px;left:0px;right:0px;margin:auto;"); ?>
</div>

<div class="tree_poule" style="top:280px;left:320px">
<div class="tree_poule_title">Group G</div>
<?php match(12, $bdd, "top:30px;left:0px;right:0px;margin:auto;"); ?>
<?php match(14, $bdd, "top:65px;left:0px;right:0px;margin:auto;"); ?>
<?php match(28, $bdd, "top:100px;left:0px;right:0px;margin:auto;"); ?>
<?php match(31, $bdd, "top:135px;left:0px;right:0px;margin:auto;"); ?>
<?php match(45, $bdd, "top:170px;left:0px;right:0px;margin:auto;"); ?>
<?php match(46, $bdd, "top:205px;left:0px;right:0px;margin:auto;"); ?>
</div>

<div class="tree_poule" style="top:280px;left:470px">
<div class="tree_poule_title">Group H</div>
<?php match(15, $bdd, "top:30px;left:0px;right:0px;margin:auto;"); ?>
<?php match(17, $bdd, "top:65px;left:0px;right:0px;margin:auto;"); ?>
<?php match(30, $bdd, "top:100px;left:0px;right:0px;margin:auto;"); ?>
<?php match(32, $bdd, "top:135px;left:0px;right:0px;margin:auto;"); ?>
<?php match(47, $bdd, "top:170px;left:0px;right:0px;margin:auto;"); ?>
<?php match(48, $bdd, "top:205px;left:0px;right:0px;margin:auto;"); ?>
</div>

<!-- Huitièmes -->
<div class="tree_quart" style="top:540px;left:20px">
<div class="tree_poule_title">Round of 16</div>
<?php match(49, $bdd, "top:37px;left:36px;"); ?>
<?php match(50, $bdd, "top:74px;left:36px;"); ?>
<?php match(51, $bdd, "top:37px;left:172px;"); ?>
<?php match(52, $bdd, "top:74px;left:172px;"); ?>
<?php match(53, $bdd, "top:37px;left:308px;"); ?>
<?php match(54, $bdd, "top:74px;left:308px;"); ?>
<?php match(55, $bdd, "top:37px;left:444px;"); ?>
<?php match(56, $bdd, "top:74px;left:444px;"); ?>
</div>

<!-- Quarts -->
<div class="tree_quart" style="top:670px;left:20px">
<div class="tree_poule_title">Quarter-finals</div>
<?php match(58, $bdd, "top:55px;left:36px;"); ?>
<?php match(57, $bdd, "top:55px;left:172px;"); ?>
<?php match(60, $bdd, "top:55px;left:308px;"); ?>
<?php match(59, $bdd, "top:55px;left:444px;"); ?>
</div>

<!-- Demi -->
<div class="tree_quart" style="top:800px;left:20px">
<div class="tree_poule_title">Semi-finals</div>
<?php match(61, $bdd, "top:55px;left:126px;"); ?>
<?php match(62, $bdd, "top:55px;left:353px;"); ?>
</div>

<!-- 3ème place -->
<div class="tree_final" style="top:930px;left:20px">
<div class="tree_poule_title">Third place</div>
<?php match(63, $bdd, "top:55px;left:90px;"); ?>
</div>

<!-- Finale -->
<div class="tree_final" style="top:930px;left:320px">
<div class="tree_poule_title">Final</div>
<?php match(64, $bdd, "top:55px;left:90px;"); ?>
</div>

</body>

</html>