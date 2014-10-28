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
$text = $date." ".$heure." index.php ".$_SESSION['username']."\n";


fwrite($compteur_f, $text);
fclose($compteur_f);
?>

<!doctype html>

<html>

<head>
<title>World Cup 2014 - Game</title>
<META http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<link href="./css/style.css?rand=<?=floor(time()/1000);?>" rel="stylesheet">

<link href="//vjs.zencdn.net/4.6/video-js.css" rel="stylesheet">
<script src="//vjs.zencdn.net/4.6/video.js"></script>
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

<?php if($_SESSION['ok'] != 3) :?>
<div class="menu">
Thank you for creating your account.<br>In order to activate it, please follow the link which has been sent to your email username@sgcib.com.
</div>

<?php else :
try
{
    $bdd = new PDO('mysql:host=sql2.olympe.in;dbname=b1omsb6t', 'b1omsb6t', 'worldcup2014');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
$req = $bdd->query('SET NAMES utf8');

$req = $bdd->prepare('SELECT * FROM matchs WHERE timecode > ? ORDER BY timecode');
$req->execute(array(time()));

$reqinfo = $bdd->prepare('SELECT * FROM users WHERE username = ?');
$reqinfo->execute(array($_SESSION['username']));
$info = $reqinfo->fetch();?>

<div class="info">
<p>Hello<br><?=ucfirst(strtolower($info['firstname']));?> <?=ucfirst(strtolower($info['lastname']));?>,</p>
<p style="font-size:30px"><?=number_format($info['solde_credit'], 1, ".", "");?>  <img src="img/ball_medium.png" style="vertical-align:middle"></p>
<p><a href="options.php" class="infolink">Settings</a><br>
<a href="signout.php" class="infolink">Logout</a></p>
</div>
<div class="menu">
<font size="5"><a href="index.php" class="menulink">MATCHES</a></font> &nbsp; &nbsp;
<a href="histo.php" class="menulink">MY BETS</a> &nbsp; &nbsp;
<a href="rank.php" class="menulink">RANKING</a> &nbsp; &nbsp;
<a href="#" onClick="MyWindow=window.open('http://worldcup2014.olympe.in/tree.php','Tree','width=640,height=700,scrollbars=1').focus(); return false;" class="menulink">TREE</a> &nbsp; &nbsp;
<a href="#" onClick="MyWindow=window.open('http://worldcup2014.olympe.in/map.php','_blank').focus(); return false;" class="menulink">MAP</a> &nbsp; &nbsp;
<a href="rules.php" class="menulink">RULES</a> &nbsp; &nbsp;
</div>

<!--Fin du jeu-->

<div class="endgame">
<p><b>Bravo <font color="black">GE</font><font color="red">RMA</font><font color="#DBA901">NY</font> !!!</b><p>
<p>Thanks you everybody for participating. We hope you enjoyed the game as much as we did.<br>
<b><i>The SOL Paris Pricing Team</i></b></p>

<p>If you enjoyed the game, feel free to contribute any number of bitcoins to this address: <br>
<b>1PmAaeTHymuymUrJCbWJD3ptCq8XFCrhyG</b> (also identified with the QR code below). <br>
For the participants in Paris, you can also just drop by (Basalte 3ème étage).<br>
<img src="img/bitcoin.png" style="height:50px; width:50pcx;"></p>

<p><b>Below this short making-of video, you will find the top players.</b></p>
</div>

<div class="video">
		<video id="example_video_1"
			class="video-js vjs-default-skin"
			controls preload="auto"
			width="900" height="600"
			poster="http://oscar6echo.github.io/WorldCupGame/clip/1-G0011624.JPG"
			data-setup='{"example_option":true}'>
			<source src="http://oscar6echo.github.io/WorldCupGame/clip/WorldCupGame_Clip_manual2.mp4" type='video/mp4' />
			<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
		</video>
</div>
<div class="endgame">
Not working on Internet Explorer. Install Chrome from <a href="chrome.php">here</a>
</div>

<div class="endgame" style="font-size:20px;font-weight:bold;margin-top:30px;">
Top players
</div>

<?php 
$winners = array('jacques.clot', 'cedric.de-masson-dautume', 'francois.caralp', 'patrick.martins', 'armel.lagadic');
$rank = array(1, 2, 3, 4, 5);
for($i = 0; $i < 5; $i++):
$req5 = $bdd->prepare('SELECT * FROM users WHERE username = ?');
$req5->execute(array($winners[$i]));
$donnees = $req5->fetch();
?>
<div class="player">

<div class="player_name"><a href="#" onClick="MyWindow=window.open('http://worldcup2014.olympe.in/player.php?username=<?=$donnees['username'];?>','User<?=$donnees['username'];?>','width=500,height=400,scrollbars=1').focus(); return false;" class="player_name_link">
<?=ucfirst(strtolower($donnees['firstname']));?> <?=ucfirst(strtolower($donnees['lastname']));?>
</a></div>
<div class="player_credit"><?=number_format($donnees['solde_reel'], 1, ".", "");?> <img src="img/ball_small.png" style="vertical-align:middle"></div>

<div class="rank_nb"><?=$rank[$i];?></div>

<div class="succes_issue"><?=number_format($donnees['succes_issue'], 1, ".", "");?>%</div>
<div class="succes_score">(<?=number_format($donnees['succes_score'], 1, ".", "");?>%)</div>

</div>
<?php endfor; ?>

<div class="endgame" style="font-size:20px;font-weight:bold;margin-top:30px;">
Top ladies
</div>

<?php 
$winners = array('sihame.bouchouari', 'laurence.arbey', 'melanie.lozon', 'audrey.stauffert');
$rank = array(12, 13, 14, 15);
for($i = 0; $i < 4; $i++):
$req5 = $bdd->prepare('SELECT * FROM users WHERE username = ?');
$req5->execute(array($winners[$i]));
$donnees = $req5->fetch();
?>
<div class="player" style="background-color:#FA58D0;">

<div class="player_name"><a href="#" onClick="MyWindow=window.open('http://worldcup2014.olympe.in/player.php?username=<?=$donnees['username'];?>','User<?=$donnees['username'];?>','width=500,height=400,scrollbars=1').focus(); return false;" class="player_name_link">
<?=ucfirst(strtolower($donnees['firstname']));?> <?=ucfirst(strtolower($donnees['lastname']));?>
</a></div>
<div class="player_credit"><?=number_format($donnees['solde_reel'], 1, ".", "");?> <img src="img/ball_small.png" style="vertical-align:middle"></div>

<div class="rank_nb"><?=$rank[$i];?></div>

<div class="succes_issue"><?=number_format($donnees['succes_issue'], 1, ".", "");?>%</div>
<div class="succes_score">(<?=number_format($donnees['succes_score'], 1, ".", "");?>%)</div>

</div>
<?php endfor; ?>

<?php while($donnees = $req->fetch()):
	$req2 = $bdd->prepare('SELECT * FROM bets WHERE username = ? AND id = ? AND active = 1');
	$req2->execute(array($_SESSION['username'], $donnees['id']));
	if($donnees['timecode'] < time() + 2000000 && $donnees['open'] == 1) :
	//Somme des ballons
	$req3 = $bdd->prepare('SELECT * FROM bets WHERE id = ? AND active = 1');
	$req3->execute(array($donnees['id']));
	$total_ball = 0;
	$nb_bets = 0;
	while($donnees3 = $req3->fetch()){
		$total_ball = $total_ball + $donnees3['size'];
		$nb_bets = $nb_bets + 1;
	}
?>

<div class="match1" <?=($donnees['tie'] == 0 ? "style=\"height:270px\"" : "" );?>>

<div class="match_diffusion">
<?php if($donnees['tv'] == 1):?>
<img src="img/TF1.jpg" style="vertical-align:middle;height:15px;width:auto;"> &nbsp; 
<?php endif; ?>
<img src="img/beinbis.png" style="vertical-align:middle;height:15px;width:auto;">
</div>

<div class="match_header">
<p><?=$donnees['titre'];?></p>
<p><?=$donnees['daytext'];?></p>
<p><?=$donnees['timetext'];?></p>
</div>

<div class="match_cotes">
<p>Current odds</p>
<?php $sumbets = $donnees['bet1'] + $donnees['bet2'] + $donnees['betnul'];?>
<p style="font-size:25px">
<font color=<?=$donnees['color1'];?>><?=($donnees['bet1'] > 0 ? number_format($sumbets/$donnees['bet1'], 2, ".", "") : "-");?></font> &nbsp; 
<?php if($donnees['tie'] == 1): ?>
<font color=#848484><?=($donnees['betnul'] > 0 ? number_format($sumbets/$donnees['betnul'], 2, ".", "") : "-");?></font> &nbsp; 
<?php endif; ?>
<font color=<?=$donnees['color2'];?>><?=($donnees['bet2'] > 0 ? number_format($sumbets/$donnees['bet2'], 2, ".", "") : "-");?></font></p>
</div>

<div class="match_more">
<p><a href="#" onClick="MyWindow=window.open('http://worldcup2014.olympe.in/match.php?id=<?=$donnees['id'];?>','Match<?=$donnees['id'];?>','width=820,height=700,scrollbars=1').focus(); return false;" class="match1link">More info</a><p>
</div>

<div class="match_team1"><img src="flags/<?=$donnees['code1'];?>.png" style="vertical-align:middle;"> &nbsp; <?=$donnees['pays1'];?></div>
<div class="match_team2"><?=$donnees['pays2'];?> &nbsp; <img src="flags/<?=$donnees['code2'];?>.png" style="vertical-align:middle;"></div>

<?php if($donnees['tie'] == 1): ?>
<div id="viz_bets_<?=$donnees['id'];?>" style="width: 200px; height: 100px; margin:auto; position:absolute; top:100px; left: 0px; right: 0px"></div>
<?php else: ?>
<div id="viz_bets_<?=$donnees['id'];?>" style="width: 400px; height: 130px; margin:auto; position:absolute; top:120px; left: 0px; right: 0px"></div>
<div class="viz_legend" style="top:245px;right:405px">0</div>
<div class="viz_legend" style="top:232px;right:392px">1</div>
<div class="viz_legend" style="top:219px;right:379px">2</div>
<div class="viz_legend" style="top:206px;right:366px">3</div>
<div class="viz_legend" style="top:193px;right:353px">4</div>
<div class="viz_legend" style="top:245px;left:405px">0</div>
<div class="viz_legend" style="top:232px;left:392px">1</div>
<div class="viz_legend" style="top:219px;left:379px">2</div>
<div class="viz_legend" style="top:206px;left:366px">3</div>
<div class="viz_legend" style="top:193px;left:353px">4</div>
<div class="viz_legend" style="top:232px;right:470px"><?=$donnees['pays1'];?></div>
<div class="viz_legend" style="top:232px;left:470px"><?=$donnees['pays2'];?></div>
<?php endif; ?>

<div class="match_stats">
<p><?=$nb_bets;?> bets / <?=$total_ball;?> <img src="img/ball_small.png" style="vertical-align:middle;height:20px;width:auto;"></p>
</div>

<div class="match_options">
<?php if($donnees2 = $req2->fetch()):
if($donnees['tie'] == 1):?>
<p><?=($donnees2['choice'] == 1 ? $donnees['pays1'] : ($donnees2['choice'] == 2 ? $donnees['pays2'] : "Tie"));?>
<?php if($donnees2['choice'] > 0):?>
, goal spread : <?=$donnees2['goal'];?>
<?php endif;?></p>
<?php else:?>
<p><?=$donnees2['score1'];?> - <?=$donnees2['score2'];?>, <?=($donnees2['choice'] == 1 ? $donnees['pays1'] : $donnees['pays2']);?> win</p>
<?php endif; ?>
<p style="font-size:20px"><b><?=$donnees2['size'];?></b> <img src="img/ball_small.png" style="vertical-align:middle"></p>
<p style="font-size:12px"><a href="bet.php?id=<?=$donnees['id'];?>&from='index'" class="match1link">Change my bet</a> &nbsp; <a href="removebet.php?id=<?=$donnees['id'];?>&from='index'" class="match1link">Cancel my bet</a></p>

<?php else: ?>
<p style="font-size:12px"><a href="bet.php?id=<?=$donnees['id'];?>&from='index'" class="match1link"><b>BET !</b></a></p><br>
<?php endif; ?>
</div>

</div>

<?php else: ?>
<div class="match2">

<div class="match_diffusion">
<?php if($donnees['tv'] == 1):?>
<img src="img/TF1.jpg" style="vertical-align:middle;height:15px;width:auto;"> &nbsp; 
<?php endif; ?>
<img src="img/beinbis.png" style="vertical-align:middle;height:15px;width:auto;">
</div>

<div class="match_header">
<p><?=$donnees['titre'];?></p>
<p><?=$donnees['daytext'];?></p>
<p><?=$donnees['timetext'];?></p>
</div>

<div class="match_team1"><img src="flags/<?=$donnees['code1'];?>.png" style="vertical-align:middle;"> &nbsp; <?=$donnees['pays1'];?></div>
<div class="match_team2"><?=$donnees['pays2'];?> &nbsp; <img src="flags/<?=$donnees['code2'];?>.png" style="vertical-align:middle;"></div>

</div>
<?php endif; 
endwhile;
endif;?>
</body>
</html>

<script src="js/jquery.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/maps/modules/map.js"></script>
<script src="http://code.highcharts.com/maps/modules/data.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>

<script src="js/viz.js?rand=<?=floor(time()/1000);?>"></script>
<script src="js/viz2.js?rand=<?=floor(time()/1000);?>"></script>

<script>
	<?php 
	$req = $bdd->prepare('SELECT * FROM matchs WHERE timecode > ? AND timecode < ? AND open = 1 ORDER BY timecode');
	$req->execute(array(time(), time() + 2000000));

	while($donnees = $req->fetch()):
		$req2 = $bdd->prepare('SELECT * FROM bets WHERE id = ? AND active = 1');
		$req2->execute(array($donnees['id']));
		
		if($donnees['tie'] == 1):
		
		$goal = array_pad(array(),21,0);		
		$index_min = 10;
		$index_max = 10;
		
		while($donnees2 = $req2->fetch()){
			if($donnees2['choice'] == 0){
				$goal[10] = $goal[10] + $donnees2['size'];
				$betnul = $betnul + $donnees2['size'];
			}
			elseif($donnees2['choice'] == 1){
				$goal[10-$donnees2['goal']] = $goal[10-$donnees2['goal']] + $donnees2['size'];
				$bet1 = $bet1 + $donnees2['size'];
				$index_min = min(10-$donnees2['goal'], $index_min);
			}
			elseif($donnees2['choice'] == 2){
				$goal[10+$donnees2['goal']] = $goal[10+$donnees2['goal']] + $donnees2['size'];
				$bet2 = $bet2 + $donnees2['size'];
				$index_max = max(10+$donnees2['goal'], $index_max);
			}
		}
		//$index_max = max(10 - $index_min, $index_max - 10, 2);
		$index_max = 5;
	?>
	var axis_bet_<?=$donnees['id'];?> = [<?php for($i=10-$index_max;$i<=10+$index_max;$i++):?><?=$i-10;?><?=($i<10+$index_max ? ", " : "");?><?php endfor; ?>];
	var data_bet_<?=$donnees['id'];?> = [<?php for($i=10-$index_max;$i<=10+$index_max;$i++):?>{y: <?=$goal[$i];?>, color: '<?=($i<10 ? $donnees['color1'] : ($i>10 ? $donnees['color2'] : '#848484'));?>'}<?=($i<10+$index_max ? ", " : "");?><?php endfor; ?>];
	graph_<?=$donnees['id'];?> = create_graph_bets('viz_bets_<?=$donnees['id'];?>', axis_bet_<?=$donnees['id'];?>, data_bet_<?=$donnees['id'];?>, "<?=$donnees['pays1'];?>", "<?=$donnees['pays2'];?>");
	
	<?php else: 
		$data = array_pad(array(),5,array_pad(array(),5,0));
		$data1 = array_pad(array(),5,0);
		$data2 = array_pad(array(),5,0);
		$total1 = 0;
		$total2 = 0;
		
		while($donnees2 = $req2->fetch()){
			if($donnees2['score1'] <> $donnees2['score2']){
				$data[$donnees2['score1']][$donnees2['score2']] = $data[$donnees2['score1']][$donnees2['score2']] + $donnees2['size'];
				if($donnees2['score1'] > $donnees2['score2']){
					$total1 = $total1 + $donnees2['size'];
				}
				else{
					$total2 = $total2 + $donnees2['size'];
				}
			}
			elseif($donnees2['choice'] == 1){
				$data1[$donnees2['score1']] = $data1[$donnees2['score1']] + $donnees2['size'];
			}
			elseif($donnees2['choice'] == 2){
				$data2[$donnees2['score1']] = $data2[$donnees2['score1']] + $donnees2['size'];
			}
		}			
	?>
	var data_<?=$donnees['id'];?> = [<?php for($i=0;$i<5;$i++):?>[<?php for($j=0;$j<5;$j++):?><?=$data[$i][$j];?><?=($j<4 ? ", " : "");?><?php endfor;?>]<?=($i<4 ? ", " : "");?><?php endfor;?>];
	var data1_<?=$donnees['id'];?> = [<?php for($i=0;$i<5;$i++):?><?=$data1[$i];?><?=($i<4 ? ", " : "");?><?php endfor;?>];
	var data2_<?=$donnees['id'];?> = [<?php for($i=0;$i<5;$i++):?><?=$data2[$i];?><?=($i<4 ? ", " : "");?><?php endfor;?>];
	var graph_bets_<?=$donnees['id'];?> = create_graph_bets2('viz_bets_<?=$donnees['id'];?>', data_<?=$donnees['id'];?>, data1_<?=$donnees['id'];?>, data2_<?=$donnees['id'];?>, <?=$total1;?>, <?=$total2;?>, "<?=$donnees['pays1'];?>", "<?=$donnees['pays2'];?>")
	
	<?php endif; endwhile; ?>
</script>

