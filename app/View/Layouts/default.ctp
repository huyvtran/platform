<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="icon" href="/favicon.ico" />
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
<?php
if (!empty($currentGame['dashboard_gaid'])) {
?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php echo $currentGame['dashboard_gaid'] ?>', 'localhost');
  ga('send', 'pageview');

</script>
<?php
}
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