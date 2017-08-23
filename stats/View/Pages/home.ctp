<?php
$this->extend('/Common/fluid');
$role = $this->Session->read('Auth.User.role');
if (!empty($dauIndexPermission) || !empty($niuIndexPermission)) {
?>
    <div class="box">
        <div class="box-body">
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

<div class="box">
    <div class="box-body">
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
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Daus</h3>
    </div>
    <div class="box-body">
        <div style="padding-left: 10px;" class="table-responsive">
            <div id="daus-datatable">
            </div>
        </div>
    </div>
</div>
<?php
}
if (!empty($niuIndexPermission)) {
?>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Nrus</h3>
    </div>
    <div class="box-body">
        <div style="padding-left: 10px;" class="table-responsive">
            <div id="nius-datatable"></div>
        </div>
    </div>
</div>
<?php
}
?>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Revenues</h3>
    </div>
    <div class="box-body">
        <div style="padding-left: 10px;" class="table-responsive">
            <div id="rev-datatable"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(function() {
		<?php 
		if (!empty($game)) {
			if (count($game) == 1) {
				$gameId = $game['0']['Game']['id'];
			} else {
				$gameId = Hash::extract($game, '{n}.Game.id');
			}
		} else {
			$gameId = '';
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

        $revURL = $this->Html->url(array(
            'controller' => 'Revenues',
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

        $.get('<?php echo $revURL ?>', function(html) {
            $("#rev-datatable").append(html);
        });

        $('select').chosen();
	})	

</script>