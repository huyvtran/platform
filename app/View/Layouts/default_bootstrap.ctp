<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="<?php echo Router::url('/') .'favicon.ico'; ?>" />

    <?php
    echo $this->element('meta_for_app');
    echo $this->Html->css('/css/style.css');
    echo $this->Html->css('/css/bootstrap.min.css');
    echo $this->Html->css('/css/font-awesome.min.css');
    echo $this->Html->css('/css/AdminLTE.css');
    echo $this->Html->css('/css/skins/_all-skins.css');
    echo $this->Html->css('/plugins/pace/pace.min.css');
    echo $this->Html->css('/js/chosen_v0.14.0/chosen.min.css');
    echo $this->Html->css('/js/pickadate.js-3.1.3/lib/themes/classic.css');
    echo $this->Html->css('/js/pickadate.js-3.1.3/lib/themes/classic.date.css');
    echo $this->Html->css('/js/pickadate.js-3.1.3/lib/themes/classic.time.css');
    echo $this->Html->css('/js/nprogress/nprogress.css');
    echo $this->fetch('css');
    $cssInline = $this->fetch('css-inline');
    if (!empty($cssInline)) {
       echo "<style type = 'text/css'>$cssInline</style>";
   }
   if (env("SERVER_ADDR") == '127.0.0.1') {
       echo $this->Html->css('debug');
   }

   echo $this->Html->script('jquery.min.js');
   echo $this->Html->script('jquery.slimscroll.min.js');
   echo $this->Html->script('bootstrap.min.js');
   echo $this->Html->script('fastclick.js');
   echo $this->Html->script('adminlte.js');
   echo $this->Html->script('app.js');
   echo $this->Html->script('/plugins/pace/pace.min.js');

		//echo $this->Html->script('jquery-2.0.0.min.js');
   echo $this->Html->script('stupidtable.js');
   echo $this->Html->script('chosen_v0.14.0/chosen.jquery.min.js');
   echo $this->Html->script('admin.js');
   ?>
   <script type='text/javascript'>
       var BASE_URL = '<?php echo Router::url("/", true); ?>'
   </script>
   <?php
		//echo $this->Html->script('bootstrap.min.js');
   echo $this->Html->script('admin_script.js');
   echo $this->Html->script('pickadate.js-3.1.3/lib/picker.js');
   echo $this->Html->script('pickadate.js-3.1.3/lib/picker.date.js');
   echo $this->Html->script('pickadate.js-3.1.3/lib/picker.time.js');
   echo $this->Html->script('pickadate.js-3.1.3/lib/legacy.js');
   echo $this->Html->script('/js/nprogress/nprogress.js');
   echo $this->fetch('script');
   ?>
   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
   <style type="text/css">body{font-family: 'Open Sans', sans-serif; color:#222;}</style>
</head>
<body class="hold-transition skin-black sidebar-mini">
    <div class="wrapper">
        <?php
        echo $this->element('nav'); ?>
        <?php
        echo $this->fetch('content');
        ?>
    </div>

    <script type="text/javascript">
       $(function() {
          <?php echo implode("\n", $this->Js->getBuffer()); ?>
      });
  </script>
  <?php
  if (Configure::read('debug') == 2) {
   /*echo $this->element('sql_dump');*/
}
?>
</body>
</html>