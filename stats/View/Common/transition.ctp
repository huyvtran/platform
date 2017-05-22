<?php
# Support Jquery Mobile transtions
?>
<div data-role="page">
	<div data-role="content" class="mg-content">
		<div class="content-inner">
		<!--nocache-->
		<?php 
		echo $this->Session->flash();
		echo $this->Session->flash('auth', array('element' => 'info'));
		?>
		<!--/nocache-->	
		<?php echo $this->fetch('content');?>		
		</div>
	</div>
</div>