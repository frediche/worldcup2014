<?php

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
<META HTTP-EQUIV="Refresh" CONTENT="10; URL=http://worldcup2014.olympe.in/matchflow.php?id=<?=$_GET['id'];?>"> 
<link href="./css/style.css?rand=<?=floor(time()/1000);?>" rel="stylesheet">
</head>

<body bgcolor="<?=( time() < $donnees['timecode'] && time() + 2000000 > $donnees['timecode'] && $donnees['open'] == 1 ? '#E1F5A9' : '#E6E6E6' );?>">

<div class="match_general">

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
<font color=#848484><?=($donnees['betnul'] > 0 ? number_format($sumbets/$donnees['betnul'], 2, ".", "") : "-");?></font> &nbsp; 
<font color=<?=$donnees['color2'];?>><?=($donnees['bet2'] > 0 ? number_format($sumbets/$donnees['bet2'], 2, ".", "") : "-");?></font></p>
</div>

<div class="match_team1"><img src="flags/<?=$donnees['code1'];?>.png" style="vertical-align:middle;"> &nbsp; <?=$donnees['pays1'];?></div>
<div class="match_team2"><?=$donnees['pays2'];?> &nbsp; <img src="flags/<?=$donnees['code2'];?>.png" style="vertical-align:middle;"></div>

<div id="viz_bets_<?=$donnees['id'];?>" style="width: 200px; height: 100px; margin:auto; position:absolute; top:100px; left: 0px; right: 0px"></div>

<div class="match_stats">
<p><?=$nb_bets;?> bets / <?=$total_ball;?> <img src="img/ball_small.png" style="vertical-align:middle;height:20px;width:auto;"></p>
</div>

<?php if(time() > $donnees['timecode']):?>
<div class="score1"><?=($donnees['issue'] >= 0 ? $donnees['score1'] : '-' );?></div>
<div class="score2"><?=($donnees['issue'] >= 0 ? $donnees['score2'] : '-' );?></div>
<?php endif; ?>

</div>

</body>

</html>

<script src="http://code.highcharts.com/adapters/standalone-framework.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>

<script src="js/viz.js"></script>

<script>
	<?php 
	$goal = array_pad(array(),21,0);
	$req2 = $bdd->prepare('SELECT * FROM bets WHERE id = ? AND active = 1 ORDER BY size DESC');
	$req2->execute(array($donnees['id']));
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
	$index_max = max(10 - $index_min, $index_max - 10, 2);
	?>
	var axis_bet_<?=$donnees['id'];?> = [<?php for($i=10-$index_max;$i<=10+$index_max;$i++):?><?=$i-10;?><?=($i<10+$index_max ? ", " : "");?><?php endfor; ?>];
	var data_bet_<?=$donnees['id'];?> = [<?php for($i=10-$index_max;$i<=10+$index_max;$i++):?>{y: <?=$goal[$i];?>, color: '<?=($i<10 ? $donnees['color1'] : ($i>10 ? $donnees['color2'] : '#848484'));?>'}<?=($i<10+$index_max ? ", " : "");?><?php endfor; ?>];
	create_graph_bets('viz_bets_<?=$donnees['id'];?>', axis_bet_<?=$donnees['id'];?>, data_bet_<?=$donnees['id'];?>, "<?=$donnees['pays1'];?>", "<?=$donnees['pays2'];?>");
</script>