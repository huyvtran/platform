<?php
$this->extend('/Common/blank');
App::import('Lib', 'RedisQueue');
?>

<div class="row">
	<div class='span12'>
		<h2><?php echo 'Email Marketings'; ?></h2>
		<div class='row'>
		<div class='span9'>
		<?php
			echo $this->Form->create('EmailMarketing', array('class' => 'form-inline'));
			echo $this->Form->input('game_id', array(
				'label' => false, 
				'empty' => '-- All Games --',
				'options' => $distinctGames,
				'value' => empty($this->request->params['named']['game_id']) ? '': $this->request->params['named']['game_id'],
	    		'div' => false
			));

			echo ' ';echo "<br/><br/>";
			echo $this->Form->submit('Search', array('class' => 'btn btn-small',
	    		'div' => false));
			echo $this->Form->end();
		?>
		</div>
		<dv class='span3'>
		<?php echo $this->Html->link('New Email Marketing', array('action' => 'add'), array('class' => 'btn btn-small')); ?>
		</dv>
		</div>
		<div class="paging">
		<?php
			echo $this->element('paging');
		?>
		</div>
		
		<table class="table table-striped">
		<tr>
				<th><?php echo $this->Paginator->sort('id'); ?></th>
				<th><?php echo $this->Paginator->sort('title'); ?></th>
				<th><?php echo $this->Paginator->sort('published_date'); ?></th>
				<th><?php echo $this->Paginator->sort('user_id'); ?></th>
				<th><?php echo $this->Paginator->sort('game_id'); ?></th>
				<th><?php echo 'Current'; ?></th>
				<th>Click/Open/Total</th>
				<th><?php echo $this->Paginator->sort('status'); ?></th>
				<th class="actions"><?php echo 'Actions'; ?></th>
		</tr>
		<?php
		foreach ($emailMarketings as $emailMarketing):
		$Redis = new RedisQueue('default', 'email-marketing-id-' . $emailMarketing['EmailMarketing']['id']);			
		?>
		<tr>
			<td><?php echo h($emailMarketing['EmailMarketing']['id']); ?>&nbsp;</td>
			<td><?php echo h($emailMarketing['EmailMarketing']['title']); ?>&nbsp;</td>
			<td><?php
			if ($emailMarketing['EmailMarketing']['published_date'] != '0000-00-00 00:00:00') {
			echo $emailMarketing['EmailMarketing']['published_date'];
			}
			?>&nbsp;</td>
			<td>
				<?php echo $this->Html->link($emailMarketing['User']['username'], array('controller' => 'users', 'action' => 'view', $emailMarketing['User']['id'])); ?>
			</td>
			<td>
				<?php echo $this->Html->link($emailMarketing['Game']['title'], array('controller' => 'games', 'action' => 'view', $emailMarketing['Game']['id'])); ?>
			</td>
			<td>
			<?php echo $Redis->lSize(); ?>
			</td>			
			<td title="Total addresses submited: <?php 
				if (!empty($emailMarketing['EmailMarketing']['data']['addresses'])) {
					echo count(explode("\n", $emailMarketing['EmailMarketing']['data']['addresses']));
				}
				?>">
			<?php echo $emailMarketing['EmailMarketing']['click'] ? $emailMarketing['EmailMarketing']['click'] : 0; ?>
			 / <?php echo $emailMarketing['EmailMarketing']['view'] ? $emailMarketing['EmailMarketing']['view'] : 0; ?>
			 / <?php echo $emailMarketing['EmailMarketing']['total']; ?>
			</td>
			<td>
				<?php 
				switch ($emailMarketing['EmailMarketing']['status'] ) {
					case EmailMarketing::SEND_COMPLETED:
						echo '<span style="color:green">Completed</span>';
						break;
					case EmailMarketing::SEND_QUEUED:
						echo 'Sending';
						break;
					case EmailMarketing::SEND_QUEUEING:
						echo 'Queueing';
						break;
					case EmailMarketing::SEND_PUSHLISHED:
						echo 'Wait for queue';
						break;
					case EmailMarketing::SEND_WAIT:
						echo 'Wait for publish';
						break;
				}
				?>
			</td>			
			<td class="btn-group">
				<?php
				if ($emailMarketing['EmailMarketing']['status'] != EmailMarketing::SEND_WAIT) {
					echo $this->Html->link('ReSend', array('controller' => 'EmailMarketings', 'action' => 'publish', $emailMarketing['EmailMarketing']['id'], 'admin' => true), array('class' => 'btn btn-mini btn-danger', 'style' => 'width: 49px'));
				} else if ($emailMarketing['EmailMarketing']['status'] == EmailMarketing::SEND_WAIT) {
					echo $this->Html->link('Send', array('controller' => 'EmailMarketings', 'action' => 'publish', $emailMarketing['EmailMarketing']['id'], 'admin' => true), array('class' => 'btn btn-mini btn-danger', 'style' => 'width: 49px'), sprintf('Are you sure you want to send # %s?', $emailMarketing['EmailMarketing']['id']));
				}
				?>
				<?php echo $this->Html->link('SendTest', array('action' => 'sendTest', $emailMarketing['EmailMarketing']['id']), array('class' => 'btn btn-mini')); ?>
				<?php echo $this->Html->link('Settings', array('action' => 'add', $emailMarketing['EmailMarketing']['id']), array('class' => 'btn btn-mini')); ?>
				<?php echo $this->Html->link('Edit Content', array('action' => 'edit', $emailMarketing['EmailMarketing']['id']), array('class' => 'btn btn-mini')); ?>

				<?php
				if ($emailMarketing['EmailMarketing']['status'] == EmailMarketing::SEND_WAIT || in_array($this->Session->read('Auth.User.username'), array('quanvh'))
				) {
					echo $this->Form->postLink('Delete', array('action' => 'delete', $emailMarketing['EmailMarketing']['id']), array('class' => 'btn btn-mini'), sprintf('Are you sure you want to delete # %s?', $emailMarketing['EmailMarketing']['id']));
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
		?>	</p>
		<div class="paging">
		<?php
			echo $this->element('paging');
		?>
		</div>
	</div>
</div>
