<style>
	.glyphicon-arrow-down {
		color:red;
	}
	.glyphicon-arrow-up {
		color:green;
	}
	.name1 {
		width: 230px;
	}
	.data {
		width: 90px;
	}
	.total_data {
		width: 135px;
	}
</style>
<?php
if (!$this->request->is('ajax')) {
	echo $this->extend('/Common/fluid');
	?>
    <div class="box">
        <div class="box-body">
				<?php
				echo $this->Form->create('Payment', array('inputDefaults' => array('div' => false, 'label' => false), 'class' => 'form-inline'));
				echo $this->Form->input('game_id', array('empty' => '--All Games--', 'options' => $games)) . ' ';
				echo $this->Form->input('chanel', array(
					'empty' => '--All Chanels --',
					'options' => $chanels,
					'style' => 'width: 154px;'
                ));
				echo $this->element('date_ranger_picker');
				echo $this->Form->submit('Submit', array('class' => 'btn btn-default', 'div' => false));

				if($this->Session->read('Auth.User.role') == 'Admin'){
				    $rate = 0;
				    if( !empty($this->request->params['named']['rate']) ) $rate = $this->request->params['named']['rate'];
                    echo $this->Form->input('rate', array(
                        'type' => 'checkbox',
                        'checked' => $rate,
                        'style' => "float:right;"
                    ));
				}

				echo $this->Form->end();
				?>
		</div>
	</div>
	<?php
}
if (empty($data)) {
	goto a;
}
?>
<?php
if (!$this->request->is('ajax')) { ?>
    <div class="box">
        <div class="box-body">
            <div class="col-md-12">
                <div id='chart'></div>
            </div>
        </div>
    </div>
<?php } ?>
<?php
if (!$this->request->is('ajax')) {
	$pointInterval = 3600 * 1000 * 24;
	$m = (int) date('m', $fromTime) - 1;
	$pointStart = '____Date.UTC(' . date('Y', $fromTime) . ', ' . $m . ', ' . date('d', $fromTime) . ')____';
	if (!empty($event)) {
		$xAxis = array('title' => array('text' => 'Dates'), 'plotLines' => $event);
	} else {
		$xAxis = array('title' => array('text' => 'Dates'));
	}
	$this->Highchart->render(array(
		'title' => array('text' => 'Daily Revenues'),
		'xAxis' => $xAxis,
		'yAxis' => array('title' => array('text' => 'Dollar')),
		'tooltip' => array('valueSuffix' => ' vnđ'),
		'plotOptions' => array(
			'series' => array(
				'pointStart' => $pointStart,
				'pointInterval' => $pointInterval
			)
		)), array_reverse($data2));
}
?>
		<?php if (!$this->request->is('ajax')) {?>
            <div class="box">
			    <div class="box-header with-border">
                    <h3 class="box-title">Data</h3>
                </div>
                <div class="table-responsive">
		<?php } else { ?>
                <div class='row'>
                    <div class='md-col-12' >
                        <?php } ?>
		<table class='table table-striped table-bordered table-data'>
			<thead>
				<th>Games</th>
				<?php
				for($i=0 ;$i < count($rangeDates); $i++){
					echo "<th class='int'>" . date('d/m', strtotime($rangeDates[$i])) . "</th>";
				}
				?>
				<th class="int">AVG</th>
				<th>Total</th>
			</thead>
			<tbody>

			<?php
			if (!empty($this->request->named['game_id'])) {
				if (count($this->request->named['game_id']) > 1) {
					?>
					<tr class="selected-total">
						<td>Selected Rows</td>
						<?php
						foreach($rangeDates as $val) {
							echo '<td></td>';
						}
                        echo '<td></td><td></td>';
						?>
					</tr>
				<?php }?>
			<?php } else { ?>
				<tr class="selected-total">
					<td>Selected Rows</td>
					<?php
					foreach($rangeDates as $val) {
						echo '<td></td>';
					}
                    echo '<td></td><td></td>';
					?>
				</tr>
			<?php }?>

			<?php
			# Calculate totals
			$totals = array();
			foreach($data as $v) {
				foreach($v['data'] as $kk => $count) {
					if (isset($totals[$kk])) {
						$totals[$kk] += $count;
					} else {
						$totals[$kk] = $count;
					}
				}
			}

			# print data to table
			echo '<tr>';
			echo '<td class="total">Total</td>';
			foreach($totals as $val) {
				echo '<td class="total int">' . n($val) . '</td>';
			}
			echo '<td class="total int">' . n(array_sum($totals) / count($rangeDates)) . '</td>';
			echo '<td class="total int">' . n(array_sum($totals)) . '</td>';
			echo '</tr>';

			foreach($data as $v) {
				echo '<tr>';
				echo '<td class="name1">' . $this->Html->link($v['name'], array('controller' => 'Revenues', 'action' => 'country', 'game_id' => $v['game_id']) ) . '</td>';
				echo '</td>';

				$t = 0;
				foreach($v['data'] as $kk => $count) {
					$t += $count;
					echo '<td class="int data">' . n($count) . '</td>';
				}
				$class = '';
				$rate = '';
				if (isset($total) && !empty($total)) {
					foreach ($total as $value) {
						if (empty($this->request->params['named']['game_id'])) {
							if ($value['Payment']['game_id'] == $v['game_id']) {
								if ($t > $value[0]['sum']) {
									$rate = round((abs($t - $value[0]['sum']) / $t) * 100, 1) . '%';
									$class = 'glyphicon glyphicon-arrow-up';
								} else if ($t < $value[0]['sum']) {
									$rate = round((abs($t - $value[0]['sum']) / $t) * 100, 1) . '%';
									$class = 'glyphicon glyphicon-arrow-down';
								} else if ($t == $value[0]['sum']) {
									$rate = '&nbsp;<span title="no change">0%</span>';
								}
								if ($rate > 10000) $rate = '&infin;';
							}
						}
					}
				}
				$a = n($t / count($rangeDates));
				switch (strlen($a)) {
					case 1 :
						$a = $a . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";
						break;
					case 2 :
						$a = $a . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";
						break;
					case 3 :
						$a = $a . "&nbsp;&nbsp;&nbsp;&nbsp; ";
						break;
					case 4 :
						$a = $a . "&nbsp;&nbsp;&nbsp;  ";
						break;
					case 5 :
						$a = $a . "&nbsp; ";
						break;
				}
				?>
				<td class="int total total_data"><?php echo $a;?><?php echo ($class != '') ? '<label class="' . $class . '"></label>' : '';?><?php echo ($rate != '') ? $rate : '&nbsp;<span title="no data">--</span>';?></td>
				<?php
				echo '<td class="int total">' . n($t) . '</td>';

				echo '</tr>';
			}
			?>
			</tbody>
		</table>
        </div>
    </div>
