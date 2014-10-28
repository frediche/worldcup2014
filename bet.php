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
$text = $date." ".$heure." bet.php ".$_SESSION['username']."\n";


fwrite($compteur_f, $text);
fclose($compteur_f);

if(!$_GET["id"]){
	header('Location: http://worldcup2014.olympe.in/index.php');
	exit;
}

$id = $_GET["id"];
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

$req2 = $bdd->prepare('SELECT * FROM bets WHERE username = ? AND id = ? AND active = 1');
$req2->execute(array($_SESSION['username'], $donnees['id']));
if($donnees2 = $req2->fetch()){
	$update = 1;
}
else{
	$update = 0;
}

$reqinfo = $bdd->prepare('SELECT * FROM users WHERE username = ?');
$reqinfo->execute(array($_SESSION['username']));
$info = $reqinfo->fetch();

$credit = $info['solde_credit'] + ($update == 1 ? $donnees2['size'] : 0);
if($credit < 1){
	if($_GET['from'] == 'histo'){
		header('Location: http://worldcup2014.olympe.in/histo.php');
		exit;
	}
	else{
		header('Location: http://worldcup2014.olympe.in/index.php');
		exit;
	}
}

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
<a href="index.php" class="menulink">MATCHS</a> &nbsp; &nbsp;
<a href="histo.php" class="menulink">MY BETS</a> &nbsp; &nbsp;
<a href="rank.php" class="menulink">RANKING</a> &nbsp; &nbsp;
<a href="#" onClick="MyWindow=window.open('http://worldcup2014.olympe.in/tree.php','Tree','width=640,height=700,scrollbars=1').focus(); return false;" class="menulink">TREE</a> &nbsp; &nbsp;
<a href="#" onClick="MyWindow=window.open('http://worldcup2014.olympe.in/map.php','_blank').focus(); return false;" class="menulink">MAP</a> &nbsp; &nbsp;
<a href="rules.php" class="menulink">RULES</a> &nbsp; &nbsp;
</div>

<div class="bet">

<div class="match_header">
<p><?=$donnees['titre'];?></p>
<p><?=$donnees['daytext'];?></p>
<p><?=$donnees['timetext'];?></p>
</div>

<div class="match_cotes">
<p>Current odds</p>
<?php $sumbets = $donnees['bet1'] + $donnees['bet2'] + $donnees['betnul'];?>
<p style="font-size:25px">
<font color=<?=$donnees['color1'];?>><?=($donnees['bet1'] > 0 ? number_format($sumbets/$donnees['bet1'], 1, ".", "") : "-");?></font> &nbsp; 
<?php if($donnees['tie'] == 1): ?>
<font color=#848484><?=($donnees['betnul'] > 0 ? number_format($sumbets/$donnees['betnul'], 1, ".", "") : "-");?></font> &nbsp; 
<?php endif; ?>
<font color=<?=$donnees['color2'];?>><?=($donnees['bet2'] > 0 ? number_format($sumbets/$donnees['bet2'], 1, ".", "") : "-");?></font></p>
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

<form action="getbet.php" method="post">
<fieldset class="bet_choice">
<?php if($donnees['tie'] == 1): ?>
<p><input type="radio" name="choice" required value="1" <?php if($update==1 && $donnees2['choice'] == 1):?>checked<?php endif; ?>/> Win <?=$donnees['pays1'];?>, goal spread : 
<input type="number" name="goal1" min="1" max="10" value="<?=($update == 1 && $donnees2['choice'] == 1 ? $donnees2['goal'] : 1);?>" style="text-align:center;"/></p>
<p><input type="radio" name="choice" required value="2" <?php if($update==1 && $donnees2['choice'] == 2):?>checked<?php endif; ?>/> Win <?=$donnees['pays2'];?>, goal spread :
<input type="number" name="goal2" min="1" max="10" value="<?=($update == 1 && $donnees2['choice'] == 2 ? $donnees2['goal'] : 1);?>" style="text-align:center;"/></p>
<?php if($donnees['tie'] == 1): ?>
<p><input type="radio" name="choice" required value="0" <?php if($update==1 && $donnees2['choice'] == 0):?>checked<?php endif; ?>/> Tie</p>
<?php endif; 
else:?>
<p><?=$donnees['pays1'];?> &nbsp; <input type="number" name="score1" id="score1" min="0" max="10" value="<?=($update == 1 ? $donnees2['score1'] : 0 );?>" style="text-align:center;"/> - 
<input type="number" name="score2" id="score2" min="0" max="10" value="<?=($update == 1 ? $donnees2['score2'] : 0 );?>" style="text-align:center;"/> &nbsp; <?=$donnees['pays2'];?></p>
<?php endif; ?>
</fieldset>
<?php if($donnees['tie']==0): ?>
<fieldset class="bet_penalty" <?=($update == 1 && $donnees2['score1'] <> $donnees2['score2'] ? "style=\"display:none\"" : "" );?>>
<SELECT name="penalty">
<OPTION value="1" <?=($donnees2['choice'] == 1 ? 'selected="selected"' : '' );?>><?=$donnees['pays1'];?></OPTION>
<OPTION value="2" <?=($donnees2['choice'] == 2 ? 'selected="selected"' : '' );?>><?=$donnees['pays2'];?></OPTION>
</SELECT> win on penalty shoot-out.
</fieldset>
<?php endif; ?>
<fieldset class="bet_size">
<p><input type="number" name="size" id="size" min="1" max="<?=min(floor($info['solde_credit']) + ($update == 1 ? $donnees2['size'] : 0),$donnees['max_size']);?>" value="<?=($update == 1 ? $donnees2['size'] : 1);?>" style="text-align:center;"/> <img src="img/ball_medium.png" style="vertical-align:middle"></p>
<input type="hidden" name="id" value="<?=$id;?>"/>
<input type="hidden" name="update" value="<?=$update;?>"/> 
<input type="hidden" name="from" value="<?=$_GET['from'];?>"/> 
</fieldset>
<fieldset class="bet_valid">
<p><input type="submit" value="<?=($update == 1 ? "Change my bet" : "BET !");?>"/></p>
</fieldset>
</form>

<div class="bet_allin"><input type="button" id="allin" value="All in !"/></div>
<!--<div class="bet_allin" id="allin">All in !</div>-->
</div>

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

<?php endif; ?>
</script>

<script>
	$('#score1').on('change',function(){
		if($('#score1').val() == $('#score2').val()){
			$('.bet_penalty').show();
		}
		else{
			$('.bet_penalty').hide();
		}
				
	});

	$('#score2').on('change',function(){
		if($('#score1').val() == $('#score2').val()){
			$('.bet_penalty').show();
		}
		else{
			$('.bet_penalty').hide();
		}				
	});
	
	$('#allin').click(function(){
		$('#size').val(<?=min(floor($info['solde_credit']) + ($update == 1 ? $donnees2['size'] : 0),$donnees['max_size']);?>);
	});
</script>