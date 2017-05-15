<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>info</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <?php
        echo $this->Html->css('/uncommon/dashboard_v2/css/style.css', null, array('inline' => false));
        echo $this->fetch('css');
    ?>
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1,user-scalable=no" />
    <?php
        echo $this->Html->script('/uncommon/dashboard_v2/js/zepto.min.js');
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
<body class="body-g">
    <?php echo $this->fetch('content') ?>
</body>
</html>