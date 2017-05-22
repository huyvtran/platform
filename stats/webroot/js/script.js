function isMobile() {
	return (/android|webos|iphone|ipod|blackberry/i.test(navigator.userAgent.toLowerCase()));
}
$(function() {

$("select:not([multiple], .picker__select--month, .picker__select--year, .notchosen)").chosen({disable_search_threshold: 10});	


// Set Highchart's defaults
if (typeof Highcharts != 'undefined') {

	Highcharts.setOptions({
		chart: {
			marginRight: 170
		},
		legend: {
			layout: 'vertical',
			align: 'right',
			itemHiddenStyle: {color: '#aaa'},
			floating: true,
			borderWidth: 0,
			verticalAlign: 'top',
			x: 0,
			y: -10,
			reversed: true,
			width: 140
		},
		xAxis: {
			labels: {
				y: 20
			}
		},
		plotOptions: {
			line: {
				dataLabels: {
					enabled: true,
					padding: 5,
					formatter : function(){
						if (this.series.yAxis.getExtremes().dataMax * 70 / 100 < this.y){
							if (typeof(highchartNumberFormat) === 'function') {
								return highchartNumberFormat(this.y);
							} else {
								return Highcharts.numberFormat(this.y, 0, '.', ',');
							}
						}
					}
				},
				enableMouseTracking: true
			},
			series: {
				marker: {
					enabled: false
				},
				events: {
					legendItemClick: function(event) {
						if (!this.visible)
							return true;

						var _redraw = chart.redraw;
						chart.redraw = function(){};                            
						
						var seriesIndex = this.index;
						var series = this.chart.series;
						
						for (var i = 0; i < series.length; i++)
						{
							if (series[i].index != seriesIndex)
							{
								
								series[i].visible ? series[i].hide() : series[i].show();
							} 
						}
						chart.redraw = _redraw;
						chart.redraw();
						return false;
					},
					click: function() {
						var _redraw = chart.redraw;
						chart.redraw = function(){};                            
						
						var seriesIndex = this.index;
						var series = this.chart.series;
						
						for (var i = 0; i < series.length; i++)
						{
							if (series[i].index != seriesIndex)
							{
								
								series[i].visible ? series[i].hide() : series[i].show();
							} 
						}
						chart.redraw = _redraw;
						chart.redraw();					
					}
				}
			}			
		},
		credits: {
			enabled: true,
			text: 'Mobgame',
			href: 'http://mobgame.mobi'
		},
		lang: {
			decimalPoint : ','
		},
		exporting: {
			buttons: {
				printButton : {
					enabled: false
				}
			},
			enabled : false
		}
	});

	if (isMobile()) {
		Highcharts.setOptions({
			legend: {
				align: 'center',
				verticalAlign: 'bottom',
				layout: 'horizontal'
			},
			chart: {
				marginRight: 0
			}
		});
	}
}

$('body').on('click', '.table tr', function() {
	if ($(this).is('.info')) {
		$(this).removeClass('info');
		calculateSelected($(this).parents('.table'));
	} else {
		$(this).addClass('info');
		calculateSelected($(this).parents('.table'));
	}
})

function calculateSelected(table)
{
	var tdnum = table.find("th").length;
	if (!tdnum) {
		tdnum = table.find('tr').eq(0).find('td');
	}
	var i, innerText, t, totalRows = [];

	if (tdnum > 0) {
		for(i = 1; i <= tdnum; i++) {
			t = 0;
			totalRows[i] = '';

			table.find("tr.info td:nth-child(" + i + ")").each(function(){
				var data = $(this).text().split(' ');
				var input = data[0];
				innerText = $.trim(input).match(/^([0-9\.\,]*)(\$)?$/i);

				if (innerText == null) {
					t = 0;
					return false;
				}
				innerText = innerText[1];
				t += parseFloat(innerText.replace(/[\,]/g,""));

			});
			
			table.find(".selected-total td:nth-child(" + i + ")").text(Highcharts.numberFormat(t, 0, '.', ','));
		}
	}
}

})

$(document).ready(function() {
	$('.dropdown-submenu [data-toggle=dropdown]').on('click', function(event) {
		// Avoid following the href location when clicking
		event.preventDefault();
		// Avoid having the menu to close when clicking
		event.stopPropagation();
	});
});

$(document).on({
	ajaxStart: function() { NProgress.start();},
	ajaxStop: function() {NProgress.done();}
});