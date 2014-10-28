<?php
session_start();
if(!isset($_SESSION['timeout']) || ($_SESSION['timeout'] < time())){
session_unset();
session_destroy();
header( 'Location: http://worldcup2014.olympe.in/login.php' );
exit;
}

if(!isset($_POST['id']) || !isset($_POST['size']) || !isset($_POST['update'])){
	header( 'Location: http://worldcup2014.olympe.in/index.php' );
	exit;
}

$id = $_POST['id'];
try
{
	$bdd = new PDO('mysql:host=sql2.olympe.in;dbname=b1omsb6t', 'b1omsb6t', 'worldcup2014');
}
catch (Exception $e)
{
		die('Erreur : ' . $e->getMessage());
}
$req = $bdd->query('SET NAMES utf8');

$reqinfo = $bdd->prepare('SELECT * FROM users WHERE username = ?');
$reqinfo->execute(array($_SESSION['username']));
$info = $reqinfo->fetch();

$req = $bdd->prepare('SELECT * FROM matchs WHERE id = ?');
$req->execute(array($id));
if(!($donnees = $req->fetch())){
	header('Location: http://worldcup2014.olympe.in/index.php');
	exit;
}

if($donnees['tie'] == 1){
	if(!isset($_POST['choice'])){
		header( 'Location: http://worldcup2014.olympe.in/index.php' );
		exit;
	}
	if(($_POST['choice'] == 1 && (!isset($_POST['goal1']) || $_POST['goal1'] > 10)) || ($_POST['choice'] == 2 && (!isset($_POST['goal2']) || $_POST['goal2'] > 10))){
		header( 'Location: http://worldcup2014.olympe.in/index.php' );
		exit;
	}
}
else{
	if(!isset($_POST['score1']) || !isset($_POST['score2'])){
		header( 'Location: http://worldcup2014.olympe.in/index.php' );
		exit;
	}
	if($_POST['score1'] < 0 || $_POST['score2'] < 0 || $_POST['score1'] > 10 || $_POST['score2'] > 10 || fmod($_POST['score1'], 1.0) > 0 || fmod($_POST['score2'], 1.0) > 0 || ($_POST['score1'] == $_POST['score2'] && !isset($_POST['penalty']))){
		header( 'Location: http://worldcup2014.olympe.in/index.php' );
		exit;
	}
}	

if($donnees['timecode'] < time() || $donnees['timecode'] > time() + 2000000 || $donnees['open'] == 0){
	header('Location: http://worldcup2014.olympe.in/index.php');
	exit;
}
$req2 = $bdd->prepare('SELECT * FROM bets WHERE username = ? AND id = ? AND active = 1');
$req2->execute(array($_SESSION['username'], $donnees['id']));
if($donnees2 = $req2->fetch()){
	$update = 1;
}
else{
	$update = 0;
}

$credit = $info['solde_credit'] + ($update == 1 ? $donnees2['size'] : 0);
if( ($credit < $_POST['size']) || ($_POST['size'] > $donnees['max_size']) || ($_POST['size'] < 1) || (fmod($_POST['size'], 1.0) > 0)){
	header( 'Location: http://worldcup2014.olympe.in/bet.php?id='.$donnees['id'].'&from=.'.$_POST['from'] );
	exit;	
}

if($update == 1){
	$req3 = $bdd->prepare('UPDATE bets SET active = 0 WHERE username = ? AND id = ? AND active = 1');
	$req3->execute(array($_SESSION['username'], $donnees['id']));
}

$req4 = $bdd->query('SELECT id_bet FROM bets ORDER BY id_bet DESC');
if($donnees4 = $req4->fetch()){
	$next_id = $donnees4['id_bet'] + 1;
}
else{
	$next_id = 1;
}

if($donnees['tie'] == 1){
	$req3 = $bdd->prepare('INSERT INTO bets(id_bet, username, timebet, size, choice, goal, issue, gain, id, active) VALUES(:id_bet, :username, :timebet, :size, :choice, :goal, :issue, :gain, :id, :active)');
	$req3->execute(array(
		'id_bet' => $next_id,
		'username' => $_SESSION['username'],
		'timebet' => time(),
		'size' => $_POST['size'],
		'choice' => $_POST['choice'],
		'goal' => ($_POST['choice'] == 1 ? $_POST['goal1'] : ($_POST['choice'] == 2 ? $_POST['goal2'] : 0)),
		'issue' => -1,
		'gain' => 0,
		'id' => $donnees['id'],
		'active' => 1
	));
}
else{
	$req3 = $bdd->prepare('INSERT INTO bets(id_bet, username, timebet, size, choice, score1, score2, issue, gain, id, active) VALUES(:id_bet, :username, :timebet, :size, :choice, :score1, :score2, :issue, :gain, :id, :active)');
	$req3->execute(array(
		'id_bet' => $next_id,
		'username' => $_SESSION['username'],
		'timebet' => time(),
		'size' => $_POST['size'],
		'choice' => ($_POST['score1'] > $_POST['score2'] ? 1 : ($_POST['score1'] < $_POST['score2'] ? 2 : $_POST['penalty'])),
		'score1' => $_POST['score1'],
		'score2' => $_POST['score2'],
		'issue' => -1,
		'gain' => 0,
		'id' => $donnees['id'],
		'active' => 1
	));
}

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

if($_POST['from'] == 'histo'){
	header('Location: http://worldcup2014.olympe.in/histo.php');
	exit;
}
else{
	header('Location: http://worldcup2014.olympe.in/index.php');
	exit;
}