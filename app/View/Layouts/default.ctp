<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="icon" href="<?php echo Router::url('/') .'favicon.ico'; ?>" />
    <?php
	echo $this->element('meta_for_app');

	echo $this->Html->css('/uncommon/signup/css/style.css');
	
	echo $this->fetch('css');
	$cssInline = $this->fetch('css-inline');
	if (!empty($cssInline)){
		echo "<style type = 'text/css'>$cssInline</style>"; 
	}
	if (env("SERVER_ADDR") == '127.0.0.1') {
		echo $this->Html->css('debug');
	}

	echo $this->element('js_libs_and_fallback');
	echo $this->Html->script('script.js');
	echo $this->fetch('script');
	echo $this->element('call_app_func');
?>
</head>
<body>

<?php 
echo $this->Session->flash('email');
echo $this->fetch('content');
?>
<script type="text/javascript">
  	$(function(){
  		<?php echo implode("\n", $this->Js->getBuffer()); ?>
	});
</script>
<?php
if (Configure::read('debug') == 2){
	echo $this->element('sql_dump');
}
?>

</body>
</html>