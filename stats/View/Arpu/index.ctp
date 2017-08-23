<style>
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
			echo $this->Form->create('LogArpuByDay', array('inputDefaults' => array('div' => false, 'label' => false), 'class' => 'form-inline'));
			echo $this->Form->input('game_id', array('empty' => '-- All Games --'));
			echo $this->element('date_ranger_picker');
			echo $this->Form->submit('Submit', array('class' => 'btn btn-default', 'div' => false));
			echo $this->Form->end()
			?>
	</div>
</div>
<?php
}
	if (empty($data)) {
		goto a;
	}
?>
<div class="box">
    <div class="box-body">
        <div class="col-md-12">
            <div id='chart'></div>
        </div>
    </div>
</div>
<?php
if (!$this->request->is('ajax')) {
$pointInterval = 3600 * 1000 * 24;
$m = (int) date('m', $fromTime) - 1;
$pointStart = '____Date.UTC(' . date('Y', $fromTime) . ', ' . $m . ', ' . date('d', $fromTime) . ')____';

$this->Highchart->render(array(
	'title' => array('text' => 'Average revenue per user'),
	'xAxis' => array('title' => array('text' => 'Dates')),
	'yAxis' => array('title' => array('text' => 'Money')),
    'tooltip' => array('valueSuffix' => ' vnđ'),
	'plotOptions' => array(
		'series' => array(
			'pointStart' => $pointStart,
			'pointInterval' => $pointInterval
		)
	)), $data);
}
?>
    <div class="box">
    <div class="box-body">
        <div class="table-responsive">
<table class='table table-striped table-bordered'>
	<thead>
		<th>Games</th>
		<?php
			for($i=0 ;$i < count($rangeDates); $i++){
				echo "<th class='int'>" . date('d/m', strtotime($rangeDates[$i])) . "</th>";
			}
		?>
		<td class='int total'>AVG</td>
		<th class='int total'>In Range</th>
	</thead>
	<tbody>
		<tr class="selected-total">
			<td>Selected Rows</td>
			<?php
			foreach($rangeDates as $val) {
				echo '<td></td>';
			}
			?>
			<td></td><td></td>
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
		echo '<td class="int"><strong>' . n(array_sum($totals) / count($rangeDates)) . '</strong></td>';
		echo '<td class="int"><strong>' . n(array_sum($totals)) . '</strong></td>';
		echo '</tr>';

		foreach($data as $v) {
			$range = 0;
			echo '<tr>';

            echo '<td class="name1">' . $v['name'] . '</td>';
			foreach($v['data'] as $kk => $count) {
				$range += $count;
				echo '<td class="int data">' . n($count) . '</td>';
			}
			$class = '';
			$rate = '';
			$a = n($range / count($rangeDates));
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
			<td class="int total total_data"><?php echo $a;?></td>
		<?php
			echo '<td class="int total">' . n($range) . '</td>';
			echo '</tr>';
		}
		?>
	</tbody>
</table>
        </div>
</div>
</div>

<?php
if (!$this->request->is('ajax')) {
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
//		new $.fn.dataTable.FixedColumns(table, {
//			leftColumns: 0,
//			rightColumns: 0
//		});
	});
</script>-->
<?php
}
a: