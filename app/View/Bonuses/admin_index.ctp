<?php
$this->extend('/Common/blank');
?>

<div class="row">
	<div class="span12">
		<h3 class="page-header"> Bonus Payment Management </h3>
	</div>
</div>

<div class='row'>
	<?php
	echo $this->Form->create('Bonus', array(
		'action' => 'index',
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
	?>
	<div class="span4">
		<?php
		echo $this->Form->input('game_id.', array(
			'type' => 'select',
			'label' => array(
				'class' => 'control-label',
				'text' => 'Game'
			),
			'multiple',
			'options' => $games,
			'selected' => !empty($this->request->params['named']['game_id']) ? $this->request->params['named']['game_id'] : ''
		));

		echo $this->Form->input('username', array(
			'type' => 'text',
			'placeholder' => 'id, username or email.',
			'label' => array(
				'class' => 'control-label',
				'text' => 'Username'
			),
		));
		?>
	</div>

    <div class="span4">
        <?php
        echo $this->Form->input('status', array(
            'type' => 'select',
            'label' => array(
                'class' => 'control-label',
                'text' => 'status'
            ),
            'empty' => 'All status',
            'options' => array('Wait', 'Success'),
            'selected' => !empty($this->request->params['named']['status']) ? $this->request->params['named']['status'] : ''
        )); echo "<br/>";
        ?>
    </div>

	<div class="span4">
		<div class="form-actions">
			<?php
			echo $this->Form->button('Search', array(
				'type' => 'submit',
				'class' => 'btn btn-primary',
				'div' => false
			));
			echo '  ';
			echo $this->Form->submit('Clear', array(
				'class' => 'btn',
				'name' => 'submit',
				'div' => false
			));
			?>
		</div>
	</div>
	<?php  echo $this->Form->end(); ?>
</div>

<br/>
<div class='row'>
	<div class='span12'>
		<table class="table table-striped">
			<tr>
				<th><?php echo $this->Paginator->sort('id'); ?></th>
				<th><?php echo 'Username'; ?></th>
				<th><?php echo 'Game'; ?></th>
				<th><?php echo $this->Paginator->sort('order_id'); ?></th>
				<th><?php echo $this->Paginator->sort('price'); ?></th>
				<th><?php echo $this->Paginator->sort('bonus'); ?></th>
				<th><?php echo $this->Paginator->sort('chanel'); ?></th>
				<th><?php echo $this->Paginator->sort('note'); ?></th>
				<th><?php echo $this->Paginator->sort('status'); ?></th>
				<th><?php echo $this->Paginator->sort('created'); ?></th>
				<th><?php echo $this->Paginator->sort('modified'); ?></th>
				<th class="actions">Actions</th>
			</tr>

			<?php foreach ($compense as $comp): ?>
				<?php
				$style = "";
				?>
				<tr style="<?php echo $style; ?>">
					<td><?php echo h($comp['Bonus']['id']); ?>&nbsp;</td>
					<td> <?php echo $this->Html->link($comp['User']['username'], array('controller' => 'users', 'action' => 'view', $comp['User']['id'])); ?> </td>
					<td> <?php echo $comp['Game']['title'] . ' ' . $comp['Game']['os']; ?> </td>
					<td> <?php echo $comp['Bonus']['order_id']; ?> </td>
					<td> <?php echo number_format( $comp['Bonus']['price'], 0, '.', ','); ?> </td>
					<td> <?php echo number_format( $comp['Bonus']['bonus'], 0, '.', ','); ?> </td>
					<td>Bonus</td>
					<td> <?php echo $comp['Bonus']['note']; ?> </td>
					<td> <?php
						if ( empty($comp['Bonus']['status']) ) {
							echo '<span class="label label-warning">Wait</span>';
						}else{
							echo '<span class="label label-success">OK</span>';
						}
						?>
					</td>
					<td> <?php echo $comp['Bonus']['created']; ?> </td>
					<td> <?php echo $comp['Bonus']['modified']; ?> </td>
					<td class="actions btn-group">
						<?php
						if ( empty($comp['Bonus']['status']) ) {
							echo $this->Html->link('Bonus', array('action' => 'bonus', $comp['Bonus']['id']), array('class' => 'btn btn-mini'));
							echo $this->Html->link('Edit', array('action' => 'edit', $comp['Bonus']['id']), array('class' => 'btn btn-mini'));
						}
						?>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>

		<p>
			<?php
			echo $this->Paginator->counter(array(
				'format' => 'Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}'
			));
			?>
		</p>
		<div class="paging">
			<?php
			echo $this->element('paging');
			?>
		</div>

		<div class="actions">
			<h3><?php echo 'Actions'; ?></h3>
			<ul>
				<li><?php echo $this->Html->link('New Bonus', array('action' => 'add')); ?></li>
			</ul>
		</div>
	</div>
</div>

<script type="text/javascript" >
	$(function() {
		$("#BonusGameId").chosen();

		$('#quick_datepicker').change(function() {
			if ($(this).val()) {
				var interval = $(this).val() * 24 * 3600;

				if ($('#to_time').val() != time_today) {
					$('#to_time').val(time_today).trigger('change');
				}

				if ($('#from_time').val() != time_today - interval) {
					$('#from_time').val(time_today - interval).trigger('change');
				}
			}
		});

		// Set DateAPicker Js defaults
		$('.date-range-picker .datepicker').each(function(i, e) {
			var bindElement = $(e).data('bind');
			var $input = $(e).pickadate({
				onStart: function() {
					if ($("#" + bindElement).val() != '') {
						this.set('select', $("#" + bindElement).val() * 1000);
					}
				},
				onSet: function(thingSet) {
					if (thingSet.select !== undefined) {
						$("#" + bindElement).val(thingSet.select / 1000);
					} else {
						$("#" + bindElement).val("");
					}
				}
			});
		});

	});
</script>
