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
$text = $date." ".$heure." match.php ".$_SESSION['username']."\n";


fwrite($compteur_f, $text);
fclose($compteur_f);

if(!isset($_GET['id'])){
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

$req = $bdd->prepare('SELECT * FROM matchs WHERE id = ?');
$req->execute(array($_GET['id']));
if(!($donnees = $req->fetch())){
	header('Location: http://worldcup2014.olympe.in/index.php');
	exit;
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
<title><?=$donnees['pays1'];?> - <?=$donnees['pays2'];?></title>
<META http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<link href="./css/style.css?rand=<?=floor(time()/1000);?>" rel="stylesheet">
</head>

<body bgcolor="<?=( time() < $donnees['timecode'] && time() + 2000000 > $donnees['timecode'] && $donnees['open'] == 1 ? '#E1F5A9' : '#E6E6E6' );?>">

<div class="match_general" style="height:570px;">

<div class="match_header">
<p><?=$donnees['titre'];?></p>
<p><?=$donnees['daytext'];?></p>
<p><?=$donnees['timetext'];?></p>
</div>

<div class="match_cotes">
<p><?=(time() < $donnees['timecode'] ? 'Current' : 'Final');?> odds</p>
<?php $sumbets = $donnees['bet1'] + $donnees['bet2'] + $donnees['betnul'];?>
<p style="font-size:25px">
<font color=<?=$donnees['color1'];?>><?=($donnees['bet1'] > 0 ? number_format($sumbets/$donnees['bet1'], 2, ".", "") : "-");?></font> &nbsp; 
<?php if($donnees['tie'] == 1): ?>
<font color=#848484><?=($donnees['betnul'] > 0 ? number_format($sumbets/$donnees['betnul'], 2, ".", "") : "-");?></font> &nbsp; 
<?php endif; ?>
<font color=<?=$donnees['color2'];?>><?=($donnees['bet2'] > 0 ? number_format($sumbets/$donnees['bet2'], 2, ".", "") : "-");?></font></p>
</div>

<div class="match_team1" <?=($donnees['issue']==1 ? "style=\"font-weight:bold\"" : "");?>><img src="flags/<?=$donnees['code1'];?>.png" style="vertical-align:middle;"> &nbsp; <?=$donnees['pays1'];?></div>
<div class="match_team2" <?=($donnees['issue']==2 ? "style=\"font-weight:bold\"" : "");?>><?=$donnees['pays2'];?> &nbsp; <img src="flags/<?=$donnees['code2'];?>.png" style="vertical-align:middle;"></div>

<?php if($donnees['tie'] == 1): ?>
<div id="viz_bets_<?=$donnees['id'];?>" style="width: 200px; height: 100px; margin:auto; position:absolute; top:120px; left: 0px; right: 0px"></div>
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
<div id="viz_odds_<?=$donnees['id'];?>" style="width: 700px; height: 300px; margin:auto; position:absolute; top:270px; left: 0px; right: 0px"></div>


<div class="match_stats">
<p><?=$nb_bets;?> bets / <?=$total_ball;?> <img src="img/ball_small.png" style="vertical-align:middle;height:20px;width:auto;"></p>
</div>

<?php if(time() > $donnees['timecode']):?>
<div class="score1"><?=($donnees['issue'] >= 0 ? $donnees['score1'] : '-' );?></div>
<div class="score2"><?=($donnees['issue'] >= 0 ? $donnees['score2'] : '-' );?></div>
<?php endif; ?>

</div>


<?php $req2 = $bdd->prepare('SELECT * FROM bets WHERE id = ? AND active = 1 ORDER BY '.($donnees['issue'] >= 0 ? 'profit' : 'size').' DESC');
$req2->execute(array($donnees['id']));
while($donnees2 = $req2->fetch()):
	$req3 = $bdd->prepare('SELECT * FROM users WHERE username = ?');
	$req3->execute(array($donnees2['username']));
	$donnees3 = $req3->fetch();
	?>

<div class="player<?=(time() > $donnees['timecode'] ? '_after' : '');?>">

<div class="player_name"><a href="#" onClick="MyWindow=window.open('http://worldcup2014.olympe.in/player.php?username=<?=$donnees3['username'];?>','User<?=$donnees3['username'];?>','width=500,height=400,scrollbars=1').focus(); return false;" class="player_name_link">
<?=ucfirst(strtolower($donnees3['firstname']));?> <?=ucfirst(strtolower($donnees3['lastname']));?>
</a></div>
<div class="player_credit" <?=(time() > $donnees['timecode'] ? 'style="font-size:15px"' : '');?>><?=$donnees2['size'];?> <img src="img/ball_small.png" style="vertical-align:middle"></div>

<?php if( time() > $donnees['timecode'] ): ?>
<div class="match_player_choice">
<?php if($donnees['tie'] == 1): ?>
<?=($donnees2['choice'] == 1 ? $donnees['pays1'] : ($donnees2['choice'] == 2 ? $donnees['pays2'] : "Tie"));?>
<?php if($donnees2['choice'] > 0):?>
, goal spread : <?=$donnees2['goal'];?>
<?php endif;
else:?>
<?=($donnees2['choice'] == 1 ? $donnees['pays1'] : $donnees['pays2']);?>, <?=$donnees2['score1'];?> - <?=$donnees2['score2'];?>
<?php endif; ?>
</div>

<div class="match_player_gain">
Profit : <b><?=($donnees['issue'] >= 0 ? ($donnees2['profit'] > 0 ? '+' : '').number_format($donnees2['profit'], 1, ".", "") : '-');?></b> <img src="img/ball_small.png" style="vertical-align:middle">
</div>
<?php endif; ?>

</div>

<?php endwhile; ?>

</body>

</html>

<script src="js/jquery.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/maps/modules/map.js"></script>
<script src="http://code.highcharts.com/maps/modules/data.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>



<script src="js/viz.js?rand=<?=floor(time()/1000);?>"></script>
<script src="js/viz2.js?rand=<?=floor(time()/1000);?>"></script>
<script src="js/vizodds.js?rand=<?=floor(time()/1000);?>"></script>

<script>
	<?php 
	$req2 = $bdd->prepare('SELECT * FROM bets WHERE id = ? AND active = 1 ORDER BY size DESC');
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
	create_graph_bets('viz_bets_<?=$donnees['id'];?>', axis_bet_<?=$donnees['id'];?>, data_bet_<?=$donnees['id'];?>, "<?=$donnees['pays1'];?>", "<?=$donnees['pays2'];?>");
	
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
	
	var data_odds_<?=$donnees['id'];?> = [
	<?php
	$req2 = $bdd->prepare('SELECT * FROM bets WHERE id = ? AND active = 1 ORDER BY timebet');
	$req2->execute(array($donnees['id']));
	$total_a1 = 0;
	$total_an = 0;
	$total_a2 = 0;
	$i = 0;
	$last_time = 0;
	while($donnees2 = $req2->fetch()):	
		if($donnees2['choice'] == 0){
			$total_an = $total_an + $donnees2['size'];
		}
		elseif($donnees2['choice'] == 1){
			$total_a1 = $total_a1 + $donnees2['size'];
		}
		elseif($donnees2['choice'] == 2){
			$total_a2 = $total_a2 + $donnees2['size'];
		}
		$total = $total + $donnees2['size'];
		if($donnees2['timebet'] - $last_time > 3600):
			$last_time = $donnees2['timebet'];
			$i = $i + 1;
		?>
		<?=($i > 1 ? ',' : '');?>[<?=$donnees2['timebet'];?>, <?=$total_a1;?>, <?=$total_an;?>, <?=$total_a2;?>, <?=$total;?>]
	<?php endif; endwhile; ?>];
	var graph_odds_<?=$donnees['id'];?> = create_graph_histo_odds('viz_odds_<?=$donnees['id'];?>', data_odds_<?=$donnees['id'];?>, "<?=$donnees['pays1'];?>", "<?=$donnees['pays2'];?>", "<?=$donnees['color1'];?>", "<?=$donnees['color2'];?>", <?=$donnees['tie'];?>);
</script>