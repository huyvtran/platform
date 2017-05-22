<?php 
echo $this->extend('/Common/fluid');
?>
<div class='row'>
	<div class="col-md-7 offset1">
		<div>
			<?php
			echo $this->Form->create('LogEntergamesServerByDay', array('inputDefaults' => array('div' => false, 'label' => false), 'class' => 'form-inline'));
			echo '<div class="form-group">';

			echo $this->Form->input('game_id', array('empty' => '--All Games--', 'data-placeholder' => '--All Games--'));
			// echo $this->Form->input('server_id', array('empty' => '--All Servers--', 'data-placeholder' => '--All Servers--'));
			echo $this->element('date_ranger_picker');
			echo $this->Form->submit('Submit', array('class' => 'btn btn-default', 'div' => false));

			echo '</div>';
			echo $this->Form->end()
			?>
		</div>
	</div>
    <div  class='col-md-4'>
        <div class="btn-group">

            <a href="<?php echo $this->Html->url(array(
                'controller' => 'revenues',
                'action' => 'server',
                'game_id' => @$this->request->params['named']['game_id'],
                'fromTime' => $fromTime,
                'toTime' => $toTime)) ?>"

                class="btn btn-default">
                <i class='glyphicon glyphicon-transfer'></i> Revenue</a>        

            <a  href="<?php echo $this->Html->url(array(
                'controller' => 'nius',
                'action' => 'server',
                'game_id' => @$this->request->params['named']['game_id'],
                'fromTime' => $fromTime,
                'toTime' => $toTime))?>"

                class="btn btn-default">NIU</a>

            <a href="<?php echo $this->Html->url(array(
                'controller' => 'daus',
                'action' => 'server',
                'game_id' => @$this->request->params['named']['game_id'],
                'fromTime' => $fromTime,
                'toTime' => $toTime)) ?>"

                class="btn btn-default">DAU</a>
        </div>
    </div>	
</div>
<?php
	if (empty($data)) {
		goto a;
	}
?>
<div id='chart'></div>
<?php
$pointInterval = 3600 * 1000 * 24;
$m = (int) date('m', $fromTime) - 1;
$pointStart = '____Date.UTC(' . date('Y', $fromTime) . ', ' . $m . ', ' . date('d', $fromTime) . ')____';

$this->Highchart->render(array(
	'title' => array('text' => 'DAU By Servers'),
	'xAxis' => array('title' => array('text' => 'Dates')),
	'yAxis' => array('title' => array('text' => 'Active Users')),
	'plotOptions' => array(
		'series' => array(
			'pointStart' => $pointStart,
			'pointInterval' => $pointInterval
		)
	)), $data);
?>
<div class='row'>
<div class='md-col-12' >
<table class='table table-striped table-bordered table-data responsive'>
	<thead>
		<?php
		if (!empty($this->request->params['named']['game_id'])) {
			echo '<th>Servers</th>';
		} else {
			echo '<th>Games</th>';
		}

		for($i=0 ;$i < count($rangeDates); $i++){
			echo "<th class='int'>" . date('d/m', strtotime($rangeDates[$i])) . "</th>";
		}
		?>
		<th class="int">AVG</th>
		<th class='int'>In Range</th>
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
		echo '<td class="total int">' . n(array_sum($totals) / count($rangeDates)) . '</td>';
		echo '<td class="total int">' . n(array_sum($totals)) . '</td>';
		echo '</tr>';

		foreach($data as $v) {

			$range = 0;
			echo '<tr>';
			echo '<td class="name">';
			if (empty($this->request->params['named']['game_id'])) {
				$addCond = array();
				if (isset($this->request->params['named']['fromTime'])) {
					$addCond = array(
						'fromTime' => $this->request->params['named']['fromTime'],
						'toTime' => $this->request->params['named']['toTime']
					);
				}
				echo $this->Html->link($v['name'], array_merge(array(
						'game_id' => $v['game_id']
					), $addCond));
			} else {
				echo $v['name'];
			}
			echo '</td>';
			foreach($v['data'] as $kk => $count) {
				$range += $count;
				echo '<td class="int">' . n($count) . '</td>';
			}
			echo '<td class="total int">' . n($range / count($rangeDates)) . '</td>';
			echo '<td class="int total">' . n($range) . '</td>';
			echo '</tr>';
		}
		?>
	</tbody>
</table>
</div>
</div>

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
			rightColumns: 2
		});
	});
</script>
<?php
a: