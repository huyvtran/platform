<?php
$this->extend('/Common/blank');
?>

<div class='row'>
	<div class="span12">
		<h3 class='page-header'>
			Bonus Payment
		</h3>
	</div>

	<div class='row'>
		<?php
		echo $this->Form->create('Bonus',array(
			'class' => 'form-horizontal',
			'inputDefaults' => array(
				'label' => array(
					'class' => 'control-label'
				),
				'div' => 'control-group',
				'between' => '<div class="controls">',
				'after' => '</div>',
			)
		));

        if ($this->action == 'admin_edit') {
            echo $this->Form->input('id');
        }
		?>
		<div class="span4">
			<?php
			echo $this->Form->input('user_id', array(
				'type' => 'text',
				'label' => array(
					'text' => 'User ID <span style="color: red">(*)</span>',
					'class' => 'control-label',
				)
			));

			echo $this->Form->input('bonus', array(
				'type' => 'text',
				'label' => array(
					'class' => 'control-label',
					'text' => 'Bonus Money <span style="color: red">(*)</span>'
				),
			));

			echo $this->Form->input('game_id', array(
				'type' => 'select',
				'label' => array(
					'class' => 'control-label',
					'text' => 'Select Game <span style="color: red">(*)</span>'
				),
				'empty' => '-- Game chose --',
				'options' => $games,
			));
			?>

			<div class="form-actions">
				<?php
				echo $this->Form->submit('Submit', array(
					'type' => 'submit',
					'class' => 'btn btn-primary',
					'div' => false
				));
				?>
			</div>
		</div>
		<div class="span8">
			<?php
			echo $this->Form->input('note', array('type' => 'textarea'));
			?>
		</div>

		<?php echo $this->Form->end(); ?>
	</div>
</div>
