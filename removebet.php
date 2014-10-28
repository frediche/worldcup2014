<?php
session_start();
if(!isset($_SESSION['timeout']) || ($_SESSION['timeout'] < time())){
session_unset();
session_destroy();
header( 'Location: http://worldcup2014.olympe.in/login.php' );
exit;
}

if(!isset($_GET['id'])){
	header( 'Location: http://worldcup2014.olympe.in/index.php' );
	exit;
}

$id = $_GET['id'];
try
{
	$bdd = new PDO('mysql:host=sql2.olympe.in;dbname=b1omsb6t', 'b1omsb6t', 'worldcup2014');
}
catch (Exception $e)
{
		die('Erreur : ' . $e->getMessage());
}
$req = $bdd->query('SET NAMES utf8');

$req = $bdd->prepare('SELECT * FROM matchs WHERE id = ?');
$req->execute(array($id));
if(!($donnees = $req->fetch())){
	header('Location: http://worldcup2014.olympe.in/index.php');
	exit;
}
if($donnees['timecode'] < time() || $donnees['timecode'] > time() + 2000000 || $donnees['open'] == 0){
	header('Location: http://worldcup2014.olympe.in/index.php');
	exit;
}

$req2 = $bdd->prepare('UPDATE bets SET active = 0 WHERE username = ? AND id = ?');
$req2->execute(array($_SESSION['username'], $donnees['id']));

//Mise à jour du match
$req4 = $bdd->prepare('SELECT * FROM bets WHERE id = ? AND active = 1');
$req4->execute(array($donnees['id']));
$bet1 = 0;
$bet2 = 0;
$betnul = 0;

while($donnees4 = $req4->fetch()){
	if($donnees4['choice'] == 0){
		$betnul = $betnul + $donnees4['size'];
	}
	elseif($donnees4['choice'] == 1){
		$bet1 = $bet1 + $donnees4['size'];
	}
	elseif($donnees4['choice'] == 2){
		$bet2 = $bet2 + $donnees4['size'];
	}
}

$req5 = $bdd->prepare('UPDATE matchs SET bet1 = ?, bet2 = ?, betnul = ? WHERE id = ?');
$req5->execute(array($bet1, $bet2, $betnul, $donnees['id']));

//Mise à jour de l'user
$req4 = $bdd->prepare('SELECT * FROM bets WHERE username = ? AND active = 1');
$req4->execute(array($_SESSION['username']));
$solde_reel = 0;
$solde_credit = 0;
while($donnees4 = $req4->fetch()){
	if($donnees4['issue'] >= 0){
		$solde_reel = $solde_reel - $donnees4['size'] + $donnees4['gain'];
	}
	$solde_credit = $solde_credit - $donnees4['size'] + $donnees4['gain'];
}
$req5 = $bdd->prepare('SELECT * FROM users WHERE username = ?');
$req5->execute(array($_SESSION['username']));
$donnees5 = $req5->fetch();
$solde_reel = $solde_reel + $donnees5['solde_init'];
$solde_credit = $solde_credit + $donnees5['solde_init'];

$req5 = $bdd->prepare('UPDATE users SET solde_reel = ?, solde_credit = ? WHERE username = ?');
$req5->execute(array($solde_reel, $solde_credit, $_SESSION['username']));

if($_GET['from'] == 'histo'){
	header('Location: http://worldcup2014.olympe.in/histo.php');
	exit;
}
else{
	header('Location: http://worldcup2014.olympe.in/index.php');
	exit;
}