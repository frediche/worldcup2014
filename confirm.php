<?php
session_start();

if( !isset($_GET['username']) || !isset($_GET['key']) ){
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

$req = $bdd->prepare('SELECT * FROM users WHERE username = ?');
$req->execute(array($_GET['username']));

if(!($donnees = $req->fetch())){
	header( 'Location: http://worldcup2014.olympe.in/index.php' );
	exit;
}

if( $donnees['conf_key'] == $_GET['key']){
	$req = $bdd->prepare('UPDATE users SET valid = 1 WHERE username = ?');
	$req->execute(array($donnees['username']));
	$_SESSION['ok'] = 3;
	$_SESSION['timeout'] = time() + 1800;
	$_SESSION['username'] = $donnees['username'];
}

header( 'Location: http://worldcup2014.olympe.in/index.php' );
exit;