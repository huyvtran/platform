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
	<div class='row-fluid'>
		<div class="span11 offset1">
			<div>
				<?php
				echo $this->Form->create('Payment', array('inputDefaults' => array('div' => false, 'label' => false), 'class' => 'form-inline'));
				echo $this->Form->input('game_id', array('empty' => '--All Games--', 'options' => $games)) . ' ';
//				echo $this->Form->input('type', array(
//					'empty' => '--All Types--',
//					'options' => $payTypes));
				echo $this->element('date_ranger_picker');
				echo $this->Form->submit('Submit', array('class' => 'btn btn-default', 'div' => false));
				echo $this->Form->end();
//				$check = '';
//				if (isset($this->request->params['named']['flag']) && $this->request->params['named']['flag'] == 1) $check = 'checked';
//				echo $this->Form->create('Payment', array('inputDefaults' => array('div' => false, 'label' => false), 'class' => 'form-inline'));
//				if (!in_array($this->Session->read('Auth.User.role'), array('Stats'))) {
//					echo $this->Form->input('flag', array(
//						'type' => 'checkbox',
//						'id' => 'check',
//						'before' => '<label>Show Event: </label> ',
//						'onchange' => "this.form.submit()",
//						$check
//					));
//				}
//				echo $this->Form->end();
				?>
			</div>
		</div>
	</div>
	<?php
}
if (empty($data)) {
	goto a;
}
?>
<div id='chart'></div>
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
		'tooltip' => array('valueSuffix' => ' VNĐ'),
		'plotOptions' => array(
			'series' => array(
				'pointStart' => $pointStart,
				'pointInterval' => $pointInterval
			)
		)), array_reverse($data2));
}
?>
<div class='row'>
	<div class='md-col-12' >
		<?php if (!$this->request->is('ajax')) {?>
			<h4>Data</h4>
		<?php }?>
		<table class='table table-striped table-bordered table-data'>
			<thead>
				<th>Games</th>
				<?php
				for($i=0 ;$i < count($rangeDates); $i++){
					echo "<th class='int'>" . date('d/m', strtotime($rangeDates[$i])) . "</th>";
				}
				?>
				<th class="int">AVG</th>
				<th>In Range</th>
				<?php if (!empty($gameTotals) && !$this->request->is('ajax')) { ?>
					<th>All Time</th>
				<?php } ?>
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
						if (!empty($gameTotals) && !$this->request->is('ajax')) {
							echo '<td></td><td></td><td></td>';
						} else {
							echo '<td></td><td></td>';
						}
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
					if (!empty($gameTotals) && !$this->request->is('ajax')) {
						echo '<td></td><td></td><td></td>';
					} else {
						echo '<td></td><td></td>';
					}
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
			if (!empty($gameTotals) && !$this->request->is('ajax')) {
				echo '<td><strong>' . n(array_sum($gameTotals)) . '</strong></td>';
			}
			echo '</tr>';

			foreach($data as $v) {
				echo '<tr>';
				echo '<td class="name1">';
				echo $v['name'];
				echo '</td>';

				$t = 0;
				foreach($v['data'] as $kk => $count) {
					$t += $count;
					echo '<td class="int data">' . $count . '</td>';
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
//						else {
//							if ($value['Payment']['type'] == $v['game_id']) {
//								if ($t > $value['sum']) {
//									$rate = round((abs($t - $value['sum']) / $t) * 100, 1) . '%';
//									$class = 'glyphicon glyphicon-arrow-up';
//								} else if ($t < $value['sum']) {
//									$rate = round((abs($t - $value['sum']) / $t) * 100, 1) . '%';
//									$class = 'glyphicon glyphicon-arrow-down';
//								} else if ($t == $value['sum']) {
//									$rate = '&nbsp;<span title="no change">0%</span>';
//								}
//								if ($rate > 10000) $rate = '&infin;';
//							}
//						}
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
				if (!empty($gameTotals) && !empty($gameTotals[$v['game_id']])) {
					echo '<td class="int total">' . n($gameTotals[$v['game_id']]) . '</td>';
				} else if (!empty($gameTotals) && !$this->request->is('ajax')) {
					echo '<td class="int total">0</td>';
				}
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
		<script type="text/javascript">
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
					leftColumns: 1,
					rightColumns: 3
				});
			});
		</script>
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