<?php if (!$this->request->is('ajax')) { ?>
	<?php
	# error dataTable js when show a game only
	if (empty($this->request->params['named']['game_id'])) {
		?>
		<!--<script type="text/javascript">
			$(function() {
				var table = $('.table').DataTable({
					"scrollX": "100%",
					"scrollCollapse": true,
					"paging": false,
					"search": false,
					"bSort": false,
					bFilter: false,
					bInfo: false
				} );
				new $.fn.dataTable.FixedColumns(table, {
					leftColumns: 1
				});
			});
		</script>-->
		<?php
	} else {
		?>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.show_hide_event').click(function () {
					var chart = $("#chart").highcharts();
					var _redraw = chart.redraw;
					chart.redraw = function(){};
					var plotBands = chart.xAxis[0].plotLinesAndBands;
					var id = $(this).attr('data-id');
					for (var i = 0; i < plotBands.length; i++)
					{
						if (plotBands[i].id != id) {
							if (plotBands[i].svgElem.visibility != 'hidden') {
								plotBands[i].svgElem.hide();
								plotBands[i].label.hide();
							} else {
								plotBands[i].svgElem.show();
								plotBands[i].label.show();
							}
						} else {
							if (plotBands[i].svgElem.visibility == 'hidden') {
								plotBands[i].svgElem.show();
								plotBands[i].label.show();
							}
						}
					}
					chart.redraw = _redraw;
					chart.redraw();
					return false;
				})
			})
		</script>
		<?php
	}
}
a: