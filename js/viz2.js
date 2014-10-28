

	function create_graph_bets2(div, data, data1, data2, total1, total2, pays1, pays2) {
		
		var exp = [],
			exp1 = [],
			exp2 = [];
		
		
		
		for (i=0;i<5;i++){
			exp[i] = [];
			for (j=0;j<5;j++){
				if(i>j){
					exp[i][j] = 2*(total1 + total2)/(total1 + data[i][j]) - 1;
				}
				else{
					exp[i][j] = 2*(total1 + total2)/(total2 + data[i][j]) - 1;
				}
			}
		}
		for(i=0;i<5;i++){
			exp1[i] = 2*(total1 + total2)/(total1 + data1[i]) - 1;
			exp2[i] = 2*(total1 + total2)/(total2 + data2[i]) - 1;
		}
		
		/*var chart1 = new Highcharts.Chart({
			chart: {
				type: 'map',
				renderTo: div,
				backgroundColor: 'rgba(255, 255, 255, 0.01)'
			},*/
		$('#' + div).highcharts('Map',{
			chart: {
				backgroundColor: 'rgba(255, 255, 255, 0.01)',
				margin: 0
			},
			title: {
				text: ''
			},
			subtitle: {
				text: ''
			},
			legend: {
                enabled: false
            },
			exporting: {
				enabled: false
			},
			credits: {
				enabled: false
			},
			colorAxis: {
				min: 0,
				stops: [
					[0, 'rgba(255, 255, 255, 0.01)'],
					[0.01, 'rgba(255, 0, 0, 0.1)'],
					[1, 'rgba(255, 0, 0, 1.0)']
				]
			},
			/*xAxis: {
				lineWidth: 0,
				minorGridLineWidth: 0,
				lineColor: 'transparent',
				labels: {
				    enabled: false
			    },
			    minorTickLength: 0,
			    tickLength: 0,
				title: {
					text: ''
				},
				max: null,
				showEmpty: false
			},
			yAxis: {
				lineWidth: 0,
				minorGridLineWidth: 0,
				lineColor: 'transparent',
				labels: {
				    enabled: false
			    },
			    minorTickLength: 0,
			    tickLength: 0,
				title: {
					text: ''
				},
				min: null,
				showEmpty: false
			},*/
			tooltip: {
				shared: true,
				formatter: function(){
					tooltip = "";
					if(this.point.win == 1){
						tooltip += '<font size="1"><tr>' + pays1;
					}
					else{
						tooltip += '<font size="1"><tr>' + pays2;
					}
					tooltip += ', <b>' + this.point.score1 + ' - ' + this.point.score2 + '</b></tr><br><tr><b>' + this.point.value + ' balls</b></tr><br><tr>Exp. profit: <b>+' + this.point.exp.toFixed(2) + '</b></tr></font>';
					return tooltip;
				},
				useHTML: true,
				hideDelay: 0,
				animation: true
			},
			series: [
			{
				data: [
							{
								"name": "Triangle",
								"path": "M500,0,500,-200,400,-100,500,0z",
								value: data1[0],
								exp: exp1[0],
								score1:0,
								score2:0,
								win:1
							},
							{
								"name": "Triangle",
								"path": "M500,-200,500,0,600,-100,500,-200z",
								value: data2[0],
								exp: exp2[0],
								score1:0,
								score2:0,
								win:2
							},
							{
								"name": "Triangle",
								"path": "M500,-200,500,-400,400,-300,500,-200z",
								value: data1[1],
								exp: exp1[1],
								score1:1,
								score2:1,
								win:1
							},
							{
								"name": "Triangle",
								"path": "M500,-400,500,-200,600,-300,500,-400z",
								value: data2[1],
								exp: exp2[1],
								score1:1,
								score2:1,
								win:2
							},
							{
								"name": "Triangle",
								"path": "M500,-400,500,-600,400,-500,500,-400z",
								value: data1[2],
								exp: exp1[2],
								score1:2,
								score2:2,
								win:1
							},
							{
								"name": "Triangle",
								"path": "M500,-600,500,-400,600,-500,500,-600z",
								value: data2[2],
								exp: exp2[2],
								score1:2,
								score2:2,
								win:2
							},
							{
								"name": "Triangle",
								"path": "M500,-600,500,-800,400,-700,500,-600z",
								value: data1[3],
								exp: exp1[3],
								score1:3,
								score2:3,
								win:1
							},
							{
								"name": "Triangle",
								"path": "M500,-800,500,-600,600,-700,500,-800z",
								value: data2[3],
								exp: exp2[3],
								score1:3,
								score2:3,
								win:2
							},
							{
								"name": "Triangle",
								"path": "M500,-800,500,-1000,400,-900,500,-800z",
								value: data1[4],
								exp: exp1[4],
								score1:4,
								score2:4,
								win:1
							},
							{
								"name": "Triangle",
								"path": "M500,-1000,500,-800,600,-900,500,-1000z",
								value: data2[4],
								exp: exp2[4],
								score1:4,
								score2:4,
								win:2
							},
							{
								"name": "rect2985",
								"path": "M500,-200L600,-300,700,-200,600,-100,500,-200",
								value: data[0][1],
								exp: exp[0][1],
								score1:0,
								score2:1,
								win:2
							},
							{
								"name": "rect2985-1",
								"path": "M300,-200L400,-300,500,-200,400,-100,300,-200",
								value: data[1][0],
								exp: exp[1][0],
								score1:1,
								score2:0,
								win:1
							},
							{
								"name": "rect2985-1-4",
								"path": "M600,-300L700,-400,800,-300,700,-200,600,-300",
								value: data[0][2],
								exp: exp[0][2],
								score1:0,
								score2:2,
								win:2
							},
							{
								"name": "rect2985-1-0",
								"path": "M200,-300L300,-400,400,-300,300,-200,200,-300",
								value: data[2][0],
								exp: exp[2][0],
								score1:2,
								score2:0,
								win:1
							},
							{
								"name": "rect2985-1-9",
								"path": "M500,-400L600,-500,700,-400,600,-300,500,-400",
								value: data[1][2],
								exp: exp[1][2],
								score1:1,
								score2:2,
								win:2
							},
							{
								"name": "rect2985-1-48",
								"path": "M300,-400L400,-500,500,-400,400,-300,300,-400",
								value: data[2][1],
								exp: exp[2][1],
								score1:2,
								score2:1,
								win:1
							},
							{
								"name": "rect2985-1-0-5",
								"path": "M700,-400L800,-500,900,-400,800,-300,700,-400",
								value: data[0][3],
								exp: exp[0][3],
								score1:0,
								score2:3,
								win:2
							},
							{
								"name": "rect2985-1-0-1",
								"path": "M600,-500L700,-600,800,-500,700,-400,600,-500",
								value: data[1][3],
								exp: exp[1][3],
								score1:1,
								score2:3,
								win:2
							},
							{
								"name": "rect2985-1-0-7",
								"path": "M500,-600L600,-700,700,-600,600,-500,500,-600",
								value: data[2][3],
								exp: exp[2][3],
								score1:2,
								score2:3,
								win:2
							},
							{
								"name": "rect2985-1-0-11",
								"path": "M100,-400L200,-500,300,-400,200,-300,100,-400",
								value: data[3][0],
								exp: exp[3][0],
								score1:3,
								score2:0,
								win:1
							},
							{
								"name": "rect2985-1-0-52",
								"path": "M200,-500L300,-600,400,-500,300,-400,200,-500",
								value: data[3][1],
								exp: exp[3][1],
								score1:3,
								score2:1,
								win:1
							},
							{
								"name": "rect2985-1-0-76",
								"path": "M300,-600L400,-700,500,-600,400,-500,300,-600",
								value: data[3][2],
								exp: exp[3][2],
								score1:3,
								score2:2,
								win:1
							},
							{
								"name": "rect2985-1-0-14",
								"path": "M800,-500L900,-600,1000,-500,900,-400,800,-500",
								value: data[0][4],
								exp: exp[0][4],
								score1:0,
								score2:4,
								win:2
							},
							{
								"name": "rect2985-1-0-2",
								"path": "M700,-600L800,-700,900,-600,800,-500,700,-600",
								value: data[1][4],
								exp: exp[1][4],
								score1:1,
								score2:4,
								win:2
							},
							{
								"name": "rect2985-1-0-3",
								"path": "M600,-700L700,-800,800,-700,700,-600,600,-700",
								value: data[2][4],
								exp: exp[2][4],
								score1:2,
								score2:4,
								win:2
							},
							{
								"name": "rect2985-1-0-22",
								"path": "M500,-800L600,-900,700,-800,600,-700,500,-800",
								value: data[3][4],
								exp: exp[3][4],
								score1:3,
								score2:4,
								win:2
							},
							{
								"name": "rect2985-1-0-16",
								"path": "M0,-500L100,-600,200,-500,100,-400,0,-500",
								value: data[4][0],
								exp: exp[4][0],
								score1:4,
								score2:0,
								win:1
							},
							{
								"name": "rect2985-1-0-8",
								"path": "M100,-600L200,-700,300,-600,200,-500,100,-600",
								value: data[4][1],
								exp: exp[4][1],
								score1:4,
								score2:1,
								win:1
							},
							{
								"name": "rect2985-1-0-57",
								"path": "M200,-700L300,-800,400,-700,300,-600,200,-700",
								value: data[4][2],
								exp: exp[4][2],
								score1:4,
								score2:2,
								win:1
							},
							{
								"name": "rect2985-1-0-6",
								"path": "M300,-800L400,-900,500,-800,400,-700,300,-800",
								value: data[4][3],
								exp: exp[4][3],
								score1:4,
								score2:3,
								win:1
							}
						]
			}
			]
		});
		
	}
	