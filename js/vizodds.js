function create_graph_histo_odds(div, data, pays1, pays2, color1, color2, tie) {
		
		var a1 = [],
			an = [],
			a2 = [],
			volume = [],
			dataLength = data.length;
			
		for (i = 0; i < dataLength; i++) {
			if(data[i][1]>0){
				a1.push([data[i][0],data[i][4]/data[i][1]]);
			}
			else {
				a1.push([data[i][0],0]);
			}
			if(data[i][2]>0){
				an.push([data[i][0],data[i][4]/data[i][2]]);
			}
			else {
				an.push([data[i][0],0]);
			}
			if(data[i][3]>0){
				a2.push([data[i][0],data[i][4]/data[i][3]]);
			}
			else {
				a2.push([data[i][0],0]);
			}
			volume.push([data[i][0],data[i][4]]);
		}
		
		var max_x = Math.max(a1[dataLength-1][1], an[dataLength-1][1], a2[dataLength-1][1])*1.5;
		
		if(tie == 1){
			var series = [{
				title: 'Team 1',
				data: a1
			},{
				title: 'tie',
				data: an
			},{
				title: 'Team 2',
				data: a2
			}, {
				type: 'area',
				data: volume,
				yAxis: 1
			}];
		}
		else{
			var series = [{
				title: 'Team 1',
				data: a1
			},{
				title: 'Team 2',
				data: a2
			}, {
				type: 'area',
				data: volume,
				yAxis: 1
			}];
		}
		
		if(tie == 1){
			colors = [color1, '#6E6E6E', color2 , '#FAAC58'];
		}
		else{
			colors = [color1, color2 , '#FAAC58'];
		}
		
		var chart1 = new Highcharts.Chart({
			chart: {
				type: 'line',
				renderTo: div,
				backgroundColor: 'rgba(255, 255, 255, 0.01)'
			},
			colors: colors,
			plotOptions: {
				series: {
					pointPadding: 0.1
				},
				line: {
					marker: {
						enabled: false
					}
				},
				area: {
					stacking: 'normal',
					marker: {
						enabled: false
					}
				}
			},
			title: {
				text: ''
			},
			subtitle: {
				text: ''
			},
			xAxis: {
				labels: {
					formatter: function() {
						var date = new Date(this.value*1000);
						return ('0' + date.getHours()).slice(-2) + ':' + ('0' + date.getMinutes()).slice(-2);
					}
				},
				tickInterval: 43200,
			},
			yAxis: [{
				title: {
					text: ''
				},
				labels: {
					enabled: true
				},
				opposite: true,
				gridLineWidth: 0.5,
				minorGridLineWidth: 0,
				tickInterval: 1.0,
				startOnTick: true,
				height: '60%',
				max: max_x,
				min: 1
			}, {
				title: {
					text: ''
				},
				labels: {
					enabled: false
				},
				gridLineWidth: 0.0,
				minorGridLineWidth: 0,
				top: '65%',
				height: '35%'
			}],
			tooltip: {
				shared: true,
				formatter: function() {
					var date = new Date(this.x*1000);
					var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
					var months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
					var s = '<b>' + days[date.getDay()] + ', ' + date.getDate() + ' ' + months[date.getMonth()] + ' ' + ('0' + date.getHours()).slice(-2) + ':' + ('0' + date.getMinutes()).slice(-2) + '</b>';
					s += '<br/><b>Odds :</b>'
					if(this.points[2+tie].y > 0){
						/*s += '<br/>' + pays1 + ': ' + (this.points[3].y/this.points[0].y).toFixed(2);
						s += '<br/>Tie' + ': ' + (this.points[3].y/this.points[1].y).toFixed(2);
						s += '<br/>' + pays2 + ': ' + (this.points[3].y/this.points[2].y).toFixed(2);*/
						s += '<br/>' + pays1 + ': ' + this.points[0].y.toFixed(2);
						if(tie == 1){
							s += '<br/>Tie' + ': ' + this.points[1].y.toFixed(2);
						}
						s += '<br/>' + pays2 + ': ' + this.points[1+tie].y.toFixed(2);
					}
					s += '<br/><b>' + this.points[2+tie].y + ' balls</b>'
					return s;
				}
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
			series: series
		});
		
		return chart1;
		
	}
	
	