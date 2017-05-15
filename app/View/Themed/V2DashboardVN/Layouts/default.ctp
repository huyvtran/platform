<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php

    echo $this->element('meta_for_app_new_dashboard');
    if(!isset($title_for_app)) $title_for_app = __(' ');
    echo "<meta name = 'title' content = '$title_for_app' />";

    echo $this->Html->css('/uncommon/dashboard_v2/css/style.css');
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
    echo $this->Html->script('/uncommon/dashboard_v2/js/bg.js');
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
            ga('dbfuntap.send', 'screenview');
            <?php }else{ ?>
            ga('create', 'UA-82141936-3','auto', 'dbmobgame');
            ga('dbmobgame.send', 'screenview');
            <?php } ?>
            ga('smobgame.send', 'screenview');
            ga('dballgame.send', 'screenview');

        </script>
        <?php
    }
    ?>
</head>
<?php
$sdkVersion = $this->request->header('mobgame-sdk-version');
?>
<?php if(isset($this->request->query['ispop']) && $this->request->query['ispop'] == true && $sdkVersion >= '2.4.7' || !$sdkVersion  ){ ?>
<body class="info <?php echo $currentGame['slug']; ?> inside">
<div class="box-nv">
    <?php if(isset($this->request->query['back']) && $this->request->query['back'] == true){ ?>
        <a href="javascript:void(0)" class="sdk-b"></a>
    <?php } ?>
    <a href="javascript:void(0)" class="sdk-c"></a>
</div>
<?php }else{ ?>
<body class="info">
<?php } ?>
<div id="wrapper">
    <?php
    echo $this->fetch('content');

    if (Configure::read('debug') == 2){
        echo $this->element('sql_dump');
    }
    ?>
</div>
</body>
</html>