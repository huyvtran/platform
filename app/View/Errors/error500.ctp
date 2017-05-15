<?php
$message = 'Internal Server Error';
$code = '500';
$description = 'An Internal Error Has Occurred.';
if ($name == 'Internal Server Error'){
	$message = 'Server không thể xử lí hoặc lỗi server';
	$code = 500;
	$description = 'Thường lỗi này xảy ra do server, báo lỗi với ban quản trị trang web để giải quyết sớm vấn đề này. ';
}elseif (isset($name)){
	$message = $name;
}else{
	$message = "Lỗi xảy ra";
}
if (Configure::read('debug') > 0) {
?>
	<div style="text-align:center;padding:50px 20px;">
		<h1><b><?php
				echo "<big style='font-size:40px;'>$code</big><br/> $message"
		?></b></h1>
		<br/>
		<p>
			<?php echo $description;?><b>
				<?php 
		//      	if (isset($url)){
				// 	echo 'Thử ' . $this->Html->link('quay trở lại trang web vừa rồi.', $url);
				// }
				?></b>
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