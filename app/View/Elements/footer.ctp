<?php
if (isset($layout) && $layout == 'fluid') {
	$class = 'container-fluid';
} else {
	$class = 'container';
}
?>
<footer class="<?php echo $class ?> footer">
	<div class="content">
		<div class = 'row'>
			<div class="span5">
			 <div><?php echo $this->Html->link('Trang chủ', 'pages/', array('class' => 'muted'));?></div>
			 <div><?php echo date("Y-m-d H:i:s") ?></div>
			 <div><?php echo 'Proccess Time: ' . round(microtime(true) - TIME_START, 2) * 1000 . ' ms' ?></div>
			<?php
			if ($this->Session->read('Auth.User')) {
			?>
			<div><?php echo 'Your user ID: ' . $this->Session->read('Auth.User.id'); ?></div>
			<?php
			}
			?>
			</div>
			<div class="span7">
				<div id='admin-tool-log' style='color:#666; height: 100px;overflow: auto'></div>
			</div>
		</div>
	</div>
</footer>