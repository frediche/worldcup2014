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
$text = $date." ".$heure." rules.php ".$_SESSION['username']."\n";


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
<a href="rank.php" class="menulink">RANKING</a> &nbsp; &nbsp;
<a href="#" onClick="MyWindow=window.open('http://worldcup2014.olympe.in/tree.php','Tree','width=640,height=700,scrollbars=1').focus(); return false;" class="menulink">TREE</a> &nbsp; &nbsp;
<a href="#" onClick="MyWindow=window.open('http://worldcup2014.olympe.in/map.php','_blank').focus(); return false;" class="menulink">MAP</a> &nbsp; &nbsp;
<font size="5"><a href="rules.php" class="menulink">RULES</a></font> &nbsp; &nbsp;
</div>

<div class="rules">

<p></p><center><h3>Objective</h3></center><p></p>

<p>At the beginning of each stage of the tournament, a participant will be given a fixed number of balls to bet with.
The objective of the game is to end the competition with the greatest number of balls.
The first few (tbd) people win a prize.</p>
<!-- on ajoute les balls -->

<p></p><center><h3>Number of balls</h3></center><p></p>

<p>The number of balls given to a participant at the beginning of each stage are the following. A participant can bet them during the same stage they are distributed or save them for later.</p>

<p>For each match, the number of balls each participant can bet must be an integer and is limited (see the limits in the table bellow).</p>

<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;border:1px solid #ffffff;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:3px 12px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:3px 12px;overflow:hidden;word-break:normal;}
.tg .tg-hdt5{background-color:#9aff99;text-align:center}
.tg .tg-tfw5{background-color:#ffffc7;text-align:center}
.tg .tg-slpz{font-weight:bold;background-color:#009901;color:#f8ff00;text-align:center}
</style>
<center><table class="tg">
  <tbody><tr>
    <th class="tg-slpz">Stage</th>
    <th class="tg-slpz">Nb of matches</th>
    <th class="tg-slpz">Nb of balls given</th>
	<th class="tg-slpz">Max bet size</th>
  </tr>
  <tr>
    <td class="tg-tfw5">Groups</td>
    <td class="tg-tfw5">48</td>
    <td class="tg-tfw5">100</td>
	<td class="tg-tfw5">10</td>
  </tr>
  <tr>
    <td class="tg-hdt5">Round of 16</td>
    <td class="tg-hdt5">8</td>
    <td class="tg-hdt5">100</td>
	<td class="tg-hdt5">50</td>
  </tr>
  <tr>
    <td class="tg-tfw5">Quarter-finals and after</td>
    <td class="tg-tfw5">8</td>
    <td class="tg-tfw5">100</td>
	<td class="tg-tfw5">Unlimited</td>
  </tr>
</tbody></table></center>

<p></p><center><h3>Group Stage betting</h3></center><p></p>

<p>In the group stage, a participant can bet any integer number of balls, subject to a maximum of 10, on a goal difference.</p>
For example, for Brazil-Croatia (the opening match), a participant could bet:<br>
<center>+2 for Brazil,<br>
or +1 for Croatia,<br>
or a tie</center><p></p>

<p>The book will open 7 days before the match date. Once open, a participant can place their bet anytime and may update it as many times as their wish until the book closes just before the kick-off.
However we encourage you to place your bet ahead of the deadline to avoid last minute server overloads. After the kick-off the bets are irreversible.</p>

<p>The total number of balls bet on the game by the participants is then split between those who got it right on who won (3 cases: Team1, Team2, or tie) in proportion of their stake, and such that players who got it right on the goal difference get twice as much as the others (for the same stake). Consequently the other participants lose their stakes.</p>

<p>For example, imagine that for [Brazil-Croatia], the bet distribution is the following</p><p>

<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;border:1px solid #ffffff;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:3px 5px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:3px 5px;overflow:hidden;word-break:normal;}
.tg .tg-tfw5{background-color:#ffffc7;text-align:center}
.tg .tg-6sdi{font-weight:bold;background-color:#009901;color:#f8ff00;text-align:center}
.tg .tg-uqai{font-weight:bold;background-color:#ffffc7;text-align:center}
</style>
</p><center><table class="tg">
  <tbody><tr>
    <th class="tg-6sdi">Goal spread in favor of Brazil</th>
    <th class="tg-6sdi">+4</th>
    <th class="tg-6sdi">+3</th>
    <th class="tg-6sdi">+2</th>
    <th class="tg-6sdi">+1</th>
    <th class="tg-6sdi">0</th>
    <th class="tg-6sdi">-1</th>
    <th class="tg-6sdi">-2</th>
  </tr>
  <tr>
    <td class="tg-uqai">Nb of balls bet (total=63)</td>
    <td class="tg-tfw5">3</td>
    <td class="tg-tfw5">10</td>
    <td class="tg-tfw5">17</td>
    <td class="tg-tfw5">20</td>
    <td class="tg-tfw5">5</td>
    <td class="tg-tfw5">0</td>
    <td class="tg-tfw5">8</td>
  </tr>
</tbody></table></center>

<p>Then the odds displayed for [Brazil, Tie, Croatia] are respectively [63/50,  63/5, 63/8] = [1.26, 12.6, 7.87]</p>
<p>Assume the outcome is [Brazil 3 - 0 Croatia]. Then<br>
</p><ul><li>The players having bet the win of Brazil, but not the right goal difference, each receive<br><center>63 / (50 + 10) x their stake</center></li>
<li>The players having bet goal difference=3 each receive<br><center><b>2 x</b> 63 / (50 + 10) x their stake</center></li></ul><p></p>
<p>Now assume the outcome is [Brazil 0 - 1 Croatia]. Then<br></p>
<ul><li>The players having bet the win of Croatia, but not the right goal difference, each receive<br><center>63 / 8 x their stake</center></li>
<li>and no one has bet the right goal difference</li></ul><p></p>

<p>The balance of balls displayed is rounded to the nearest tenth, but the exact balance is recorded. If you find yourself with strictly less than 1 ball, you cannot bet any longer... until the end of the Group stage.</p>

<p></p><center><h3>Direct Elimination Stage betting</h3></center><p></p>

<p>In this stage the same betting rules as in the Group stage apply except for the following modifications:<br>
</p><ul><li>The maximum bet per match is changed (see above).</li>
<li>A bet is not placed on a goal difference any longer but on an explicit score.</li></ul><p></p>

<p>For example, in a hypothetical semi final Portugal - Argentina, a player could bet<br>
</p><center>[Portugal 2 - 0 Argentina]<br>
or [Portugal 1 - 4 Argentina]<br>
or [Portugal 1 - 1 Argentina & Portugal wins on penalties]</center><p></p>

<p>And this time : the total number of balls bet on the game by the participants is split between those who got it right on who won (2 cases: Team1 or Team2) in proportion of their stake, and such that players who got it right on the <b>score</b> get twice as much as the others (for the same stake). Consequently the other participants lose their stakes.</p>

<p>The followings bets are considered as two different score :<br>
<ul><li>[Portugal 1 - 1 Argentina & Portugal wins on penalties]</li>
<li>[Portugal 1 - 1 Argentina & Argentina wins on penalties]</li></ul></p>

</div>


</body>

</html>