<?php

//Update de tous les matchs : bet1, bet2, betnul, goal0 à goal20 ; bets : gains
//Update de tous les users : solde_credit, solde_reel (voir tous les bets de chaque user)

//Constantes :
$cagnotte_issue = 0.6;
$cagnotte_goal = 0.4;
$max_nb_match = 70; //64 matchs normalement

//Initialisation du tableau
$goal = array_pad(array(),21,0);

try
{
	$bdd = new PDO('mysql:host=sql2.olympe.in;dbname=b1omsb6t', 'b1omsb6t', 'worldcup2014');
}
catch (Exception $e)
{
		die('Erreur : ' . $e->getMessage());
}
$req = $bdd->query('SET NAMES utf8');

//Update des matchs
$req = $bdd->query('SELECT * FROM matchs');

while($donnees = $req->fetch()){
	$goal = array_pad(array(),21,0);
	$bet1 = 0;
	$bet2 = 0;
	$betnul = 0;
	
	$req2 = $bdd->prepare('SELECT * FROM bets WHERE id = ? AND active = 1');
	$req2->execute(array($donnees['id']));
	
	while($donnees2 = $req2->fetch()){
		if($donnees2['choice'] == 0){
			$goal[10] = $goal[10] + $donnees2['size'];
			$betnul = $betnul + $donnees2['size'];
		}
		elseif($donnees2['choice'] == 1){
			$goal[10-$donnees2['goal']] = $goal[10-$donnees2['goal']] + $donnees2['size'];
			$bet1 = $bet1 + $donnees2['size'];
		}
		elseif($donnees2['choice'] == 2){
			$goal[10+$donnees2['goal']] = $goal[10+$donnees2['goal']] + $donnees2['size'];
			$bet2 = $bet2 + $donnees2['size'];
		}
	}
	
	$req3 = $bdd->prepare('UPDATE matchs SET bet1 = ?, bet2 = ?, betnul = ? WHERE id = ?');
	$req3->execute(array($bet1, $bet2, $betnul, $donnees['id']));
	
	$req4 = $bdd->prepare('UPDATE bets SET issue = -1, gain = 0, profit = 0 WHERE id = ?');
	$req4->execute(array($donnees['id']));
	
	
	$sum_size = $bet1 + $bet2 + $betnul;
	//Mise à jour des gains (si le match est passé, et si il y a des paris)
	if($donnees['issue'] >= 0 && $sum_size > 0){
		$req3 = $bdd->prepare('UPDATE bets SET issue = ? WHERE id = ? AND active = 1');
		$req3->execute(array($donnees['issue'], $donnees['id']));
		
		//Arbre de décision gains "issue"
		$nb_win_issue = 0;
		$sum_size_win_issue = 0;
		if($donnees['issue'] == 1){
			$nb_win_issue = 1;
			if($bet1 > 0){
				$win_issue_A = 1;
				$sum_size_win_issue = $bet1;
			}
			elseif($betnul > 0){
				$win_issue_A = 0;
				$sum_size_win_issue = $betnul;
			}
			elseif($bet2 > 0){
				$win_issue_A = 2;
				$sum_size_win_issue = $bet2;
			}
		}
		elseif($donnees['issue'] == 2){
			$nb_win_issue = 1;
			if($bet2 > 0){
				$win_issue_A = 2;
				$sum_size_win_issue = $bet2;
			}
			elseif($betnul > 0){
				$win_issue_A = 0;
				$sum_size_win_issue = $betnul;
			}
			elseif($bet1 > 0){
				$win_issue_A = 1;
				$sum_size_win_issue = $bet1;
			}
		}
		else{
			if($betnul > 0){
				$nb_win_issue = 1;
				$win_issue_A = 0;
				$sum_size_win_issue = $betnul;
			}
			elseif($bet1 + $bet2 > 0){
				$nb_win_issue = 2;
				$win_issue_A = 1;
				$win_issue_B = 2;
				$sum_size_win_issue = $bet1 + $bet2;
			}
		}
		
		//Arbre de décision des gains "goal"
		$score = $donnees['score2'] - $donnees['score1'] + 10;
		$nb_win_goal = 0;
		$sum_size_win_goal = 0;
		if($goal[$score] > 0){
			$nb_win_goal = 1;
			$win_goal_A = $score;
			$sum_size_win_goal = $goal[$score];
		}
		else{
			$ecart = 1;
			$ok = FALSE;
			while(!$ok){
				if($ecart > 20){
					$ok = TRUE;
				}
				$sum_size_win_goal = ($score + $ecart <= 20 ? $goal[$score + $ecart] : 0) + ($score - $ecart >= 0 ? $goal[$score - $ecart] : 0);
				if($sum_size_win_goal > 0){
					$ok = TRUE;
					$nb_win_goal = ($score + $ecart <= 20 ? 1 : 0) + ($score - $ecart >= 0 ? 1 : 0);
					$win_goal_A = ($score + $ecart <= 20 ? $score + $ecart : $score - $ecart);
					$win_goal_B = ($score + $ecart <= 20 ? $score - $ecart : $score + $ecart);
				}
				$ecart = $ecart + 1;
			}
		}
		
		if($nb_win_issue == 0 || $nb_win_goal == 0){
			echo "Erreur : Match ".$donnees['id']." sans gagnants";
		}
		
		$req2 = $bdd->prepare('SELECT * FROM bets WHERE id = ? AND active = 1');
		$req2->execute(array($donnees['id']));
		
		while($donnees2 = $req2->fetch()){
			$gain = 0;
			if(($nb_win_issue > 0 && $donnees2['choice'] == $win_issue_A) || ($nb_win_issue > 1 && $donnees2['choice'] == $win_issue_B)){
				$gain = $gain + $cagnotte_issue * $sum_size * $donnees2['size'] / $sum_size_win_issue;
			}
			$goal_choice = 10 + ($donnees2['choice'] == 1 ? -1 : ($donnees2['choice'] == 2 ? 1 : 0 )) * ($donnees2['choice'] > 0 ? $donnees2['goal'] : 0);
			if(($nb_win_goal > 0 && $goal_choice == $win_goal_A) || ($nb_win_goal > 1 && $goal_choice == $win_goal_B)){
				$gain = $gain + $cagnotte_goal * $sum_size * $donnees2['size'] / $sum_size_win_goal;
			}
			$req3 = $bdd->prepare('UPDATE bets SET gain = ?, profit = ? WHERE id = ? AND username = ? AND active = 1');
			$req3->execute(array($gain, $gain - $donnees2['size'], $donnees2['id'], $donnees2['username']));
		}
	}
}

$req = $bdd->query('SELECT * FROM users');

while($donnees = $req->fetch()){
	$req2 = $bdd->prepare('SELECT * FROM bets WHERE username = ? AND active = 1');
	$req2->execute(array($donnees['username']));
	$solde_reel = $donnees['solde_init'];
	$solde_credit = $donnees['solde_init'];
	while($donnees2 = $req2->fetch()){
		if($donnees2['issue'] >= 0){
			$solde_reel = $solde_reel - $donnees2['size'] + $donnees2['gain'];
		}
		$solde_credit = $solde_credit - $donnees2['size'] + $donnees2['gain'];
	}

	$req3 = $bdd->prepare('UPDATE users SET solde_reel = ?, solde_credit = ? WHERE username = ?');
	$req3->execute(array($solde_reel, $solde_credit, $donnees['username']));
}

echo "Complete";