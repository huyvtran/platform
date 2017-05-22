<style>
	#label {
		vertical-align: top;
		font-weight: normal;
	}
	#user {
		margin-left: 5px;
	}
    .filter_action {
        margin-top:10px;
    }
</style>
<?php
	echo $this->extend('/Common/fluid');
	$vips = array(
		User::USER_VIP_DEFAULT  => User::USER_VIP_DEFAULT,
		User::USER_VIP_SILVER_1 => User::USER_VIP_SILVER_1,
		User::USER_VIP_SILVER_2 => User::USER_VIP_SILVER_2,
		User::USER_VIP_SILVER_3 => User::USER_VIP_SILVER_3,
		User::USER_VIP_GOLD_1   => User::USER_VIP_GOLD_1,
		User::USER_VIP_GOLD_2   => User::USER_VIP_GOLD_2,
		User::USER_VIP_GOLD_3   => User::USER_VIP_GOLD_3,
		User::USER_VIP_DIAMOND  => User::USER_VIP_DIAMOND,
	);
?>
<div class='row'>
	<div class="col-md-12">
		<div>
		<?php
			echo $this->Form->create('LogInfoByDay', array('inputDefaults' => array('div' => false, 'label' => false), 'class' => 'form-inline'));
			echo '<div class="form-group">';
			if (!empty($this->request->params['named']['game'])) {
				$title = 'KHTT - Game';
				echo $this->Form->input('game_id', array('empty' => '--All Games--', 'data-placeholder' => '--All Games--', 'value' => empty($this->request->params['named']['game_id']) ? '': $this->request->params['named']['game_id']));
			} else if (!empty($this->request->params['named']['type'])) {
				$title = 'KHTT - Vip';
				echo $this->Form->input('vip', array('empty' => '--All Vip--', 'data-placeholder' => '--All Vip--', 'options' => $vips, 'value' => empty($this->request->params['named']['vip']) ? '': $this->request->params['named']['vip']));
			} else {
				$title = 'KHTT';
				echo $this->Form->input('type', array('empty' => '--All Type--', 'data-placeholder' => '--All Type--', 'value' => empty($this->request->params['named']['type']) ? '': $this->request->params['named']['type']));
			}
			echo $this->element('date_ranger_picker');
			echo $this->Form->submit('Submit', array('class' => 'btn btn-default', 'div' => false));
			echo '</div>';
			echo $this->Form->end();
		?>
		</div>
	</div>
    <div class="col-md-12 filter_action">
    <?php
		echo $this->Form->create('LogInfoByDay', array('inputDefaults' => array('div' => false, 'label' => false, 'url' => array('controller' => 'users', 'action' => 'index')), 'class' => 'form-inline'));
		echo '<label id="label">User still play game (Last login in 7 days): </label>';
        if (!empty($this->request->params['named']['last_login']) && $this->request->params['named']['last_login'] == 1) {
	        $checked = 'checked';
        } else {
	        $checked = '';
        }
		echo $this->Form->input('last_login', array('type' => 'checkbox', 'id' => 'user', 'onchange'=> "this.form.submit()",'checked' => $checked, 'value' => empty($this->request->params['named']['last_login']) ? '': $this->request->params['named']['last_login']));
		echo $this->Form->end();
	?>
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
		'chart' => array('type' => 'line'),
		'title' => array('text' => "$title"),
		'xAxis' => array('title' => array('text' => 'Dates')),
		'yAxis' => array('title' => array('text' => 'Number users')),
		'plotOptions' => array(
			'series' => array(
				'pointStart' => $pointStart,
				'pointInterval' => $pointInterval
			)
		)), $data
	);
?>
<div class='row'>
	<div class='md-col-12'>
		<table class='table table-striped table-bordered table-data responsive'>
			<thead>
				<th>Type</th>
				<?php
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
					if (empty($this->request->params['named']['type']) && empty($this->request->params['named']['game'])) {
						$addCond = array();
						if (isset($this->request->params['named']['fromTime'])) {
							$addCond = array(
								'fromTime' => $this->request->params['named']['fromTime'],
								'toTime' => $this->request->params['named']['toTime']
							);
						}
						echo $this->element('other_link2', array(
							'types' => $types,
							'type'  => $v['game_id'],
							'fromTime' => $fromTime,
							'toTime' => $toTime)
						);
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
?>