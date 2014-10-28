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
<p>Hello<br><?=$info['firstname'];?> <?=$info['lastname'];?>,</p>
<p style="font-size:30px"><?=number_format($info['solde_credit'], 1, ".", "");?>  <img src="img/ball_medium.png" style="vertical-align:middle"></p>
<p><a href="options.php" class="infolink">Settings</a><br>
<a href="signout.php" class="infolink">Logout</a></p>
</div>


<div class="menu">
<a href="index.php" class="menulink">MATCHES</a> &nbsp; &nbsp;
<a href="histo.php" class="menulink">MY BETS</a> &nbsp; &nbsp;
<a href="rank.php" class="menulink">RANKING</a> &nbsp; &nbsp;
<a href="#" onClick="MyWindow=window.open('http://worldcup2014.olympe.in/tree.php','Tree','width=640,height=400,scrollbars=1').focus(); return false;" class="menulink">TREE</a> &nbsp; &nbsp;
<a href="#" onClick="MyWindow=window.open('http://worldcup2014.olympe.in/map.html','_blank').focus(); return false;" class="menulink">MAP</a> &nbsp; &nbsp;
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
    <td class="tg-hdt5">30</td>
	<td class="tg-hdt5">15</td>
  </tr>
  <tr>
    <td class="tg-tfw5">Quarter-finals</td>
    <td class="tg-tfw5">4</td>
    <td class="tg-tfw5">30</td>
	<td class="tg-tfw5">20</td>
  </tr>
  <tr>
    <td class="tg-hdt5">Semi-finals</td>
    <td class="tg-hdt5">2</td>
    <td class="tg-hdt5">20</td>
	<td class="tg-tfw5">20</td>
  </tr>
  <tr>
    <td class="tg-tfw5">Third place</td>
    <td class="tg-tfw5">1</td>
    <td class="tg-tfw5">5</td>
	<td class="tg-tfw5">10</td>
  </tr>
  <tr>
    <td class="tg-hdt5">Final</td>
    <td class="tg-hdt5">1</td>
    <td class="tg-hdt5">20</td>
	<td class="tg-hdt5">30</td>
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

<p>The total number of balls bet on the game by the participants is then divided 60% / 40% in bucket 1 / 2 respectively. After the result is known<br>
</p><ul><li>Bucket 1 is split between those who got it right on who won (3 cases: Team1, Team2, or tie) in proportion of their stake</li>
<li>Bucket 2 is split between those who got it right on the goal difference in proportion of their stake</li>
<li>Consequently the other participants lose their stakes</li></ul><p></p>

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
</p><ul><li>The players having bet a goal difference strictly positive each receive 60% x 1.26 x their stake</li>
<li>The players having bet goal difference=3 each receive 40% x 63/10 x their stake</li></ul><p></p>
<p>Now assume the outcome is [Brazil 0 - 1 Croatia]. Then<br>
</p><ul><li>Bucket 1 is split in the same way</li>
<li>Since nobody bet on a goal difference of -1 the winners set is widened to encompass the nearest goal differences, here 0 and -2. Each of the participants having bet 0 or -2 then receive 40% x 63/(8+5) x their stake. If nobody had bet on 0 or -2 either, then the winners' set would have widened to +1 and -3, and so on, until it is not empty.</li></ul><p></p>

<p>The balance of balls displayed is rounded to the nearest tenth, but the exact balance is recorded. If you find yourself with strictly less than 1 ball, you cannot bet any longer... until the end of the Group stage.</p>

<p></p><center><h3>Direct Elimination Stage betting</h3></center><p></p>

<p>In this stage the same betting rules as in the Group stage apply except for the following modifications:<br>
</p><ul><li>The maximum bet per match is changed from 10 to 15/20/20/10/30 for the second stage rounds respectively.</li>
<li>A bet is not placed on a goal difference any longer but on an explicit score.</li></ul><p></p>

<p>For example, in a hypothetical semi final Portugal - Argentina, a player could bet<br>
</p><center>[Portugal 2 - 0 Argentina]<br>
or [Portugal 1 - 4 Argentina]<br>
or [Portugal 1 - 1 Argentina & Portugal wins on penalties]</center><p></p>

<p>The split between bucket 1 / 2 is now 75% / 25% respectively.<br>
In order to claim their share of bucket 2 a participant must have got the score exactly right, not just the goal difference. In case nobody has bet on the score, then the winners' set is widened to those got it wrong by only one goal, and if that means a tie, who got the penalties right. If the winners' set is still empty it is gradually widened until at least one winner emerges.</p>

<p>For example, if the result is [Portugal 3 - 2 Argentina] and nobody has bet on it then bucket 2 is shared between those participants who bet on the following scores in proportion of their stake<br>
</p><center>[Portugal 4 - 2 Argentina]<br>
[Portugal 3 - 1 Argentina]<br>
[Portugal 3 - 3 Argentina & Portugal wins on penalties]<br>
[Portugal 2 - 2 Argentina & Portugal wins on penalties]</center><p></p>

<p>Another example, if the result is [Portugal 1 - 1 Argentina + Portugal wins on penalties] and nobody has bet on it then bucket 2 is shared between those participants who bet on the following scores in proportion of their stake<br>
</p><center>[Portugal 2 - 1 Argentina]<br>
[Portugal 1 - 0 Argentina]<br>
[Portugal 1 - 1 Argentina + Argentina wins on penalties]</center><p></p>

<p>Here is a third example. Here the result of the match is Team1(horizontal axis) 2-1 Team2(vertical axis).
Bucket2 is shared among those who bet on the tile that is tagged <b>1</b>. If this set is empty, then Bucket2 is shared among those who bet in any tile tagged <b>2</b>, etc.</p>

<p>A picture is worth a thousand words: The winner's set growth is illustrated below.</p>

<p><center><img src="img/rules1.png"></center></p>



</div>


</body>

</html>