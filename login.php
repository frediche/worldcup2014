<?php
session_start();

if(isset($_SESSION['timeout']) && ($_SESSION['timeout'] > time())){
header('Location: http://worldcup2014.olympe.in/index.php');
exit;
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
<form action="signin.php" method="post">
<p>Login: <input type="text" name="username" /> &nbsp; &nbsp; Password: <input type="password" name="password" /> &nbsp; &nbsp; <input type="submit" value="Login" /></p>
</form>
</div>

<div class="form">
<a href="inscrip.php" class="formlink">Signup</a>
</div>

<div class="form">
<p>Welcome on the SOL World Cup Game 2014.</p>
<p>If you already have an account, login to start playing.</p>
<p>If not, click on "Signup", to create an account in a few seconds.</p>
</div>

</body>
</html>