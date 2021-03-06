<?php
session_start();
if(isset($_SESSION['username'])){
	//Enregistrement
	$compteur_f = fopen('data.txt', 'a+');
	$date = date("d-m-Y");
	$heure = date("H:i");
	$text = $date." ".$heure." map.php ".$_SESSION['username']."\n";
	
	
	fwrite($compteur_f, $text);
	fclose($compteur_f);
}
?>
<!DOCTYPE html>
<html>


<head>

	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<title>WorldCup Map</title>
	<script src="bower_components/platform/platform.js"></script>
	<link rel="import" href="bower_components/d3-geomap/d3-geomap.html">
	<link href="./css/style.css?rand=<?=floor(time()/1000);?>" rel="stylesheet">

	<style type="text/css">
		table {
			margin-left:auto;
			margin-right:auto;
			border-collapse: separate;
			border-spacing: 15px 5px;
		}
		th, td {
			text-align: left;
		}
	</style>

</head>

<body>

	<div class="chrome">
	Website is optimized for Chrome. Install from <a href="chrome.php" style="chrome">here</a>.
	</div>

	<br>
	<template id="example" bind>
		<d3-geomap style="width:85%; margin:0 auto; border-radius:50px;border:1px solid #ddd;overflow:hidden"
						map = 'world'
						selected="{{selected}}"
						hover="{{hover}}"
						hoverTemplate="{{hoverTemplate}}"
						theme="{{theme}}"
						pan=true
						zoom=true></d3-geomap>
	</template>
	<br>

	<div style="text-align:center">
		<input type="button" onclick="model.selected = null" value="Clear">
		<input type="button" onclick="changeSelection(this)" style="background-color:#4292c6" value="All Groups" data="groupAll">
		<input type="button" onclick="changeSelection(this)" style="background-color:#8c564b" value="Group A" data="groupA">
		<input type="button" onclick="changeSelection(this)" style="background-color:#ff7f0e" value="Group B" data="groupB">
		<input type="button" onclick="changeSelection(this)" style="background-color:#ffbb78" value="Group C" data="groupC">
		<input type="button" onclick="changeSelection(this)" style="background-color:#2ca02c" value="Group D" data="groupD">
		<input type="button" onclick="changeSelection(this)" style="background-color:#98df8a" value="Group E" data="groupE">
		<input type="button" onclick="changeSelection(this)" style="background-color:#d62728" value="Group F" data="groupF">
		<input type="button" onclick="changeSelection(this)" style="background-color:#ff9896" value="Group G" data="groupG">
		<input type="button" onclick="changeSelection(this)" style="background-color:#9467bd" value="Group H" data="groupH">
		<br/>
		<br/>
		<div id='info' style='text-align:center'></div>
		<div id='info_clicked' style='text-align:center'></div>

	</div>


	<script>

	var groupA_info = '<table><tbody><tr><th>Group</th><th>Match No</th><th>Date</th><th>Time</th><th>Match</th><th>Location</th><th>Time Zone</th></tr><tr><td>A</td><td>1</td><td>Thu Jun/12</td><td>17:00</td><td>Brazil - Croatia</td><td>Arena de São Paulo, São Paulo</td><td>UTC-3</td></tr><tr><td>A</td><td>2</td><td>Fri Jun/13</td><td>13:00</td><td>Mexico - Cameroon</td><td>Estádio das Dunas, Natal</td><td>UTC-3</td></tr><tr><td>A</td><td>17</td><td>Tue Jun/17</td><td>16:00</td><td>Brazil - Mexico</td><td>Estádio Castelão, Fortaleza</td><td>UTC-3</td></tr><tr><td>A</td><td>18</td><td>Wed Jun/18</td><td>18:00</td><td>Cameroon - Croati</td><td>Arena Amazônia, Manaus</td><td>UTC-4</td></tr><tr><td>A</td><td>33</td><td>Mon Jun/23</td><td>17:00</td><td>Cameroon - Brazil</td><td>Brasília</td><td>UTC-3</td></tr><tr><td>A</td><td>34</td><td>Mon Jun/23</td><td>17:00</td><td>Croatia - Mexico</td><td>Recife</td><td>UTC-3</td></tr></tbody></table>',
		groupB_info = '<table><tbody><tr><th>Group</th><th>Match No</th><th>Date</th><th>Time</th><th>Match</th><th>Location</th><th>Time Zone</th></tr><tr><td>B</td><td>3</td><td>Fri Jun/13</td><td>16:00</td><td> Spain - Netherlands</td><td>Arena Fonte Nova, Salvador</td><td>UTC-3</td></tr><tr><td>B</td><td>4</td><td>Fri Jun/13</td><td>18:00</td><td>Chile - Australia</td><td>Arena Pantanal, Cuiabá</td><td>UTC-4</td></tr><tr><td>B</td><td>19</td><td>Wed Jun/18</td><td>16:00</td><td>Spain - Chile</td><td>Estádio do Maracanã, Rio de Janeiro</td><td>UTC-3</td></tr><tr><td>B</td><td>20</td><td>Wed Jun/18</td><td>13:00</td><td>Australia - Netherlands</td><td>Estádio Beira-Rio, Porto Alegre</td><td>UTC-3</td></tr><tr><td>B</td><td>35</td><td>Mon Jun/23</td><td>13:00</td><td>Australia - Spain</td><td>Curitiba</td><td>UTC-3</td></tr><tr><td>B</td><td>36</td><td>Mon Jun/23</td><td>13:00</td><td>Netherlands - Chile</td><td>São Paulo</td><td>UTC-3</td></tr></tbody></table>',
		groupC_info = "<table><tbody><tr><th>Group</th><th>Match No</th><th>Date</th><th>Time</th><th>Match</th><th>Location</th><th>Time Zone</th></tr><tr><td>C</td><td>5</td><td>Sat Jun/14</td><td>13:00 </td><td>Colombia - Greece</td><td>Estádio Mineirão, Belo Horizonte</td><td>UTC-3</td></tr><tr><td>C</td><td>6</td><td>Sat Jun/14</td><td>22:00</td><td>Côte d'Ivoire - Japan</td><td>Arena Pernambuco, Recife</td><td>UTC-3</td></tr><tr><td>C</td><td>21</td><td>Thu Jun/19</td><td>13:00</td><td>Colombia - Côte d'Ivoire</td><td>Estádio Nacional Mané Garrincha, Brasília</td><td>UTC-3</td></tr><tr><td>C</td><td>22</td><td>Thu Jun/19</td><td>19:00</td><td>Japan - Greece</td><td>Estádio das Dunas, Natal</td><td>UTC-3</td></tr><tr><td>C</td><td>37</td><td>Tue Jun/24</td><td>16:00</td><td>Japan - Colombia</td><td>Cuiabá</td><td>UTC-4</td></tr><tr><td>C</td><td>38</td><td>Tue Jun/24</td><td>17:00</td><td>Côte d'Ivoire - Greece</td><td>Fortaleza</td><td>UTC-3</td></tr></tbody></table>",
		groupD_info = '<table><tbody><tr><th>Group</th><th>Match No</th><th>Date</th><th>Time</th><th>Match</th><th>Location</th><th>Time Zone</th></tr><tr><td>D</td><td>7</td><td>Sat Jun/14</td><td>16:00</td><td>Uruguay - Costa Rica</td><td>Estádio Castelão, Fortaleza</td><td>UTC-3</td></tr><tr><td>D</td><td>8</td><td>Sat Jun/14</td><td>18:00</td><td>England - Italy</td><td>Arena Amazônia, Manaus</td><td>UTC-4</td></tr><tr><td>D</td><td>23</td><td>Thu Jun/19</td><td>16:00</td><td>Uruguay - England</td><td>Arena de São Paulo, São Paulo</td><td>UTC-3</td></tr><tr><td>D</td><td>24</td><td>Fri Jun/20</td><td>13:00</td><td>Italy - Costa Rica</td><td>Arena Pernambuco, Recife</td><td>UTC-3</td></tr><tr><td>D</td><td>39</td><td>Tue Jun/24</td><td>13:00</td><td>Italy - Uruguay</td><td>Natal</td><td>UTC-3</td></tr><tr><td>D</td><td>40</td><td>Tue Jun/24</td><td>13:00</td><td>Costa Rica - England</td><td>Belo Horizonte</td><td>UTC-3</td></tr></tbody></table>',
		groupE_info = '<table><tbody><tr><th>Group</th><th>Match No</th><th>Date</th><th>Time</th><th>Match</th><th>Location</th><th>Time Zone</th></tr><tr><td>E</td><td>9</td><td>Sun Jun/15</td><td>13:00</td><td>Switzerland - Ecuador</td><td>Estádio Nacional Mané Garrincha, Brasília</td><td>UTC-3</td><td> </td></tr><tr><td>E</td><td>10</td><td>Sun Jun/15</td><td>16:00 </td><td>France - Honduras</td><td>Estádio Beira-Rio, Porto Alegre</td><td>UTC-3</td><td> </td></tr><tr><td>E</td><td>25</td><td>Fri Jun/20</td><td>16:00</td><td>Switzerland - France</td><td>Arena Fonte Nova, Salvador</td><td>UTC-3</td><td> </td></tr><tr><td>E</td><td>26</td><td>Fri Jun/20</td><td>19:00</td><td>Honduras - Ecuador</td><td>Arena da Baixada, Curitiba</td><td>UTC-3</td><td> </td></tr><tr><td>E</td><td>41</td><td>Wed Jun/25</td><td>16:00</td><td>Honduras - Switzerland</td><td>Manaus</td><td>UTC-4</td><td> </td></tr><tr><td>E</td><td>42</td><td>Wed Jun/25</td><td>17:00</td><td>Ecuador - France</td><td>Rio de Janeiro</td><td>UTC-3</td><td> </td></tr></tbody></table>',
		groupF_info = '<table><tbody><tr><th>Group</th><th>Match No</th><th>Date</th><th>Time</th><th>Match</th><th>Location</th><th>Time Zone</th></tr><tr><td>F</td><td>11</td><td>Sun Jun/15</td><td>19:00</td><td>Argentina - Bosnia-Herzegovina</td><td>Estádio do Maracanã, Rio de Janeiro</td><td>UTC-3</td></tr><tr><td>F</td><td>12</td><td>Mon Jun/16</td><td>16:00</td><td>Iran - Nigeria</td><td>Arena da Baixada, Curitiba</td><td>UTC-3</td></tr><tr><td>F</td><td>27</td><td>Sat Jun/21</td><td>13:00</td><td>Argentina - Iran</td><td>Estádio Mineirão, Belo Horizonte</td><td>UTC-3</td></tr><tr><td>F</td><td>28</td><td>Sat Jun/21</td><td>18:00</td><td>Nigeria - Bosnia-Herzegovina</td><td>Arena Pantanal, Cuiabá</td><td>UTC-4</td></tr><tr><td>F</td><td>43</td><td>Wed Jun/25</td><td>13:00</td><td>Nigeria - Argentina</td><td>Porto Alegre</td><td>UTC-3</td></tr><tr><td>F</td><td>44</td><td>Wed Jun/25</td><td>13:00</td><td>Bosnia-Herzegovina - Iran</td><td>Salvador</td><td>UTC-3</td></tr></tbody></table>',
		groupG_info = '<table><tbody><tr><th>Group</th><th>Match No</th><th>Date</th><th>Time</th><th>Match</th><th>Location</th><th>Time Zone</th></tr><tr><td>G</td><td>13</td><td>Mon Jun/16</td><td>13:00</td><td>Germany - Portugal</td><td>Arena Fonte Nova, Salvador</td><td>UTC-3</td></tr><tr><td>G</td><td>14</td><td>Mon Jun/16</td><td>19:00</td><td>Ghana - United States</td><td>Estádio das Dunas, Natal</td><td>UTC-3</td></tr><tr><td>G</td><td>29</td><td>Sat Jun/21</td><td>16:00</td><td>Germany - Ghana</td><td>Fortaleza</td><td>UTC-3</td></tr><tr><td>G</td><td>30</td><td>Sun Jun/22</td><td>18:00</td><td>United States - Portugal</td><td>Manaus</td><td>UTC-4</td></tr><tr><td>G</td><td>45</td><td>Thu Jun/26</td><td>13:00</td><td>United States - Germany</td><td>Recife</td><td>UTC-3</td></tr><tr><td>G</td><td>46</td><td>Thu Jun/26</td><td>13:00</td><td>Portugal - Ghana</td><td>Brasília</td><td>UTC-3</td></tr></tbody></table>',
		groupH_info = '<table><tbody><tr><th>Group</th><th>Match No</th><th>Date</th><th>Time</th><th>Match</th><th>Location</th><th>Time Zone</th></tr><tr><td>H</td><td>15</td><td>Tue Jun/17</td><td>13:00</td><td>Belgium - Algeria</td><td>Estádio Mineirão, Belo Horizonte</td><td>UTC-3</td></tr><tr><td>H</td><td>16</td><td>Tue Jun/17</td><td>18:00</td><td>Russia - South Korea</td><td>Arena Pantanal, Cuiabá</td><td>UTC-4</td></tr><tr><td>H</td><td>31</td><td>Sun Jun/22</td><td>13:00</td><td>Belgium - Russia</td><td>Rio de Janeiro</td><td>UTC-3</td></tr><tr><td>H</td><td>32</td><td>Sun Jun/22</td><td>16:00</td><td>South Korea - Algeria</td><td>Porto Alegre</td><td>UTC-3</td></tr><tr><td>H</td><td>47</td><td>Thu Jun/26</td><td>17:00</td><td>South Korea - Belgium</td><td>São Paulo</td><td>UTC-3</td></tr><tr><td>H</td><td>48</td><td>Thu Jun/26</td><td>17:00</td><td>Algeria - Russia</td><td>Curitiba</td><td>UTC-3</td></tr></tbody></table>';

	var groupA = ['BRA','HRV','MEX','CMR'],	//Brasil, Croatia, Mexico, Cameroon
		groupB = ['ESP','NLD','CHL','AUS'],	//Spain, Netherlands, Chile, Australia
		groupC = ['COL','GRC','CIV','JPN'],	//Columbia, Greece, Côte d'Ivoire, Japan
		groupD = ['URY','CRI','GBR','ITA'],		//Uruguay, Costa Rica, England, Italy
		groupE = ['CHE','ECU','FRA','HND'],	//Switzerland, Ecuador, France, Honduras
		groupF = ['ARG','BIH','IRN','NGA'],	//Argentina, Bosnia Herzegovina, Iran, Nigeria
		groupG = ['DEU','PRT','GHA','USA'],	//Germany, Portugal, Ghana, USA
		groupH = ['BEL','DZA','RUS','KOR'],	//Belgium, Algeria, Russia, Korea
		groupAll = [].concat(groupA, groupB, groupC, groupD, groupE, groupF, groupG, groupH)


	var current_id,
		clicked = false;

	function changeSelection(b){
		var arr = window[b.getAttribute('data')];
		var fill = b.style.backgroundColor;

		function arrayToMap(arr, fill){
			var r = [];
			for(var i=0;i<arr.length;i++){
				r[arr[i]] = fill;
			}
			return r;
		}

		var s = arrayToMap(arr,fill);
		model.selected = s;
	}

	function id_to_group(id) {
		if (groupA.indexOf(id) > -1) { return 'group A'; }
		else if (groupB.indexOf(id) > -1) { return 'Group B'; }
		else if (groupC.indexOf(id) > -1) { return 'Group C'; }
		else if (groupD.indexOf(id) > -1) { return 'Group D'; }
		else if (groupE.indexOf(id) > -1) { return 'Group E'; }
		else if (groupF.indexOf(id) > -1) { return 'Group F'; }
		else if (groupG.indexOf(id) > -1) { return 'Group G'; }
		else if (groupH.indexOf(id) > -1) { return 'Group H'; }
		else {return name+''; }
	}

	function display_info (id) {
		if (id_to_group(id)=='group A') { return groupA_info; }
		else if (id_to_group(id)=='Group B') { return groupB_info; }
		else if (id_to_group(id)=='Group C') { return groupC_info; }
		else if (id_to_group(id)=='Group D') { return groupD_info; }
		else if (id_to_group(id)=='Group E') { return groupE_info; }
		else if (id_to_group(id)=='Group F') { return groupF_info; }
		else if (id_to_group(id)=='Group G') { return groupG_info; }
		else if (id_to_group(id)=='Group H') { return groupH_info; }
		else { return ''; }
	}

	var model = {
		selected: null,
		theme: {
			backgroundColor: '#f7fbff ',
			defaultFill: '#9ecae1',
			borderWidth: 0.5,
			borderColor: '#000',
			highlightFillColor: '#084594',
			highlightBorderColor: '#000',
			highlightBorderWidth: 0.5,
			cursor: 'crosshair'
		},
		hoverTemplate: function(geography, data) { //this function should just return a string
			console.log(geography.properties.name);
			console.log('clicked='+clicked);
			current_id = geography.id;
			if (clicked==false) {
				document.getElementById('info').innerHTML = display_info(geography.id);
			}
			return '<div class="hoverinfo"><strong>' + geography.properties.name+' '+id_to_group(geography.id) + '</strong></div>';
		}
	}

	window.addEventListener('polymer-ready', function(){
		document.getElementById('example').model = model;

		document.addEventListener('mouseout', function(e) {
			document.getElementById('info').innerHTML = '';
		});

		document.addEventListener('clicked', function(e) {
			clicked = true;
			document.getElementById('info').innerHTML = '';
			document.getElementById('info_clicked').innerHTML = display_info(current_id);

			setTimeout(function(){
				clicked = false;
				document.getElementById('info_clicked').innerHTML = '';
			},1000*5);
		});
	});
	</script>

</body>

</html>


