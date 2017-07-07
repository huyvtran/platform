<?php
$this->extend('/Common/blank');
?>
<div class='row'>
	<div class='span5'>
		<h2 class = 'page-header'>Products
		<small><em>Gói xu</em></small></h2>
	</div>
	<div class='span7'>
		<?php
		echo $this->Form->create('Product', array('class' => 'form-inline'));
		echo $this->Form->input('game_id', array(
			'label' => false, 
			'empty' => '-- All Games --',
			'value' => empty($this->request->params['named']['game_id']) ? false : $this->request->params['named']['game_id'],
    		'div' => false
		));
		echo ' ';
		echo $this->Form->submit('Search', array('class' => 'btn btn-small',
    		'div' => false));
		echo $this->Form->end();
		?>
	</div>
</div>

<div class="games index">
	<table class="table table-striped">
	<tr>
		<th><?php echo $this->Paginator->sort('id'); ?></th>
		<th><?php echo $this->Paginator->sort('title'); ?></th>
		<th><?php echo $this->Paginator->sort('Game.title', 'Game Title'); ?></th>
		<th><?php echo $this->Paginator->sort('price'); ?></th>
		<th><?php echo $this->Paginator->sort('platform_price'); ?></th>
        <th><?php echo $this->Paginator->sort('type'); ?></th>
		<th><?php echo $this->Paginator->sort('description'); ?></th>
        <th><?php echo $this->Paginator->sort('created'); ?></th>
        <th><?php echo $this->Paginator->sort('modified'); ?></th>
		<th class="actions">Actions</th>
	</tr>
	<?php foreach ($products as $product): ?>
	<tr>
		<td><?php echo $product['Product']['id']; ?></td>
		<td><?php echo h($product['Product']['title']); ?>&nbsp;</td>
		<td><?php echo h($product['Game']['title_os']); ?>&nbsp;</td>
		<td><?php echo $product['Product']['price']; ?>&nbsp;</td>
		<td><?php echo h($product['Product']['platform_price']); ?></td>
        <td><?php echo h($product['Product']['type']); ?></td>
		<td><?php echo h($product['Product']['description']); ?>&nbsp;</td>
        <td><?php echo h($product['Product']['created']); ?>&nbsp;</td>
        <td><?php echo h($product['Product']['modified']); ?>&nbsp;</td>
		<td class="actions btn-group">
			<?php echo $this->Html->link('edit', array('action' => 'edit', $product['Product']['id']), array('class' => 'btn btn-mini')); ?>
			<?php echo $this->Form->postLink('delete', array('action' => 'delete', $product['Product']['id']), array('class' => 'btn btn-mini'), sprintf('Are you sure you want to delete # %s?', $product['Product']['id'])); ?>
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
		<li><?php echo $this->Html->link('New Product', array('action' => 'add')); ?></li>
	</ul>
</div>
