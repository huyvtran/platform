<?php
$this->extend('/Common/blank');
?>
<h3 class='page-header'><?php echo $user['User']['username'] ?></h3>
<h5>Info</h5>
<ul class='unstyled'>
	<li>Id: <?php echo $user['User']['id'] ?></li>
	<li>Email: <?php echo $user['User']['email'] ?></li>
	<?php
	if (!empty($user['User']['email']) && empty($user['User']['fb_id'])) {
		if (!empty($user['User']['email_temp_verified'])) {
			echo '<li>Hippo check verified: ' . $user['User']['email_temp_verified'] . '</li>';
		}
	}
	?>
	<li>Active:
	<?php
	if ($user['User']['active'])
		echo "<span style='color:green'>Active</span>";
	else
		echo "<span style='color:red'>Deactive</span>";
	$role = $this->Session->read('Auth.User.role');
	if ($role == 'Admin') {
		echo $this->Html->link(' Deactive ', '/admin/users/deactive/' . $user['User']['id']);
	}
	?>
	</li>
	<li>FB Verified: <?php echo $user['User']['fb_verified'] ?></li>
	<li>Created: <?php 
				echo $this->Time->timeAgoInWords($user['User']['created'], array(
					'end' => '1 year',
					'accuracy' => array('day' => 'day', 'week' => 'week', 'month' => 'month')
				));
				?>
	</li>
</ul>

<h5>Accounts</h5>
<div class='row'>
<div class='span6'>
<table class='table'>
	<thead>
		<tr>
		<td>Game</td>
		<td>Account ID</td>
		<td>Created</td>
		<td>Facebook App ID</td>
		<td>Updated at</td>
		</tr>
	</thead>
	<tbody>
<?php
foreach($user['Account'] as $account) {
	$accountId = $account['id'];
	if (!empty($account['account_id'])) {
		$accountId = $account['account_id'];
	}

	echo '<tr>';
	echo '<td>';
	echo $account['Game']['title_os'];
	echo '</td>';

	echo '<td>';
	echo $accountId;
	echo '</td>';

	echo '<td>';
	echo $account['created'];
	echo '</td>';
	
	echo '<td>';
	echo $account['fb_id'];
	echo '</td>';
    echo '<td>';
	echo $account['modified'];
	echo '</td>';
	
	echo '<td>';
	echo $this->Form->postLink(' DELETE', array('controller' => 'accounts' , 'action' => 'delete', 'admin' => true, $account['id']), null, 'Are you sure want to delete the account on this game ?');
	echo '</td>';

	echo '</tr>';
}
?>
	</tbody>
</table>
</div>
</div>


<h5>RoleID And Server</h5>
<ul>
<?php
foreach ($areaRoles as $k => $v) {
	echo '<li>';
	echo 'Game: ' . $v['Game']['title'] . ' (' . $v['Game']['os'] . '), Area: ' . $v['LogEntergame']['area_id'] . ', RoleID: ' . $v['LogEntergame']['role_id'];
	echo '</li>';	
}
?>
</ul>