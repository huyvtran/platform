<div id="sb-site" class="m-container">
    <div class="entry-content">
        <div class="mob-BoxNews">
            <?php
                $hotnews = '';
                if ($article['Article']['is_new']) {
                    $hotnews = "<span class='snews'>new</span>";
                }
                if ($article['Article']['is_hot']) {
                    $hotnews = "<span class='shot'>hot</span>";
                }

                $class_date = "date";
                $time_published =  $this->Time->format("d/m/Y",$article['Article']['published_date']);
                if($currentGame['language_default'] == 'vie'){
                    $time_published =  $this->Time->format("d/m/Y",$article['Article']['published_date']) . ' ' . '(GMT+7)';

                    if ($this->Time->isToday($article['Article']['published_date'])) {
                        $time_published = __('Hôm nay') ;
                        $class_date = $class_date. " datesk";
                    }elseif ($this->Time->wasYesterday($article['Article']['published_date'])) {
                        $time_published = __('Hôm qua') ;
                    }
                }else{
                    $time_zone = "Asia/Hong_Kong";
                    if(!empty($currentGame['data']['time_zone'])) $time_zone = $currentGame['data']['time_zone'];

                    $time_published = $this->Nav->timeShort($article['Article']['published_date'], "d/m/Y", $time_zone);
                    if ($this->Time->isToday($article['Article']['published_date'], $time_zone)) {
                        $time_published = __('Hôm nay') ;
                        $class_date = $class_date. " datesk";
                    }elseif ($this->Time->wasYesterday($article['Article']['published_date'], $time_zone)) {
                        $time_published = __('Hôm qua') ;
                    }
                }
            ?>
                <h1 class="focus rs" itemprop="name"><?php echo $hotnews.$article['Article']['title']; ?></h1>
                <span class="mob-timeDetail"><span class="<?php echo $class_date; ?>"><?php echo __('Cập nhật lần cuối'); ?>: <?php echo $time_published; ?></span></span>

        </div>
        <div class="mob-listEvent ">
            <article class="box-detail" itemscope="" itemtype="http://schema.org/Article">
                <div class="dataBody" itemprop="articleBody">
                    <?php echo $article['Article']['parsed_body']; ?>
                </div>
            </article>
        </div>
    </div>
</div>
<script type='text/javascript'>
    $(function() {
        $('body').removeClass("info");
    });
</script>