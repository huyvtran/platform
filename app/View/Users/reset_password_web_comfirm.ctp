<!-- Layout Default -->
<?php
$this->extend('/Common/default');
?>
<div class="row">
	<div class = 'span6 offset2'>
	<h4>Reset Password</h4>
<?php
echo $this->Form->create('User', array(
	'inputDefaults' => array('div' => false, 'label' => false, 'errorMessage' => false)
	));

?>
	<div class="unit modal-form">
		<?php
	    echo $this->Session->flash('flash');
	    echo $this->Session->flash('auth', array('element' => 'info'));		
		?>
		<p class="caption"></p>
		<div class="control-group">
			<label for="pincode">Please enter PIN code that sent to your email.</label>
			<?php
			echo $this->Form->input('token', array(
				'required' => 'required', 'placeholder' => 'PIN code',
				'autocapitalize' => 'off', 'maxlength' => 32, 'type' => 'text',
				'class' => 'reset full'
			));
			?>
		</div>
		<div class="control-group">
			<label for="login">Enter new password</label>
			<?php
			echo $this->Form->input('new_password', array(
				'type' => 'password', 'class' => 'reset full', 'required' => 'required',
				'placeholder' => 'Password length is 6-20 characters', 'autocomplete' => 'off'));
			?>
			<label for="password">Confirm password</label>
			<?php
			echo $this->Form->input('confirm_password', array(
				'type' => 'password', 'class' => 'reset full', 'required' => 'required', 'placeholder' => 'Comfirm password', 'autocomplete' => 'off'));
			?>				
		</div>
	</div>

	<?php
	echo $this->Form->submit('submit', array('class' => 'btn btn-primary', 'div' => false));
	?>
	<a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login')) ?>" class="btn">Cancel</a>
	<?php
	echo $this->Form->end();
	?>

</div>
</div>
<script type='text/javascript'>
	$(".icon-clear").click(function() {
		$(this).siblings('input').val('').focus();
	})
</script>