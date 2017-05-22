<?php
echo $this->extend('/Common/fluid');
?>
<style>
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
	<div class="col-md-12">
		<div>
			<?php
			echo $this->Form->create('User', array('inputDefaults' => array('div' => false, 'label' => false), 'class' => 'form-inline'));
			echo '<div class="form-group">';
			echo $this->Form->input('game_id', array(
				'empty' => '--All Games--', 'data-placeholder' => '--All Games--',
				'value' => empty($this->request->query['game_id']) ? '': $this->request->query['game_id']
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
<?php
if (empty($data)) {
	goto a;
}
?>
<div class='row'>
	<h3>Users đã được liên hệ</h3>
	<div class='md-col-12' >
		<label>Total User : <?php echo count($data);?></label>
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
			foreach ($data as $value) {
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
		$('.note').click(function(){
			var id = $(this).attr('data-id');
			$('#id_user').val(id);
		});
	});
</script>
<?php
a: