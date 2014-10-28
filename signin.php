<?php
session_start();

if(!isset($_POST['username']) || !isset($_POST['password'])){
	$_SESSION['ok'] = 2;
	header('Location: http://worldcup2014.olympe.in/login.php');
	exit;
}

$username = $_POST['username'];
$password = $_POST['password'];

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
$req->execute(array($username));

if(($donnees = $req->fetch()) && ($donnees['password'] === $password)){
	if($donnees['valid'] == 1){
		$_SESSION['ok'] = 3;
		$_SESSION['timeout'] = time() + 1800;
	}
	else{
		$_SESSION['ok'] = 1;
		$_SESSION['timeout'] = time() + 60;
	}
	$_SESSION['username'] = $username;
	$compteur_f = fopen('data.txt', 'a+');
	$date = date("d-m-Y");
	$heure = date("H:i");
	$text = $date." ".$heure." login ".$username."\n";
	
	
	fwrite($compteur_f, $text);
	fclose($compteur_f);
	header('Location: http://worldcup2014.olympe.in/index.php');
	exit;
}
else{
	$_SESSION['ok'] = 2;
	header('Location: http://worldcup2014.olympe.in/login.php');
	exit;
}
