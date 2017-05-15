<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php

	echo $this->element('meta_for_app_new_dashboard');
	if(!isset($title_for_app)) $title_for_app = __(' ');
	echo "<meta name = 'title' content = '$title_for_app' />";
	echo $this->Html->css('/uncommon/payment_sdk/css/style_copy.css');
	echo $this->Html->css('/css/c/problems/magnific-popup.css');
	echo $this->fetch('css');
	$cssInline = $this->fetch('css-inline');
	if (!empty($cssInline)){
		echo "<style type = 'text/css'>$cssInline</style>";
	}
	if (env("SERVER_ADDR") == '127.0.0.1') {
		echo $this->Html->css('debug');
	}

	echo $this->element('js_libs_and_fallback');
	echo $this->Html->script('/uncommon/dashboard_v2/js/jquery.min.js');
	//        echo $this->Html->script('script.js');
	echo $this->element('call_app_func');
	echo $this->fetch('script');

	?>
	<?php
	if (!empty($currentGame['dashboard_gaid'])) {
		?>
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
					(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			ga('create', '<?php echo $currentGame['dashboard_gaid'] ?>','auto', 'smobgame');
			ga('create', 'UA-82141936-1','auto', 'dballgame');
			<?php if(isset($currentGame['language_default']) && $currentGame['language_default'] == 'vie'){ ?>
			ga('create', 'UA-82141936-2','auto', 'dbfuntap');
			ga('dbfuntap.send', 'pageview');
			<?php }else{ ?>
			ga('create', 'UA-82141936-3','auto', 'dbmobgame');
			ga('dbmobgame.send', 'pageview');
			<?php } ?>
			ga('smobgame.send', 'pageview');
			ga('dballgame.send', 'pageview');

		</script>
		<?php
	}
	?>
</head>
<body>
<?php
echo $this->fetch('content');

if (Configure::read('debug') == 2){
	echo $this->element('sql_dump');
}
?>
</body>
</html>