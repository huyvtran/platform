<?php
echo $this->extend('/Common/fluid');
?>
<style>
	.day3,.day7,.day30{
		margin-left: 48px;
	}
	.glyphicon-remove {
		color:red;

	}
	.glyphicon-ok {
		color:green;
	}
	#send_email .wrap{
		position: absolute;
		top:50%;
		left:50%;
		margin-left: -250px;
		border-radius: 5px;
		margin-top: -120px;
		padding:15px 25px;
		border:0;
		width:500px;
		height: 240px;
		background: #ffffff;
	}
</style>
<div class='row'>
	<div class="col-md-7">
		<div>
			<?php
			echo $this->Form->create('User', array('inputDefaults' => array('div' => false, 'label' => false), 'class' => 'form-inline', 'type' => "GET"));
			echo '<div class="form-group">';

			echo $this->Form->input('game_id', array(
				'empty' => '--All Games--', 'data-placeholder' => '--All Games--',
				'value' => empty($this->request->query['game_id']) ? '': $this->request->query['game_id']
			));
			$active3 = $active30 = $active7 = $active90 = '';
			if ($numberday == 3) {
				$active3 = 'active';
			} else if ($numberday == 7) {
				$active7 = 'active';
			} else if($numberday == 30) {
				$active30 = 'active';
			} elseif($numberday == 90) {
				$active90 = 'active';
			}
			echo $this->Form->submit(3 . 'Days', array('class' => "btn btn-default day3 $active3", 'div' => false, 'name' => 'day3'));
			echo $this->Form->submit(7 . 'Days', array('class' => "btn btn-default day7 $active7", 'div' => false, 'name' => 'day7'));
			echo $this->Form->submit(30 . 'Days', array('class' => "btn btn-default day30 $active30", 'div' => false, 'name' => 'day30'));
			echo $this->Form->submit(90 . 'Days', array('class' => "btn btn-default day30 $active90", 'div' => false, 'name' => 'day90'));
			echo '</div>';
			echo $this->Form->end();
			?>
		</div>
	</div>
	<div  class='col-md-5'>
		<div class="btn-group">
			<a href="<?php echo $this->Html->url(array('10', '?' => $this->request->query)) ?>" class="btn btn-default <?php echo $this->Nav->thisSlug('10') ?>"><i class='glyphicon glyphicon-transfer'></i> 10 Mills</button></a>
			<a href="<?php echo $this->Html->url(array('30', '?' => $this->request->query)) ?>" class="btn btn-default <?php echo $this->Nav->thisSlug('30') ?>">30 Mills</a>
			<a href="<?php echo $this->Html->url(array('50', '?' => $this->request->query)) ?>" class="btn btn-default <?php echo $this->Nav->thisSlug('50') ?>">50 Mills</a>
			<a href="<?php echo $this->Html->url(array('70', '?' => $this->request->query)) ?>" class="btn btn-default <?php echo $this->Nav->thisSlug('70') ?>">70 Mills</a>
			<a href="<?php echo $this->Html->url(array('100', '?' => $this->request->query)) ?>" class="btn btn-default <?php echo $this->Nav->thisSlug('100') ?>">100 Mills</a>
		</div>
	</div>
</div>
<?php
if (empty($data)) {
	goto a;
}
?>
<div class='row'>
	<h3>Users không đăng nhập vào game <?php echo $text?></h3>
	<div class='md-col-12' >
		<label>Total User : <?php echo count($data);?></label>
		<table class='table table-striped table-bordered table-data responsive'>
			<thead>
				<th class="int">UserName</th>
				<th class="int">Email Login</th>
				<th class="int">Email Contact</th>
				<th class="int">Phone</th>
				<th class="int">Time ago</th>
                <th class="int">Last Login</th>
				<th class="int">Action</th>
			</thead>
			<tbody>
			<?php
				foreach ($data as $value) {
					$startTimeStamp = strtotime($value['User']['last_action']);
					$endTimeStamp = time();
					$timeDiff = abs($endTimeStamp - $startTimeStamp);
					$numberDays = $timeDiff/86400;  // 86400 seconds in one day
					$numberDays = intval($numberDays);
					echo "<tr>";
					echo '<td><a target="_blank" href="http://admin.smobgame.com/plf/admin/users/view/' . $value['Moborder']['user_id']. '">' . $value['User']['username'] . '</a><br>';
					if (!empty($user_id) && in_array($value['Moborder']['user_id'], $user_id)) {
						echo "<b>(User đã được liên lạc)</b>";
					}
					echo '</td>';
					echo '<td>' . $value['User']['email'] . '</td>';
					echo '<td>' . $value['Profile']['email_contact'] . '</td>';
					echo '<td>' . $value['Profile']['phone'] . '</td>';
					echo '<td>' . $numberDays . ' days ago</td>';
                    echo '<td>' . $value['User']['last_action'] . '</td>';
					echo '<td>' . $this->Html->link('OK', array('controller' => 'users', 'action' => 'addUserFollow', 'user_id' => $value['Moborder']['user_id'], 'app_key' => $this->request->query['game_id']), array('target' => '_blank', 'id' => 'complete', 'onclick' => 'return confirm("Bạn đã liên hệ thành công ?")')) . '</td>';
					echo "</tr>";
				}
			?>
			</tbody>
		</table>
	</div>
