<?php
$this->extend('/Common/fluid');
$role = $this->Session->read('Auth.User.role');
if (!empty($dauIndexPermission) || !empty($niuIndexPermission)) {
?>
<div class='row form-stats'>
	<div class="col-md-11 offset1">
		<div>
			<?php
			echo $this->Form->create('LogLoginsByDay', array(
				'url' => array('controller' => 'pages'),
				'inputDefaults' => array('div' => false, 'label' => false), 'class' => 'form-inline'));
			echo '<div class="form-group">';

			echo $this->Form->input('game_id', array(
				'multiple' => 'multiple',
				'empty' => '--All Games--', 'data-placeholder' => '--All Games--',
				'value' => empty($this->request->params['named']['game_id']) ? '': $this->request->params['named']['game_id']
			));
			echo $this->element('date_ranger_picker');
			echo $this->Form->submit('Submit', array('class' => 'btn btn-default', 'div' => false));

			echo '</div>';
			echo $this->Form->end()
			?>
		</div>
	</div>
</div>

<div class='row'>
	<?php
	if ($dauIndexPermission) {
	?>
	<div class='col-md-6'>
		<div id='chart1'></div>
	</div>
	<?php
	}

	if ($niuIndexPermission) {
	?>	
	<div class='col-md-6'>
		<div id='chart2'></div>
	</div>
	<?php
	}
	?>
</div>

<?php
$pointInterval = 3600 * 1000 * 24;
$m = (int) date('m', $fromTime) - 1;
$pointStart = '____Date.UTC(' . date('Y', $fromTime) . ', ' . $m . ', ' . date('d', $fromTime) . ')____';

if (!empty($daus) && $dauIndexPermission) {
	$this->Highchart->render(array(
		'chart' => array('renderTo' => 'chart1'),
		'title' => array('text' => 'Active Users'),
		'xAxis' => array('title' => array('text' => 'Dates')),
		'yAxis' => array('title' => array('text' => 'Active Users')),
		'plotOptions' => array(
			'series' => array(
				'pointStart' => $pointStart,
				'pointInterval' => $pointInterval
			)
		)), array_reverse($daus));
}

if (!empty($nius) && $niuIndexPermission) {
	$this->Highchart->render(array(
		'chart' => array('renderTo' => 'chart2'),
		'title' => array('text' => 'New Registers'),
		'xAxis' => array('title' => array('text' => 'Dates')),
		'yAxis' => array('title' => array('text' => 'New Registers')),
		'plotOptions' => array(
			'series' => array(
				'pointStart' => $pointStart,
				'pointInterval' => $pointInterval
			)
		)), array_reverse($nius));
}

}
?>

<?php
if (!empty($dauIndexPermission)) {
?>
<div id="daus-datatable">
<h3>Daus</h3>
</div>
<?php
}
if (!empty($niuIndexPermission)) {
?>
<div id="nius-datatable">
<h3>Nrus</h3>
</div>
<?php
}
?>
<script type="text/javascript">
	$(function() {
		<?php 
		if (!empty($game)) {
			if (count($game) == 1) {
				$appKey = $game['0']['Game']['app_key'];
				$gameId = $game['0']['Game']['id'];
			} else {
				$appKey = Hash::extract($game, '{n}.Game.app_key');
				$gameId = Hash::extract($game, '{n}.Game.id');
			}
		} else {
			$gameId = '';
			$appKey = '';
		}		
		$dauURL = $this->Html->url(array(
			'controller' => 'daus',
			'action' => 'index',
			'game_id' => $gameId,
			'fromTime' => $fromTime,
			'toTime' => $toTime
		));
		$niuURL = $this->Html->url(array(
			'controller' => 'nius',
			'action' => 'index',
			'game_id' => $gameId,
			'fromTime' => $fromTime,
			'toTime' => $toTime
		));
		?>

		$.get('<?php echo $niuURL ?>', function(html) {
			$("#nius-datatable").append(html);
		});
		$.get('<?php echo $dauURL ?>', function(html) {
			$("#daus-datatable").append(html);
		});		
		$('select').chosen();
	})	

</script>