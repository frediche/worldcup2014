<?php
session_start();

if(!isset($_POST['username']) || !isset($_POST['password'])){
	$_SESSION['ok'] = 2;
	header('Location: http://worldcup2014.olympe.in/inscrip.php');
	exit;
}

$username = $_POST['username'];
$password = $_POST['password'];
$lastname = $_POST['lastname'];
$firstname = $_POST['firstname'];
$team = $_POST['team'];
if( $_POST['news'] == 'yes' ){
	$news = 1;
}
else{
	$news = 0;
}

//Valeurs par défaut
$solde_init = 300;
$valid = 0;
$ok = 2;

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

if($donnees = $req->fetch()){
	$_SESSION['ok'] = 2;
	header('Location: http://worldcup2014.olympe.in/inscrip.php');
	exit;
}
else{
	var_dump("1");
	$key = md5($username.rand(1,1000));
	$req = $bdd->prepare('INSERT INTO users(username, password, lastname, firstname, conf_key, valid, solde_init, solde_reel, solde_credit, news, id_team) VALUES(:username, :password, :lastname, :firstname, :conf_key, :valid, :solde_init, :solde_reel, :solde_credit, :news, :id_team)');
	$req->execute(array(
		'username' => $username,
		'password' => $password,
		'lastname' => $lastname,
		'firstname' => $firstname,
		'conf_key' => $key,
		'valid' => $valid,
		'solde_init' => $solde_init,
		'solde_reel' => $solde_init,
		'solde_credit' => $solde_init,
		'news' => $news,
		'id_team' => $team
	));
	
	//Email de confirmation
		$to = $username.'@sgcib.com';
		$subject = '[SOL World Cup Game] Welcome';
		$template = file_get_contents('email_template.html');
		$template = ereg_replace('{USERNAME}', $username, $template);
		$template = ereg_replace('{KEY}', $key, $template);
		$body = $template;
		$header = 'MIME-Version: 1.0'."\r\n";
		$header .= 'Content-Type: text/html; charset=ISO-8859-1'."\r\n";
		$header .= 'From: World Cup 2014 <noreply@worldcup2014.olympe.in>'."\r\n";
		
		mail($to, $subject, $body, $header);
		
	$_SESSION['ok'] = $ok;
	$_SESSION['timeout'] = time() + (ok == 3 ? 1800 : 60);
	$_SESSION['username'] = $username;
	header('Location: http://worldcup2014.olympe.in/index.php');
	exit;
}
