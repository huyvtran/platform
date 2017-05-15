<?php
if ($this->layout == 'default_bootstrap') {
?>
	<div class="alert alert-success">
		<a class="close" data-dismiss="alert">×</a>
		<?php echo $message;?>
	</div>
<?php	
} else {
?>
	<div class="message msg-sucess"><?php echo $message;?></div>
<?php
}
?>