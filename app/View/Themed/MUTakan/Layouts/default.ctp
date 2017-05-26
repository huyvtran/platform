<!DOCTYPE html>
<html>
	<head>
		<meta name = "viewport" content = "user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<?php
		echo $this->element('meta_data');
        echo $this->Html->css('/uncommon/all-bootrap/css/bootstrap.min.css');
		echo $this->Html->css('/uncommon/navtop-login/css/style.css');
		echo $this->fetch('css');
        echo $this->Html->script('/uncommon/all-js/jquery-1.10.2.min.js');
        echo $this->fetch('script');
		?>
	</head>
	<body>
    <?php echo $this->fetch('content') ?>

    <?php echo $this->element('footer_script'); ?>
	</body>
</html>