<?php

App::uses('AppHelper', 'Helper');

/**
 * Default configs use for only basic line,
 * you must change configs to render other charts type
 */
class HighchartHelper extends AppHelper {

	public $helpers = array('Js');

	private $_defaults = array(
		'chart' => array(
			'renderTo' => 'chart',
			'type' => 'line',
			'marginBottom' => 100
		),
		'title' => array(
			'text' => 'Title is missing'
		),
		'xAxis' => array(
			'title' => array(
				'text' => "xAxis's title for  is missing"
			),
			'type' => 'datetime',
			'dateTimeLabelFormats' => array("day" => "%e/%m")
		),
		'yAxis' => array(
			'title' => array(
				'text' => "yAxis's title for is missing"
			),
			'min' => 0,
			'plotLines' => array(array(
				'value' => 0,
				'width' => 1,
				'color' => '#808080'
			))
		),
		'legend' => array(
			'x' => 0,
			'y' => -10,
		),
		'tooltip' => array(
			'formatter' => "____function(){

				if (this.series.pointInterval != 1) {
					if (this.series.pointInterval >= 86400000){
						var format = '%A, %d %b, %Y';
					}else{ 
						var format = '%H:%M';
					}
					var dateOrString = Highcharts.dateFormat(format, this.x);
				} else {
					dateOrString = this.x;
				}

				var suffix = '';
				if (this.series.tooltipOptions.valueSuffix !== undefined) {
					suffix = this.series.tooltipOptions.valueSuffix;
				}
				if (typeof(highchartNumberFormat) === 'function') {
					var y = highchartNumberFormat(this.y);
				} else {
					var y = Highcharts.numberFormat(this.y, 0, '.', ',');
				}
				
				return '<strong>' + this.series.name + '</strong><br/>'
						+ dateOrString + '<br/>'
						+ this.series.yAxis.axisTitle.text + ': <strong>' +  y + '</strong>'
						+ suffix;
			}____"
		),
		'plotOptions' => array(
			'series' => array(
				'events' => array(
					'legendItemClick' => "____function(event) {
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
					}____",
					'click' => '____function() {
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
					}____'
				)
			)
		),
        'credits' => array(
        	'enabled' => true,
        	//'text' => 'Mob Game',
        	//'href' => 'http://mobgame.vn'
        ),
        'lang' => array(
        	'decimalPoint' => ','
    	)
	);

	public function render($options, $data)
	{
		$tmp = current($data);
		$keys = array_keys($tmp);
		foreach ($data as &$serie) {
			$serie['color'] = '#'.substr(md5($serie['name']), 3, 6);;
		}		
		$options['series'] = $data;


        $url = $this->here;
        if(strpos($url,'/game_title:full'))
        {
            $this->_defaults['credits']['href'] = str_replace('/game_title:full','',$url);
            $this->_defaults['credits']['text'] =  'Short Game Title';

        }else
        {
            $this->_defaults['credits']['href'] = $url . '/game_title:full';
            $this->_defaults['credits']['text'] =  'Full Game Title';

        }

		$options = $this->merge($this->_defaults, $options);

		$options = html_entity_decode(json_encode($options));
	    $options = str_replace('\r', '', $options);
	    $options = str_replace('\t', '	', $options);
	    $options = str_replace('\n', '
	    	', $options);
	    $options = str_replace('\/', '/', $options);
	    $options = preg_replace('/(\"____|____\")/', '', $options);
		$this->Js->buffer('var chart = new Highcharts.Chart(' . $options . ');');
	}

	private function merge(array $data, $merge)
	{
		$args = func_get_args();
		$return = current($args);

		while (($arg = next($args)) !== false) {
			foreach ((array)$arg as $key => $val) {
				if (!empty($return[$key]) && is_array($return[$key]) && is_array($val)) {
					$return[$key] = $this->merge($return[$key], $val);
				} elseif (is_int($key)) {
					$return[] = $val;
				} else {
					$return[$key] = $val;
				}
			}
		}
		return $return;
	}

}
	
?>