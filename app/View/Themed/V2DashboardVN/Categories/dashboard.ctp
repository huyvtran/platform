<section id="wrapper">
    <article class="infoInner">
        <ul class="rs lstGuide">
            <?php /*if($currentGame['language_default'] == 'vie'){?>
                <li><a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'generalSupport')) ?>" class="g-faq">FAQ</a></li>
            <?php }else { ?>
                <li><a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index', 'faq')) ?>" class="g-faq">FAQ</a></li>
            <?php }*/ ?>
            <li><a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'generalSupport')) ?>" class="g-faq">FAQ</a></li>

            <li><a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index', 'guides')) ?>" class="g-babe"><?php echo __("Hướng dẫn"); ?></a></li>
        </ul>
    </article>
</section>
<script type="text/javascript">
    $(document).ready(function(){
        $("body").addClass("info");
    });
</script>