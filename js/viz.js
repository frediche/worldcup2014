

	function create_graph_bets(div, axis, data, pays1, pays2) {
		
		var dataLength = data.length,
			bet1 = 0,
			bet2 = 0,
			betnul = 0;
			
		for (i = 0; i < dataLength; i++) {
			if(axis[i]<0){
				bet1 = bet1 + data[i].y;
			}
			if(axis[i]>0){
				bet2 = bet2 + data[i].y;
			}
			if(axis[i]==0){
				betnul = betnul + data[i].y;
			}
		}
		var bettot = bet1 + bet2 + betnul;
		
		var chart1 = new Highcharts.Chart({
			chart: {
				type: 'column',
				renderTo: div,
				backgroundColor: 'rgba(255, 255, 255, 0.01)'
			},
			title: {
				text: ''
			},
			subtitle: {
				text: ''
			},
			xAxis: {
				categories: axis,
				labels: {
					enabled: true,
					formatter: function() {
						return String(Math.abs(this.value));
					}
				},
				tickLength: 10
			},
			yAxis: {
				min: 0,
				maxPadding: 0.1,
				title: {
					text: ''
				},
				labels: {
					enabled: false
				},
				gridLineWidth: 0.0,
				minorGridLineWidth: 0
			},
			plotOptions: {
				series: {
					pointPadding: 0.1
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
			tooltip: {
				formatter: function() {
					if(this.x < 0){
						tooltip = '<font size="1"><tr>' + pays1 + ', goal spread : ' + (-this.x) + '</tr><br><tr><b>' + this.y + ' balls</b></tr><br><tr>Exp. profit: <b>+' + (2.0*bettot/(bet1 + this.y)-1.0).toFixed(2) + '</b></tr></font>';
					}
					else if(this.x > 0){
						tooltip = '<font size="1"><tr>' + pays2 + ', goal spread : ' + this.x + '</tr><br><tr><b>' + this.y + ' balls</b></tr><br><tr>Exp. profit: <b>+' + (2.0*bettot/(bet2 + this.y)-1.0).toFixed(2) + '</b></tr></font>';
					}
					else{
						tooltip = '<font size="1"><tr>Tie</tr><br><tr><b>' + this.y + ' balls</b></tr><br><tr>Exp. profit: <b>+' + (bettot/betnul-1.0).toFixed(2) + '</b></tr></font>';
					}
					return tooltip;
				},
                useHTML: true,
				hideDelay: 0,
				animation: true,
				shared:true
            },
			plotOptions: {
				column: {
					pointPadding: 0.0,
					borderWidth: 0
				}
			},
			series: [{
				name: '',
				data: data

			}]
		});
		
		return chart1;
		
	}
	