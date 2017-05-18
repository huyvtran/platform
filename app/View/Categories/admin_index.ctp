<?php $this->extend('/Common/blank'); ?>
<h2>Categories</h2>

<p>
	<?php
	echo $this->Paginator->counter(array('format' => 'Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%'));
?>
</p>
<table cellpadding="0" cellspacing="0" class="table">
	<thead>
		<tr>
			<th><?php echo $this->Paginator->sort('title');?></th>
			<th><?php echo $this->Paginator->sort('type');?></th>
			<th><?php echo $this->Paginator->sort('description');?></th>
			<th><?php echo $this->Paginator->sort('article_count');?></th>
			<th><?php echo $this->Paginator->sort('slug');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th class="actions">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
foreach ($categories as $category):
		?>
		<tr>
			<td>
				<?php
				echo '<span style="color:red">' . str_repeat('|----', $category['Category']['level']) . '</span> ' . $category['Category']['title'];
				?>
			</td>
			<td>
				<?php
				echo $category['Category']['type'];
				?>
			</td>
			<td><?php echo $this->Text->truncate(h($category['Category']['description']), 70);?></td>
			<td><?php echo $category['Category']['article_count']; ?></td>
			<td><?php echo $category['Category']['slug']; ?></td>
			<td><?php echo $category[$modelClass]['created'];?></td>
			<td class="actions btn-group">
				<?php 
				echo $this->Html->link('Edit', array(
					'action' => 'edit',
					$category[$modelClass]['id']
				), array('class' => 'btn'));
                if(in_array($this->Session->read('Auth.User.role'),array('Admin','Developer'))) {
                    echo $this->Html->link('Delete', array(
                        'action' => 'delete',
                        $category[$modelClass]['id']
                    ), array('class' => 'btn'), sprintf('Are you sure you want to delete # %s?', $category[$modelClass]['id']));
                }
				?>
			</td>
		</tr>
<?php endforeach;?>
	</tbody>
</table>
<?php echo $this->element('paging');?>
<?php
echo $this->Html->link('Add category', array('action' => 'add'))
?>