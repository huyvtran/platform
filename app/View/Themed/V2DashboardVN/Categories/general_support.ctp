<section id="wrapper">
    <article class="infoInner">
        <ul class="rs lstFaq">
            <?php foreach($categories as $categorie) : ?>
<!--            --><?php //debug($categorie); ?>
                <?php if( !empty($categorie['Category']['category_id']) ) : ?>
                    <li><a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'supportGame', 'slug' => $categorie['Category']['slug'])) ?>"> <?php echo $categorie['Category']['title'] ?> </a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </article>
</section>