<?php
$this->extend('/Common/blank');
?>
<div class="games form">
<?php echo $this->Form->create('Game'); ?>
	<fieldset>
		<legend>Admin Add Game</legend>
	<?php
		echo $this->Form->input('title');
		echo $this->Form->input('description');
		echo '<div class="form-actions">';
		echo $this->Form->submit('Submit', array('class' => 'btn btn-primary'));
		echo '</div>';
	?>
	</fieldset>
<?php echo $this->Form->end(); ?>
</div>
<div class="actions">
	<h3>Actions</h3>
	<ul>

		<li><?php echo $this->Html->link('List Games', array('action' => 'index')); ?></li>
	</ul>
</div>
