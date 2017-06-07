<?php
echo $this->extend('/Common/fluid');

?>
<div class='row'>
	<div class="col-md-8">
		<?php
		echo $this->Form->create('LogRetention', array('inputDefaults' => array('div' => false, 'label' => false), 'class' => 'form-inline'));
		echo $this->Form->input('game_id', array('empty' => '-- All Games --'));
		echo $this->element('date_ranger_picker');
		echo $this->Form->submit('Submit', array('class' => 'btn btn-default', 'div' => false));
		echo $this->Form->end()
		?>
	</div>

	<div  class='col-md-3 col-md-offset-1'>
		<div class="btn-group">
            <a href="<?php echo $this->Html->url(array_merge(array('1'), $this->request->params['named'])) ?>" class="btn btn-default <?php echo $this->Nav->thisSlug('1') ?>"><i class='glyphicon glyphicon-transfer'></i> 1 Days</button></a>
			<a href="<?php echo $this->Html->url(array_merge(array('3'), $this->request->params['named'])) ?>" class="btn btn-default <?php echo $this->Nav->thisSlug('3') ?>">3 Days</button></a>
			<a href="<?php echo $this->Html->url(array_merge(array('7'), $this->request->params['named'])) ?>" class="btn btn-default <?php echo $this->Nav->thisSlug('7') ?>">7 Days</a>
			<a href="<?php echo $this->Html->url(array_merge(array('30'), $this->request->params['named'])) ?>" class="btn btn-default <?php echo $this->Nav->thisSlug('30') ?>">30 Days</a>
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
	'title' => array('text' => 'Retention ' . $this->request->params['pass'][0] . ' days'),
	'xAxis' => array('title' => array('text' => 'Days')),
	'yAxis' => array('title' => array('text' => 'Retention')),
	'tooltip' => array('valueSuffix' => '%'),
	'plotOptions' => array(
		'series' => array(
			'pointStart' => $pointStart,
			'pointInterval' => $pointInterval
		)
	)), $data);
?>
<div class='row'>
<div class='md-col-12' style='overflow:auto'>
<table class='table table-striped table-bordered table-data'>
	<thead>
		<th>Games</th>
		<?php
			for($i=0 ;$i < count($rangeDates); $i++){
				echo "<th class='int'>" . date('d/m', strtotime($rangeDates[$i])) . "</th>";
			}
		?>
		<th class='int'>In Range</th>
	</thead>
	<tbody>
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

		foreach($data as $v) {
			$range = 0;
			echo '<tr>';
			echo '<td>' . $v['name'] . '</td>';
			foreach($v['data'] as $kk => $count) {
				$range += $count;
				echo '<td class="int">' . n($count) . '%';
				echo '</td>';
			}
			echo '<td class="int total">' . n($range / count($v['data'])) . '% </td>';
			echo '</tr>';
		}
		?>
	</tbody>
</table>
</div>
</div>
<script type="text/javascript">
	// overwrite number's format in chart
	function highchartNumberFormat(number)
	{
		return number;
	}

</script>
<?php
a: