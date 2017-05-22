<?php
echo $this->extend('/Common/fluid');
$group = array(
	'op1' => 'OP1',
	'op2' => 'OP2',
	'op68' => 'OP68',
);
?>
<style>
	.chzn-container {
		margin-right: 10px;
	}
</style>
<div class='row'>
	<div class="col-md-12">
		<h3>Add Group Game</h3>
	<?php
		echo $this->Form->create('User', array('inputDefaults' => array('div' => false, 'label' => false), 'class' => 'form-inline'));
		echo '<div class="form-group">';
		echo $this->Form->input('game_id', array('empty' => '--All Games--', 'data-placeholder' => '--All Games--'));
		echo $this->Form->input('group',   array('empty' => '--Choose Group--', 'data-placeholder' => '--Choose Group--', 'options' => $group, 'class' => 'game_id'));
		echo $this->Form->submit('Submit', array('class' => 'btn btn-default', 'div' => false));
		echo '</div>';
		echo $this->Form->end();
	?>
	</div>
</div>
<div class="row">
	<table class="table table-striped table-bordered table-data responsive">
		<h3>Game Group</h3>
		<thead>
		<th>Games</th>
		<th>Group</th>
		</thead>
		<tbody>
		<?php
		foreach ($game as $key => $value) {
			echo "<tr>";
			echo "<td>" . $value['Game']['title_os'] . "</td>";
			echo "<td>" . strtoupper($value['Game']['group']) . "</td>";
			echo "</tr>";
		}
		?>
		</tbody>
	</table>
</div>