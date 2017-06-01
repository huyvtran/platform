<!DOCTYPE html>
<html>
	<head>
        <link rel="icon" href="/favicon.ico" />

		<?php
		echo $this->element('meta_for_app');
		echo $this->Html->css('/css/style.css');
		echo $this->Html->css('/js/chosen_v0.14.0/chosen.min.css');
		echo $this->Html->css('/js/pickadate.js-3.1.3/lib/themes/classic.css');
		echo $this->Html->css('/js/pickadate.js-3.1.3/lib/themes/classic.date.css');
		echo $this->Html->css('/js/pickadate.js-3.1.3/lib/themes/classic.time.css');
		echo $this->Html->css('/js/nprogress/nprogress.css');
		echo $this->Html->css('/css/font-awesome-4.7.0/css/font-awesome.min.css');
		echo $this->fetch('css');
		$cssInline = $this->fetch('css-inline');
		if (!empty($cssInline)) {
			echo "<style type = 'text/css'>$cssInline</style>";
		}
		if (env("SERVER_ADDR") == '127.0.0.1') {
			echo $this->Html->css('debug');
		}

		echo $this->Html->script('jquery-2.0.0.min.js');
		echo $this->Html->script('stupidtable.js');
		echo $this->Html->script('chosen_v0.14.0/chosen.jquery.min.js');
		echo $this->Html->script('admin.js');
		?>
		<script type='text/javascript'>
			var BASE_URL = '<?php echo Router::url("/", true); ?>'
		</script>		
		<?php
		echo $this->Html->script('bootstrap.min.js');
		echo $this->Html->script('admin_script.js');
		echo $this->Html->script('pickadate.js-3.1.3/lib/picker.js');
		echo $this->Html->script('pickadate.js-3.1.3/lib/picker.date.js');
		echo $this->Html->script('pickadate.js-3.1.3/lib/picker.time.js');
		echo $this->Html->script('pickadate.js-3.1.3/lib/legacy.js');
		echo $this->Html->script('/js/nprogress/nprogress.js');
		echo $this->fetch('script');
		?>
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
		<style type="text/css">body{font-family: 'Open Sans', sans-serif; color:#222;}</style>
	</head>
	<body>
<?php
echo $this->element('nav');
echo $this->fetch('content');
?>

<script type="text/javascript">
	$(function() {
		<?php echo implode("\n", $this->Js->getBuffer()); ?>
	});
</script>
<div class="progress progress-striped active" id='generic-progress'>
  <div class="bar" style="width: 40%;"></div>
</div>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-78171029-1', 'auto');
  ga('send', 'pageview');

</script>
<?php
if (Configure::read('debug') == 2) {
	echo $this->element('sql_dump');
}
?>
	</body>
</html>