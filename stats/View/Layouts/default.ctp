<!DOCTYPE html>
<html>
	<head>
        <link rel="icon" href="<?php echo Router::url('/') .'favicon.ico'; ?>" />
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php
        // CSS
		echo $this->Html->css('/bower_components/bootstrap/dist/css/bootstrap.min.css');
		echo $this->Html->css('/bower_components/font-awesome/css/font-awesome.min.css');
		echo $this->Html->css('/bower_components/Ionicons/css/ionicons.min.css');
		echo $this->Html->css('/css/AdminLTE.min.css');
		echo $this->Html->css('/css/skins/skin-red.min.css');
		echo $this->Html->css('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic');
		echo $this->Html->css('/bower_components/chosen/chosen.min.css');
		echo $this->Html->css('/bower_components/pickadate/themes/default.css');
		echo $this->Html->css('/bower_components/pickadate/themes/default.date.css');
		echo $this->Html->css('/bower_components/pickadate/themes/default.time.css');
		//echo $this->Html->css('/bower_components/zurb-responsive-tables/responsive-tables.css');
		echo $this->Html->css('https://cdn.datatables.net/v/bs-3.3.7/dt-1.10.15/fc-3.2.2/sc-1.4.2/datatables.min.css');

		// old css and js
		//echo $this->Html->css('/js/chosen_v0.14.0/chosen.min.css');
		//echo $this->Html->css('/js/pickadate.js-3.1.3/themes/classic.css');
		//echo $this->Html->css('/js/pickadate.js-3.1.3/themes/classic.date.css');
		//echo $this->Html->css('/js/pickadate.js-3.1.3/themes/classic.time.css');
		//echo $this->Html->css('/js/zurb-responsive-tables/responsive-tables.css');
		// echo $this->Html->css('/js/DataTables-1.10.2/media/css/jquery.dataTables.css');
		//echo $this->Html->css('/js/DataTables-1.10.2/extensions/FixedColumns/css/dataTables.fixedColumns.css');
		//echo $this->Html->css('/js/DataTables-1.10.2/dataTables.bootstrap.css');
		echo $this->Html->css('/js/nprogress/nprogress.css');
		echo $this->fetch('css');
		$cssInline = $this->fetch('css-inline');
		if (!empty($cssInline)){
			echo "<style type = 'text/css'>$cssInline</style>"; 
		}
		if (env("SERVER_ADDR") == '127.0.0.1') {
			echo $this->Html->css('debug');
		}
		
		echo $this->Html->script('/js/jquery-2.0.3.min.js');
		?>
		<script type='text/javascript'>
			var BASE_URL = '<?php echo Router::url("/", true);?>'
		</script>		
		<?php
        //Javascript
		echo $this->Html->script('/bower_components/bootstrap/dist/js/bootstrap.min.js');
		echo $this->Html->script('/js/adminlte.min.js');
        // Pick a date
        echo $this->Html->script('/bower_components/pickadate/picker.js');
        echo $this->Html->script('/bower_components/pickadate/picker.date.js');
        echo $this->Html->script('/bower_components/pickadate/picker.time.js');
        echo $this->Html->script('/bower_components/pickadate/legacy.js');
        echo $this->Html->script('/bower_components/chosen/chosen.jquery.min.js');
        //echo $this->Html->script('/bower_components/zurb-responsive-tables/responsive-tables.js');
        echo $this->Html->script('https://cdn.datatables.net/v/bs/dt-1.10.15/fc-3.2.2/sc-1.4.2/datatables.min.js');
		echo $this->Html->script('highcharts-3.0.2/js/highcharts.js');
		echo $this->Html->script('highcharts-3.0.2/js/highcharts-more.js');
		//echo $this->Html->script('pickadate.js-3.1.3/picker.js');
		//echo $this->Html->script('pickadate.js-3.1.3/picker.date.js');
		//echo $this->Html->script('pickadate.js-3.1.3/picker.time.js');
		//echo $this->Html->script('pickadate.js-3.1.3/legacy.js');
		//echo $this->Html->script('zurb-responsive-tables/responsive-tables.js');
		//echo $this->Html->script('chosen_v0.14.0/chosen.jquery.min.js');
		//echo $this->Html->script('DataTables-1.10.2/media/js/jquery.dataTables.js');
		//echo $this->Html->script('DataTables-1.10.2/extensions/FixedColumns/js/dataTables.fixedColumns.js');
		//echo $this->Html->script('/js/DataTables-1.10.2/dataTables.bootstrap.js');
		echo $this->Html->script('/js/nprogress/nprogress.js');
		echo $this->Html->script('script.js');
		echo $this->fetch('script');
		?>
        <!-- Custom datatable style -->
        <style>
            th, td { white-space: nowrap; }
            div.dataTables_wrapper {
                width: 800px;
                margin: 0 auto;
            }
        </style>
	</head>
	<body class="hold-transition skin-red fixed sidebar-mini">
        <div class="wrapper">
		<?php

		echo $this->element('nav');
		?>
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Page title
                    </h1>
                </section>

                <!-- Main content -->
                <!--<section class="content container-fluid"> -->
                    <!--nocache-->
                    <?php
                    echo $this->Session->flash();
                    echo $this->Session->flash('auth', array('element' => 'info'));
                    ?>
                    <!--/nocache-->
                    <?php echo $this->fetch('content');?>
                <!--</section>-->
            </div>
            <footer class="main-footer">
                <!-- To the right -->
                <div class="pull-right hidden-xs">
                    <?php echo 'Proccess Time: ' . round(microtime(true) - TIME_START, 2) * 1000 . ' ms' ?>
                </div>
                <strong>Copyright &copy; 2017 <?php echo $this->Html->link('VNTap', 'http://vntap.vn') ?>.</strong> All rights reserved.
			</footer>

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
        </div>
	</body>
</html>