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
			echo $this->Form->input('type', array(
				'empty' => '--All Types--',
				'options' => $payTypes));
			echo $this->element('date_ranger_picker');
			echo $this->Form->submit('Submit', array('class' => 'btn btn-default', 'div' => false));
			echo $this->Form->end()
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

$this->Highchart->render(array(
	'title' => array('text' => 'Daily Revenues'),
	'xAxis' => array('title' => array('text' => 'Dates')),
	'yAxis' => array('title' => array('text' => 'VND')),
	'tooltip' => array('valueSuffix' => ' VND'),
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
		<?php
		if (!empty($gameTotals) && !$this->request->is('ajax')) {
		?>
		<th>All Time</th>
		<?php
		}
		?>
	</thead>
	<tbody>
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
		echo '<td class="total int">' . n(array_sum($totals) / count($rangeDates)) . ' VND</td>';
		echo '<td class="total int">' . n(array_sum($totals)) . ' VND</td>';
		echo '</tr>';

		foreach($data as $v) {
			echo '<tr>';
			echo '<td class="name1">';
            echo $v['name'];
			echo '</td>';

			$t = 0;
			foreach($v['data'] as $kk => $count) {
				$t += $count;
				echo '<td class="int data">' . number_format($count) . '</td>';
			}
			$class = '';
			$rate = '';
			if (isset($total) && !empty($total)) {
				foreach ($total as $value) {
					if (empty($this->request->params['named']['game_id'])) {
						if (isset( $value['game_id']) && $value['game_id'] == $v['game_id']) {
							if ($t > $value['sum']) {
								$rate = round((abs($t - $value['sum']) / $t) * 100, 1) . '%';
								$class = 'glyphicon glyphicon-arrow-up';
							} else if ($t < $value['sum']) {
								$rate = round((abs($t - $value['sum']) / $t) * 100, 1) . '%';
								$class = 'glyphicon glyphicon-arrow-down';
							} else if ($t == $value['sum']) {
								$rate = '&nbsp;<span title="no change">0%</span>';
							}
							if ($rate > 10000) $rate = '&infin;';
						}
					} else {
						if ($value['type'] == $v['game_id']) {
							if ($t > $value['sum']) {
								$rate = round((abs($t - $value['sum']) / $t) * 100, 1) . '%';
								$class = 'glyphicon glyphicon-arrow-up';
							} else if ($t < $value['sum']) {
								$rate = round((abs($t - $value['sum']) / $t) * 100, 1) . '%';
								$class = 'glyphicon glyphicon-arrow-down';
							} else if ($t == $value['sum']) {
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
					$a = $a . " VND&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";
					break;
				case 2 :
					$a = $a . " VND&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";
					break;
				case 3 :
					$a = $a . " VND&nbsp;&nbsp;&nbsp;&nbsp; ";
					break;
				case 4 :
					$a = $a . " VND&nbsp;&nbsp;&nbsp;  ";
					break;
				case 5 :
					$a = $a . " VND&nbsp; ";
					break;
			}
			?>
			<td class="int total total_data"><?php echo $a;?><?php echo ($class != '') ? '<label class="' . $class . '"></label>' : '';?><?php echo ($rate != '') ? $rate : '&nbsp;<span title="no data">VNĐ</span>';?></td>
			<?php
				echo '<td class="int total">' . n($t) . ' VND</td>';
				if (!empty($gameTotals) && !empty($gameTotals[$v['game_id']])) {
					echo '<td class="int total">' . n($gameTotals[$v['game_id']]) . ' VND</td>';
				} else if (!empty($gameTotals) && !$this->request->is('ajax')) {
					echo '<td class="int total">0 VND</td>';
				}
			echo '</tr>';
		}
		?>
	</tbody>
</table>
</div>
</div>
<?php
if (!$this->request->is('ajax')) {
?>
<?php

# error dataTable js when show a game only
if (empty($this->request->params['named']['app_key'])) {
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
}
}
a: