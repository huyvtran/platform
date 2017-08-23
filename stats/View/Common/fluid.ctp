<section class="content container-fluid">
		<!--nocache-->
		<?php
		echo $this->Session->flash();
		echo $this->Session->flash('auth', array('element' => 'info'));
		?>              
		<!--/nocache-->
		<?php echo $this->fetch('content');?>
</section>