</div>
<?php
a:
if (empty($data_return)) {
	goto b;
}
?>
<hr>
<div class='row'>
	<div class="col-md-12">
		<div>
			<?php
			echo $this->Form->create('User', array('inputDefaults' => array('div' => false, 'label' => false), 'class' => 'form-inline'));
			echo '<div class="form-group">';
			echo $this->Form->input('game_id1', array(
				'empty' => '--All Games--', 'data-placeholder' => '--All Games--',
				'options' => $games,
				'value' => empty($this->request->named['game_id1']) ? '': $this->request->named['game_id1']
			));
			echo '&nbsp;';
			echo $this->Form->input('status', array(
				'empty' => '--All Status--', 'data-placeholder' => '--All Status--',
				'options' => array('1' => 'Return', '0' => 'Not Return'),
				'value' => empty($this->request->query['status']) ? '': $this->request->query['status']
			));
			echo '&nbsp;';
			echo $this->Form->input('user_id', array('placeholder' => 'Username', 'class' => 'form-control', 'type' => 'text'));
			echo '&nbsp;&nbsp;';
			echo $this->Form->input('user_id_save', array('type' => 'text', 'placeholder' => 'User Contact', 'class' => 'form-control'));
			echo $this->element('date_ranger_picker');
			echo $this->Form->submit('Submit', array('class' => 'btn btn-default', 'div' => false));
			echo '</div>';
			echo $this->Form->end();
			?>
		</div>
	</div>
</div>
<div class='row'>
	<h3>Users đã được liên hệ</h3>
	<div class='md-col-12' >
		<label>Total User : <?php echo count($data_return);?></label>
		<table class='table table-striped table-bordered table-data responsive'>
			<thead>
			<th class="int">UserName</th>
			<th class="int">Game</th>
			<th class="int">Note</th>
			<th class="int">Time Contact</th>
			<th class="int">Status</th>
			<th class="int">Time User Return</th>
			<th class="int">User Contact</th>
			<th class="int">Action</th>
			</thead>
			<?php
			foreach ($data_return as $value) {
				echo "<tr>";
				echo '<td><a target="_blank" href="http://admin.smobgame.com/plf/admin/users/view/' . $value['LogUserReturn']['user_id']. '">' . $username[$value['LogUserReturn']['user_id']]  . '</td>';
				echo '<td>'. $games[$value['LogUserReturn']['app_key']]. '</td>';
				$note = ($value['LogUserReturn']['note'] != '') ? $value['LogUserReturn']['note'] : 'No have note';
				if ($note != 'No have note') {
					$note = $note . ' (' . $username[$value['LogUserReturn']['user_note']] . ')';
				}
				echo '<td>'. $note . '</td>';
				echo '<td>' . date('H:i:s d/m/Y', strtotime($value['LogUserReturn']['created'])) . '</td>';
				$status = ($value['LogUserReturn']['status'] == 0) ? 'glyphicon glyphicon-remove' : 'glyphicon glyphicon-ok';
				echo '<td style="text-align: center;"><span class="'. $status .'"></span></td>';
				$time_return = ($value['LogUserReturn']['time_login'] != '') ? date('H:i:s d/m/Y', strtotime($value['LogUserReturn']['time_login'])) : 'No have time';
				echo '<td>' . $time_return . '</td>';
				echo '<td>' . $username[$value['LogUserReturn']['user_id_save']] . '</td>';
				echo '<td><a data-id="'. $value['LogUserReturn']['id'] .'" href="javascript:void(0)" role="button" data-toggle="modal" data-target="#send_email" class="note">Note</a></td>';
				echo "</tr>";
			}
			?>
		</table>
	</div>
</div>
<div id="send_email" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="wrap">
		<h4>Quick Note :</h4>
		<?php
		echo $this->Form->create('LogUserReturn', array(
			'url' => array('controller' => 'Users', 'action' => 'addNote'),
			'id' => 'form_send',
			'onSubmit' => "if(!confirm('Bạn có chắc chắn muốn note này không ?')){return false;}",
		));
		echo $this->Form->input('id',  array('type' => 'hidden', 'label' => false, 'id' => 'id_user'));
		echo $this->Form->input('note',  array('type' => 'textarea', 'label' => false, 'cols' => 60, 'rows' => 6, 'id' => 'content_note', 'maxlength' => 150));
		echo $this->Form->submit('Note', array(
			'id' => 'submit',
			'class' => 'btn btn-primary',
			'name'  => 'submit',
			'type' => 'submit',
			'div'   => false,
		));
		echo $this->Form->end();
		?>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#complete').click(function(){
			$(this).parent().parent().remove();
		});
		$('.note').click(function(){
			var id = $(this).attr('data-id');
			$('#id_user').val(id);
		});
	})
</script>
<?php
b:
?>