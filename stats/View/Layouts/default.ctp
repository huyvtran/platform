<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php
		echo $this->Html->css('/css/bootstrap-3.2.0/css/bootstrap.min.css');
		echo $this->Html->css('/css/style.css');
		echo $this->Html->css('/js/chosen_v0.14.0/chosen.min.css');
		echo $this->Html->css('/js/pickadate.js-3.1.3/themes/classic.css');
		echo $this->Html->css('/js/pickadate.js-3.1.3/themes/classic.date.css');
		echo $this->Html->css('/js/pickadate.js-3.1.3/themes/classic.time.css');
		echo $this->Html->css('/js/zurb-responsive-tables/responsive-tables.css');
		// echo $this->Html->css('/js/DataTables-1.10.2/media/css/jquery.dataTables.css');
		echo $this->Html->css('/js/DataTables-1.10.2/extensions/FixedColumns/css/dataTables.fixedColumns.css');
		echo $this->Html->css('/js/DataTables-1.10.2/dataTables.bootstrap.css');
		echo $this->Html->css('/js/nprogress/nprogress.css');
		echo $this->fetch('css');
		$cssInline = $this->fetch('css-inline');
		if (!empty($cssInline)){
			echo "<style type = 'text/css'>$cssInline</style>"; 
		}
		if (env("SERVER_ADDR") == '127.0.0.1') {
			echo $this->Html->css('debug');
		}
		
		echo $this->Html->script('jquery-2.0.3.min.js');
		?>
		<script type='text/javascript'>
			var BASE_URL = '<?php echo Router::url("/", true);?>'
		</script>		
		<?php
		echo $this->Html->script('/css/bootstrap-3.2.0/js/bootstrap.min.js');
		echo $this->Html->script('highcharts-3.0.2/js/highcharts.js');
		echo $this->Html->script('highcharts-3.0.2/js/highcharts-more.js');
		echo $this->Html->script('pickadate.js-3.1.3/picker.js');
		echo $this->Html->script('pickadate.js-3.1.3/picker.date.js');
		echo $this->Html->script('pickadate.js-3.1.3/picker.time.js');
		echo $this->Html->script('pickadate.js-3.1.3/legacy.js');
		echo $this->Html->script('zurb-responsive-tables/responsive-tables.js');
		echo $this->Html->script('chosen_v0.14.0/chosen.jquery.min.js');
		echo $this->Html->script('DataTables-1.10.2/media/js/jquery.dataTables.js');
		echo $this->Html->script('DataTables-1.10.2/extensions/FixedColumns/js/dataTables.fixedColumns.js');
		echo $this->Html->script('/js/DataTables-1.10.2/dataTables.bootstrap.js');
		echo $this->Html->script('/js/nprogress/nprogress.js');
		echo $this->Html->script('script.js');
		echo $this->fetch('script');
		?>
	</head>
	<body>
		<?php

		echo $this->element('nav');
		?>
		<div class="container">
			<div class="content">
				<!--nocache-->
				<?php
				echo $this->Session->flash();
				echo $this->Session->flash('auth', array('element' => 'info'));
				?>              
				<!--/nocache-->
				<?php echo $this->fetch('content');?>
			</div>
			<footer class='footer'>
				@copyright 2013
				<div class='muted'><?php echo date("Y-m-d H:i:s") ?></div>
				<div class='muted'><?php echo 'Proccess Time: ' . round(microtime(true) - TIME_START, 2) * 1000 . ' ms' ?></div>				
			</footer>	
		</div>

<script type="text/javascript">
	$(function(){
		<?php echo implode("\n", $this->Js->getBuffer()); ?>
	});
</script>

<?php
if (Configure::read('debug') > 0) {
	echo $this->element('sql_dump');
}
?>
	</body>
</html>