<?php
session_start();

if(isset($_SESSION['timeout']) && ($_SESSION['timeout'] > time())){
header('Location: http://worldcup2014.olympe.in/index.php');
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

$req = $bdd->query('SELECT * FROM teams ORDER BY name');
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

<div class="form">
<?php if(isset($_SESSION['ok']) && ($_SESSION['ok'] == 2)) : ?>
<p>Error</p>
<?php endif; ?>

<form action="signup.php" method="post">
<p>Firstname<br>
<input type="text" name="firstname" /></p>
<p>Lastname<br>
<input type="text" name="lastname" /></p>
<p>Username (firstname.lastname)<br>
<input type="text" name="username" /></p>
<p>Password<br>
<input type="password" name="password" /></p>
<p>Team &nbsp; 
<SELECT name="team">
<?php while($donnees = $req->fetch()):?>
		<OPTION VALUE="<?=$donnees['id_team'];?>" <?=($donnees['id_team'] == 0 ? 'selected="selected"' : '' );?>><?=$donnees['name'];?></OPTION>
<?php endwhile; ?>
</SELECT></p>
<p><input type="checkbox" name="news" value="yes" checked /> I want to receive the World Cup Newsletter (max one mail per day)</p>
<p><input type="submit" value="Submit" /></p>
</form>
</div>

<div class="form">
<a href="login.php" class="formlink">Already have an account ?</a>
</div>

</body>
</html>