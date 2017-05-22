<div class="container body">
	<!--nocache-->
	<?php
	echo $this->Session->flash();
	echo $this->Session->flash('auth', array('element' => 'info'));
	?>
	<!--/nocache-->
	<div class="content"> <?php echo $this->fetch('content');?></div>
</div>