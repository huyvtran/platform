<?php
$this->extend('/Common/blank');
?>
<div class="games index">
	<h2>Websites</h2>
	<table class="table table-striped">
	<tr>
		<th><?php echo $this->Paginator->sort('Id'); ?></th>
		<th><?php echo $this->Paginator->sort('title'); ?></th>
		<th><?php echo $this->Paginator->sort('Game.title', 'Game Title'); ?></th>
		<th><?php echo $this->Paginator->sort('url'); ?></th>
		<th><?php echo $this->Paginator->sort('theme'); ?></th>
		<th><?php echo $this->Paginator->sort('theme_mobile'); ?></th>
		<th><?php echo $this->Paginator->sort('lang'); ?></th>
		<th class="actions">Actions</th>
	</tr>

	<?php foreach ($websites as $website): ?>
	<tr>
		<td><?php echo h($website['Website']['id']); ?>&nbsp;</td>
		<td><?php echo h($website['Website']['title']); ?>&nbsp;</td>
		<td>
			<?php
			if (!empty($website['Game'])) {
				foreach($website['Game'] as $game) {
					echo $game['title'];
					echo '(' . $game['os'] . ')';
				}
			}
			?>
			&nbsp;</td>
		<td><?php echo h($website['Website']['url']); ?>&nbsp;</td>
		<td><?php echo h($website['Website']['theme']); ?>&nbsp;</td>
		<td><?php echo h($website['Website']['theme_mobile']); ?>&nbsp;</td>
		<td><?php echo $website['Website']['lang']; ?>&nbsp;</td>
		
		<td class="actions btn-group">
			<?php echo $this->Html->link('Xem', array('action' => 'view', $website['Website']['id'], 'admin' => false), array('class' => 'btn btn-mini')); ?>
			<?php echo $this->Html->link('Sửa', array('action' => 'edit', $website['Website']['id']), array('class' => 'btn btn-mini')); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => 'Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}'
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< previous', array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next('next >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3>Actions</h3>
	<ul>
		<li><?php echo $this->Html->link('New Website', array('action' => 'add')); ?></li>
	</ul>
</div>
