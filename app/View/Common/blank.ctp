<div class="content-wrapper">
    <!--/nocache-->
    <section class="content container-fluid">
        <!--nocache-->
        <?php
        echo $this->Session->flash();
        echo $this->Session->flash('auth', array('element' => 'info'));
        ?>
        <?php echo $this->fetch('content');?>
    </section>
</div>
<?php
echo $this->element('footer');
?>