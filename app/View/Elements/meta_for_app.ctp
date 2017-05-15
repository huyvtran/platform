<meta charset="utf-8">
<meta name = "viewport" content = "user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="msapplication-tap-highlight" content="no"/>
<title><?php 
	if ($this->fetch('title')){
		echo $this->fetch('title');
	}elseif (isset($title_for_layout)){
		echo h($title_for_layout);
	}
	?></title>
<?php
if (!empty($description_for_layout)) {
	echo $this->Html->meta('description', h($description_for_layout));
}
echo $this->fetch('meta');
?>	

<script type='text/javascript'>
	var BASE_URL = '<?php echo Router::url("/", true);?>'
</script>
<style type='text/css'>
html {
    -webkit-text-size-adjust: 100%;
    -ms-text-size-adjust: 100%;
}
</style>
<?php
if (!empty($description_for_layout)) {
	echo $this->Html->meta('description', h($description_for_layout));
}
echo $this->fetch('meta');
