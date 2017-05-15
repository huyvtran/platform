<?php

$message = '';
$code = $dataForView['error']->getCode();
$description = 'Error';
if ($name == 'Not Found'){
	$message = 'Không tìm thấy';
	$description = 'Trang web bạn cần tìm không có hoặc bạn gõ sai địa chỉ. ';
}elseif($name == 'Bad Request'){
	$message = 'Không được chấp nhận';
	$description = 'Lỗi xảy ra vì request gửi tới không đúng. ';
}
if (empty($message)) {
	$message = $name;
} else {
	$message = "Lỗi xảy ra";
}

if (Configure::read('debug') > 0) {
	?>
	<div style="text-align:center;padding:50px 20px;">
		<h1><b><?php
				echo "<big style='font-size:40px;'>$code</big><br/> $message"
		?></b></h1>
		<br/>
	<!--     <p>
			<?php #echo $description;?>Thử <b><?php #echo $this->Html->link('quay trở lại trang web vừa rồi.', $url);?></b>
		</p> -->
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
