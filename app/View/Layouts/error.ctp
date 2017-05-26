<!DOCTYPE html>
<html lang="vi">
    <head>
    	<meta http-equiv="X-UA-Compatible" content="IE=8" />
        <meta charset="utf-8" />
        <?php echo $this->Html->charset();?>
        <title>
            <?php
            
            if ($this->fetch('title')){
                echo h($this->fetch('title'));
            }elseif (isset($title_for_layout)){
            	echo h($title_for_layout);
        	}
            ?>
        </title>
        <?php
        if (!empty($description_for_layout)) {
            echo $this->Html->meta('description', h($description_for_layout));
        }
        echo $this->fetch('meta');
        echo $this->Html->css('/css/style.css');
        echo $this->fetch('css');
		$cssInline = $this->fetch('css-inline');

		if (!empty($cssInline)){
			echo "<style type = 'text/css'>$cssInline</style>"; 
		}

        if (env("SERVER_ADDR") == '127.0.0.1') {
            echo $this->Html->css('debug');
        }
        ?>
    </head>
    <body>
        <?php echo $this->fetch('content'); ?>

<?php
if (env("SERVER_ADDR") == '127.0.0.1'){
	echo $this->element('sql_dump');
}
?>
    </body>
</html>