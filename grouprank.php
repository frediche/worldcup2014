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

try
{
	$bdd = new PDO('mysql:host=sql2.olympe.in;dbname=b1omsb6t', 'b1omsb6t', 'worldcup2014');
}
catch (Exception $e)
{
		die('Erreur : ' . $e->getMessage());
}
$req = $bdd->query('SET NAMES utf8');

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
<?php $req = $bdd->query('SELECT * FROM countries WHERE group = 1 ORDER BY p');
$i = 0;
while($donnees = $req->fetch()):
$i = $i +1;?>
<div class="grouperank_team" style="top:<?=30*$i;?>;">
<div class="grouprank_team_name"><?=$donnees['name']
<?php endwhile; ?>
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