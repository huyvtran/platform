<div class="news clearfix">
    <div class="slider">
        <?php if(count($sliders) > 0){
             foreach($sliders as $slider){
        ?>
                <div class="item">
                    <a href="<?php echo isset($slider['description']['link'])?$slider['description']['link']:'#'; ?>">
                        <img src="<?php echo $this->Nav->image($slider, null, null, array('retina' => false, 'urlonly' => true));?>" alt="">
                    </a>
                </div>
        <?php }} ?>
    </div>
    <div class="tabs-news">
        <div class="navtabs clearfix">
            <ul class="list-unstyled">
                <li class="tab1"><a href="#tab1" data-tab=".tab1" data-src="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index','slug'=> 'news+events')); ?>">Tin mới</a></li>
                <li class="tab2"><a href="#tab2" data-tab=".tab2" data-src="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index','slug'=> 'news')) ?>">Tin tức</a></li>
                <li class="tab3"><a href="#tab3" data-tab=".tab3" data-src="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index','slug'=> 'events')) ?>">Sự kiện</a></li>
            </ul>
            <a href="#" class="sprite readmore-tab"></a>
        </div>
        <div class="tabs-container">
            <div class="tab-content" id="tab1">
                <ul class="list-unstyled list-posts">
                    <?php
                        if(count($newAndEvents) >0 ){
                        foreach($newAndEvents as $ne){
                    ?>
                    <li><a href="<?php echo $this->Html->url(array("controller" => "articles","action" => "view","category"=> $ne['Category']['slug'],"slug"=>$ne['Article']['slug'])) ; ?>">
                            <?php echo $ne['Article']['title']  ?>
                        </a><span class="date"><?php echo $this->Time->format('d.m.Y', $ne['Article']['published_date']); ?></span>
                    </li>
                    <?php }} ?>
                </ul>
            </div>
            <div class="tab-content" id="tab2" style="display:none">
                <ul class="list-unstyled list-posts">
                    <?php
                        if(count($news) >0 ){
                            foreach($news as $ne){
                                ?>
                                <li><a href="<?php echo $this->Html->url(array("controller" => "articles","action" => "view","category"=> $ne['Category']['slug'],"slug"=>$ne['Article']['slug'])) ; ?>">
                                        <?php echo $ne['Article']['title']  ?>
                                    </a><span class="date"><?php echo $this->Time->format('d.m.Y', $ne['Article']['published_date']); ?></span>
                                </li>
                    <?php }} ?>
                </ul>
            </div>
            <div class="tab-content" id="tab3" style="display:none">
                <ul class="list-unstyled list-posts">
                    <?php
                    if(count($events) >0 ){
                        foreach($events as $ne){
                            ?>
                            <li><a href="<?php echo $this->Html->url(array("controller" => "articles","action" => "view","category"=> $ne['Category']['slug'],"slug"=>$ne['Article']['slug'])) ; ?>">
                                    <?php echo $ne['Article']['title']  ?>
                                </a><span class="date"><?php echo $this->Time->format('d.m.Y', $ne['Article']['published_date']); ?></span>
                            </li>
                        <?php }} ?>
                </ul>
            </div>
        </div>
    </div>

</div>
<div class="bottom clearfix">
    <div class="community clearfix">
        <h2>Cộng đồng</h2>
        <?php echo $this->element('box-right'); ?>
    </div>
    <div class="hero-featured">
        <h2>Nhân vật</h2>
        <div class="owl-hero">
            <div class="item">
                <img src="http://cdn.smobgame.com/newfolder/dautruongpk/1.png" class="img-fix" alt="">
            </div>
            <div class="item">
                <img src="http://cdn.smobgame.com/newfolder/dautruongpk/2.png" class="img-fix" alt="">
            </div>
            <div class="item">
                <img src="http://cdn.smobgame.com/newfolder/dautruongpk/3.png" class="img-fix" alt="">
            </div>
            <div class="item">
                <img src="http://cdn.smobgame.com/newfolder/dautruongpk/4.png" class="img-fix" alt="">
            </div>

        </div>
    </div>
    <div class="fanpage">
        <h2>Fanpage</h2>
        <div class="fb-page" data-href="https://www.facebook.com/dautruongmegaxy/" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/dautruongmegaxy/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/dautruongmegaxy/">Đấu Trường Mega XY</a></blockquote></div>
    </div>
</div>