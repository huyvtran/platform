<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <link rel="icon" href="<?php echo Router::url('/') .'favicon.ico'; ?>" />
        <?php
            if(!isset($title_for_app))
                $title_for_app = __('Nạp thẻ');

            echo "<title>" . $title_for_app . "</title>";
            echo "<meta name = 'title' content = '$title_for_app' />";
            echo '<meta name = "viewport" content = "user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />';
            echo '<meta name="apple-mobile-web-app-capable" content="yes"/>';

        echo $this->Html->css('/payment/css/bootstrap.min.css');
        echo $this->Html->css('/payment/css/font-awesome.min.css');
        echo $this->Html->css('/payment/css/bootstrap-theme.css');

            echo $this->Html->script('/js/jquery-2.0.0.min.js');
            echo $this->Html->script('/js/bootstrap.min.js');

            echo $this->element('js_libs_and_fallback');
            echo $this->Html->script('script.js');
            echo $this->fetch('script');

//            echo $this->element('call_app_func');

            echo $this->fetch('css');
            echo $this->fetch('script');
        ?>
    </head>

    <body>
        <?php echo $this->fetch('content'); ?>
    </body>
    <?php if (Configure::read('debug') == 2) { ?>
        <div class="table-responsive">
            <?php echo $this->element('sql_dump'); ?>
        </div>
    <?php } ?>
</html>