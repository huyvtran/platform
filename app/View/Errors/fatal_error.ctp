<?php
if (Configure::read('debug') > 0) {
?>
	<div style="text-align:center;padding:50px 20px;">
		<h1><b><?php
				echo "<big style='font-size:40px;'>$code</big><br/> {$error->getMessage()}}"
		?></b></h1>
		</p>
		<hr/>
	</div>
	<?php
	if (Configure::read('debug') > 0) :
		echo $this->element('exception_stack_trace');
	endif;

} else {

?>

	<body>
	<section id="wrapper">
		<article class="content">
			<div id="error">
				<p>
					<?php echo $this->Html->image('/uncommon/dashboard/images/blank.png', array('class' => 'i-error')); ?>
				</p>
				<?php
				if (empty($this->request->params['admin'])) {
					echo '<h2 class="err-plf-v2">' . __('Có lỗi trong quá trình xử lý!') ?><br /><?php echo __('Xin vui lòng thử lại sau.') ?></h2>
				<?php 
				} else {
					echo '<h2 class="err-plf-v2">' . $message . '</h2>';
				}
				?>
			</div>
		</article>
	</section>
	</body>
<?php
}
?>