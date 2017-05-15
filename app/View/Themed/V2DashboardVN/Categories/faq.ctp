<section id="wrapper">
    <article class="infoInner">
        <ul class="rs lstFaq">
            <?php foreach ($articles_general as $i => $article) : ?>
                <li>
                    <a href="<?php echo $this->Html->url(array('controller' => 'articles', 'action' => 'viewGame', 'gameAlias' => $currentGame['alias'], 'slug' => $article['Article']['slug'], 'webId'=>$article['Article']['website_id']) ) ?>" title="<?php echo $article['Article']['title'] ?>"><?php echo $article['Article']['title']; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </article>
</section>