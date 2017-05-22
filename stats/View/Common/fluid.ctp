<div class="container">
	<div class="content">
		<!--nocache-->
		<?php
		echo $this->Session->flash();
		echo $this->Session->flash('auth', array('element' => 'info'));
		?>              
		<!--/nocache-->
		<?php echo $this->fetch('content');?>
	</div>
</div>
