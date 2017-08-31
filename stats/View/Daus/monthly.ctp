<style>
	.glyphicon-arrow-down {
		color:red;
	}
	.glyphicon-arrow-up {
		color:green;
	}
</style>
<?php
echo $this->extend('/Common/fluid');
?>
<div class="box">
    <div class="box-body">
			<?php
			echo $this->Form->create('LogLoginsByMonth', array('inputDefaults' => array('div' => false, 'label' => false), 'class' => 'form-inline'));
			echo $this->Form->input('game_id', array('empty' => '--All Games--', 'options' => $games)) . ' ';
			
			echo $this->element('monthly_ranger_picker');
			echo $this->Form->submit('Submit', array('class' => 'btn btn-default', 'div' => false));
			
			echo $this->Form->end()
			?>
	</div>
</div>
<?php
if (empty($data)) {
	goto end_of_file;
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
$this->Highchart->render(array(
	'title' => array('text' => 'Monthly Active Users'),
	'xAxis' => array(
		'title' => array('text' => 'Months',),
		'categories' => $rangeDates,
	),
	
	'yAxis' => array('title' => array('text' => 'Active users')),
	), array_reverse($data2));
?>

<!-- DATA TABLE -->
<div class="box">
    <div class="box-body">
        <div class="table-responsive">
		<table class='table table-striped table-bordered table-data'>
			<thead>
				
				<!-- HEADER ROW -->
				<th>Month</th>
				<?php foreach ($rangeDates as $date) : ?>
				<th class="int">
					<?php echo date('M Y', strtotime('01-'.$date)); ?>
				</th>
				<?php endforeach; ?>
				<th class="int">AVG</th>
				<th class="int">Total</th>
				
			</thead>			
			<tbody>
				
				<?php
				# Calculate totals
				$totals = array();
				foreach ($data as $d) {
					foreach ($d['data'] as $i => $value) {
						if (isset($totals[$i])) {
							$totals[$i] += $value;
						} else {
							$totals[$i] = $value;
						}
					}
				}
				?>		
				
				<!-- TOTAL ROW -->
				<tr>
					<td class="total">All Games</td>
					<?php foreach ($totals as $total) : ?>
					<td class="total int"><?php echo n($total); ?></td>
					<?php endforeach; ?>
					<td class="total int"><?php echo n(array_sum($totals) / count($rangeDates)); ?></td>
					<td class="total int"><?php echo n(array_sum($totals)); ?></td>
				</tr>
				
				<!-- OTHER ROWS -->
				<?php foreach ($data as $d) : ?>
				<tr>
					<?php $total_inrange = 0; ?>
					<td class="name"><?php echo $d['name']; ?></td>
					<?php foreach ($d['data'] as $i => $value) : $total_inrange += $value; ?>
						<td class="int"><?php echo n($value); ?></td>
					<?php endforeach; ?>
					<?php
						$class = '';
						$rate = '';
						if (isset($total_data) && !empty($total_data)) {
							foreach ($total_data as $value) {
								if ($value['game_id'] == $d['game_id']) {
									if ($total_inrange > $value['sum']) {
										$rate = round((abs($total_inrange - $value['sum']) / $total_inrange) * 100, 1) . '%';
										$class = 'glyphicon glyphicon-arrow-up';
									} else if ($total_inrange < $value['sum']) {
										$rate = round((abs($total_inrange - $value['sum']) / $total_inrange) * 100, 1) . '%';
										$class = 'glyphicon glyphicon-arrow-down';
									} else if ($total_inrange == $value['sum']) {
										$rate = '&nbsp;<span title="no change">0%</span>';
									}
									if ($rate > 10000) $rate = '&infin;';
								}
							}
						}
						$a = n($total_inrange / count($rangeDates));
						switch (strlen($a)) {
							case 1 :
								$a = $a . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";
								break;
							case 2 :
								$a = $a . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";
								break;
							case 3 :
								$a = $a . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ";
								break;
							case 4 :
								$a = $a . "&nbsp;&nbsp;&nbsp;&nbsp;  ";
								break;
							case 5 :
								$a = $a . "&nbsp;&nbsp;&nbsp; ";
								break;
							case 6 :
								$a = $a . "&nbsp; ";
								break;
						}
					?>
					<td class="int total"><?php echo $a;?><?php echo ($class != '') ? '<label class="' . $class . '"></label>' : '';?><?php echo ($rate != '') ? $rate : '&nbsp;<span title="no data">--</span>';?></td>
					<td class="int total"><?php echo n($total_inrange); ?></td>
				</tr>
				<?php endforeach; ?>
				
			</tbody>
		</table>
        </div>
	</div>
</div>
<?php
end_of_file: