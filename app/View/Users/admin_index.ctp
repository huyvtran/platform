<?php
$this->extend('/Common/blank');
?>
<div class="users index">
	<h2>Users</h2>
	<hr/>
	<h3>Filter</h3>
	<div class='row'>
		<div class='span3'>
		<?php
		echo $this->Form->create('User', array('action' => 'index', 'class' => 'simple', 'style' => 'width:200px;'));
		echo $this->Form->input('id', array('type' => 'text','required' => false));
		echo $this->Form->input('username', array('required' => false));
		echo $this->Form->input('email', array('required' => false, 'type' => 'text'));
		echo $this->Form->input('phone', array('required' => false, 'type' => 'text'));
        echo "<br/>";
		echo '<label>Staff only' . $this->Form->input('staff', array('type' => 'checkbox', 'label' => false)) . '</label>';
		echo $this->Form->submit('Search', array('class' => 'btn'));
		?>
		</div>
		<div class='span3'>
		<?php
		echo $this->Form->input('account_id', array('required' => false, 'type' => 'text', 'label' => 'Account ID'));
		echo $this->Form->input('facebook_uid', array('required' => false));	
		echo $this->Form->end();		
		?>
		</div>
		<div class='span3'>
			<em>(1) fields was required together if you use one of these fields while search </em>
			<p><span style="color:red">Note : </span>Fileds username and email can use "nickname%" to search user have email "nickname123@anything.com"</p>
		</div>		
	</div>
	<hr/>
	<?php echo $this->element('paging'); ?>
	<table class = "table">
		<tr>
			<th><small><?php echo $this->Paginator->sort('id'); ?></small></th>
			<th><small><?php echo $this->Paginator->sort('username'); ?></small></th>
			<th><small>Updated From</small></th>
			<th><small><?php echo $this->Paginator->sort('email'); ?></small></th>
			<th><small><?php echo $this->Paginator->sort('created'); ?></small></th>
			<th><small><?php echo $this->Paginator->sort('last_action'); ?></small></th>
			<th><small><?php echo $this->Paginator->sort('email_verified', 'Email Verified'); ?></small></th>
			<th><small><?php echo $this->Paginator->sort('active', 'Active'); ?></small></th>
			<th><small><?php echo $this->Paginator->sort('facebook_uid', 'Facebook ID'); ?></small></th>
			<th><small><?php echo $this->Paginator->sort('device_id', 'Device ID'); ?></small></th>
			<th><small><?php echo $this->Paginator->sort('role', 'Role'); ?></small></th>
			<th><small><?php echo $this->Paginator->sort('phone', 'Phone'); ?></small></th>
            <th><small>Descriptions</small></th>
			<th><small>Country</small></th>
			<th class="actions"><small>Actions</small></th>
		</tr>
		<?php
		foreach ($users as $user) {
		?>
		<tr>
			<td>
				<?php echo $user['User']['id']; ?>
			</td>
			<td>
				<?php echo h($user['User']['username']); ?>
			</td>
			<td>
				<?php
				if (!empty($user['LogUpdatedAccount']['guest'])) {
					echo $user['LogUpdatedAccount']['guest'];
				}
				?>
			</td>
			<td>
				<?php echo h($user['User']['email']); ?>
			</td>
			<td>
				<?php echo $this->Time->timeAgoInWords($user['User']['created']); ?>
			</td>
			<td>
				<?php echo $this->Time->timeAgoInWords($user['User']['last_action']); ?>
			</td>
			<td>
				<?php
				if ($user['User']['email_verified'])
					echo "<span style='color:green'>Verified</span>";
				else
					echo "<span style='color:red'>No</span>";
				?>
			<td>
				<?php
				if ($user['User']['active'])
					echo "<span style='color:green'>Active</span>";
				else
					echo "<span style='color:red'>Deactive</span>";
				?>
			</td>

			<td>
				<?php
				if ($user['User']['facebook_uid'])
					echo "<span style='color:green'>" . $user['User']['facebook_uid'] . "</span>";
				else
					echo "<span style='color:red'>No</span>";
				?>
			</td>

			<td>
				<?php
				if ($user['User']['device_id'])
					echo "<span style='color:green' title='" . $user['User']['device_id'] . "'>Yes</span>";
				else
					echo "<span style='color:red'>No</span>";
				?>
			</td>

			<td><?php echo $user['User']['role'] ?></td>
			<td><?php echo $user['User']['phone'] ?></td>

			<td><?php echo $user['User']['description'] ?></td>

			<td><?php echo $user['User']['country_code'] ?></td>

			<td class="actions btn-group">
				<?php echo $this->Html->link('View', '/admin/users/view/' . $user['User']['id'], array('class' => 'btn btn-mini')); ?>
				<?php  ?>
				<div class="btn-group">
					<a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#">
						Others <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><?php echo $this->Html->link('Add Permissions', '/admin/permissions/add?user_id=' . $user['User']['id']); ?></li>
						<li><?php echo $this->Html->link('Edit', '/admin/users/edit/' . $user['User']['id']); ?></li>

                        <?php
						if (!empty($user['User']['active'])) {
						?>
						<li><?php echo $this->Html->link('Deactive', '/admin/users/deactive/' . $user['User']['id']); ?></li>
						<?php
						} else {
						?>
						<li><?php echo $this->Html->link('Active', '/admin/users/deactive/' . $user['User']['id'] . '/1'); ?></li>
						<?php
						}
						?>

						<?php $role = $this->Session->read('Auth.User.role'); ?>
						<?php if (in_array($role, array('Admin','Developer'))) { ?>
							<li><?php echo $this->Html->link('Delete', array('action' => 'delete', $user['User']['id']), array(), sprintf('Are you sure you want to delete # %s?', $user['User']['id'])); ?></li>
						<?php } ?>


						</li>
					</ul>
				</div>				
			</td>
		</tr>

		<?php
		if (!empty($user['Game'])) {
			echo '<tr>';
			echo '<td colspan="10"><b>Played: ';
			foreach($user['Game'] as $game) {
				echo $game['title'] . ' ' . $game['os'] . ' - ';
			}
			echo '</b></td>';
			echo '</tr>';
		}
		?>
	<?php
	}
	?>
	</table>
	<?php
	echo $this->Paginator->counter(array(
		'format' => 'Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}'
	));
	?>
	<?php echo $this->element('paging'); ?>
</div>