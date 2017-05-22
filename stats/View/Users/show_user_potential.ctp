<?php
echo $this->extend('/Common/fluid');
$filter_money = array(
	'10' => '> 10 millions',
	'15' => '> 15 millions',
	'20' => '> 20 millions',
);
?>
	<style>
		.glyphicon-remove {
			color:red;

		}
	</style>
	<div class='row'>
		<div class="col-md-12">
			<div>
				<?php
				echo $this->Form->create('User', array('inputDefaults' => array('div' => false, 'label' => false), 'class' => 'form-inline'));
				echo '<div class="form-group">';
				echo $this->Form->input('game_id', array(
					'empty' => '--All Games--', 'data-placeholder' => '--All Games--',
					'value' => empty($this->request->named['game_id']) ? '': $this->request->named['game_id']
				));
				echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				echo $this->Form->input('money', array(
					'empty' => '--All Money--', 'data-placeholder' => '--All Money--',
					'options' => $filter_money,
					'value' => empty($this->request->named['money']) ? '': $this->request->named['money']
				));
				echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				echo $this->Form->input('username', array(
					'placeholder' => 'Username',
					'value' => empty($this->request->named['username']) ? '': $this->request->named['username']
				));
				echo $this->element('date_ranger_picker');
				echo $this->Form->submit('Submit', array('class' => 'btn btn-default', 'div' => false));
				echo '</div>';
				echo $this->Form->end();
				?>
			</div>
		</div>
	</div>
<?php
if (empty($data)) {
	goto a;
}
?>
	<div class='row'>
		<h3>Users nạp tiền trên <?php echo (isset($this->request->named['money']) && $this->request->named['money'] != '') ? $this->request->named['money'] : '5'?> triệu</h3>
		<div class='md-col-12' >
			<label>Total User : <?php echo count($data);?></label>
			<table class='table table-striped table-bordered table-data responsive'>
				<thead>
					<th class="int">UserName</th>
					<th class="int">VIP</th>
					<th class="int">Game</th>
					<th class="int">Money</th>
					<th class="int">Last Payment</th>
					<?php if (in_array($this->Session->read('Auth.User.role'), array('SupportAdmin', 'Support', 'Admin', 'Developer'))) {?>
					<th style="text-align: center;" class="int">Phone</th>
					<th style="text-align: center;" class="int">Email Contact</th>
					<?php }?>
				</thead>
				<?php
				foreach ($data as $value) {
					if ($value['Profile']['phone'] != '') {
						$phone = $value['Profile']['phone'];
					} else {
						$phone = '<span class="glyphicon glyphicon-remove"></span>';
					}
					if ($value['Profile']['email_contact'] != '') {
						$email = $value['Profile']['email_contact'];
					} else {
						$email = '<span class="glyphicon glyphicon-remove"></span>';
					}
					$from = (isset($this->request->named['fromTime']) && $this->request->named['fromTime'] != '') ? $this->request->named['fromTime'] : $fromTime;
					$to   = (isset($this->request->named['toTime']) && $this->request->named['toTime'] != '') ? $this->request->named['toTime'] : $toTime;
					echo "<tr>";
					echo '<td><a target="_blank" href="http://admin.smobgame.com/plf/admin/users/view/' . $value['Moborder']['user_id']. '">' . $value['User']['username']  . '</td>';
					echo '<td>'. $value['User']['vip'] . '</td>';
					echo '<td>'. $games[$value['Moborder']['app_key']]. '</td>';
					echo '<td><a target="_blank" href="http://admin.smobgame.com/plf/admin/mob_orders/index/user_id:'. $value['Moborder']['user_id'] .'/from_time:' . $from . '/to_time:'. $to .'">'. n($value[0]['Sum']/100) . '</a> $</td>';
					echo '<td>' . date('H:i:s d/m/Y', $value[0]['time']) . '</td>';
					if (in_array($this->Session->read('Auth.User.role'), array('SupportAdmin', 'Support', 'Admin', 'Developer'))) {
						echo '<td style="text-align: center;">' . $phone . '</td>';
						echo '<td style="text-align: center;">' . $email . '</td>';
					}
					echo "</tr>";
				}
				?>
			</table>
		</div>
	</div>
<?php
a: