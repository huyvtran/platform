<?php echo $this->element('count_time'); ?>

<?php if (!$this->request->is('ajax')) { ?>
    <div style="display: none">
        <?php echo $this->Session->flash() ?>
    </div>
<div id="sb-site" class="m-container">
    <!--Test store in app-->
    <!--    <a class="button" href="javascript:MobAppSDKexecute('mobOpenStoreInApp', {id: 533886215})">Test store in app</a>-->

    <!--nocache-->

    <?php
    $model = 'Article';
    $this->Nav->markAsReaded($model,$this->Session->read('Auth.User.id'));

    echo $this->element('userShow');

    ?>
    <?php if(!empty($this->request->params['pass'][0]) && $this->request->params['pass'][0] == 'events'){ ?>
    <!--  Going  -->
    <?php
        $Article = ClassRegistry::init('Article');
        $article_going = $Article->find('all', array(
            'conditions' => array(
                'Article.website_id' => $game['website_id'],
                'Article.published' => true,
                'Article.event_start <' => date('Y-m-d H:i:s'),
                'Article.event_end >' => date('Y-m-d H:i:s'),
                'Category.slug'     => 'events'
            ),
            'contain' => array('Avatar', 'Category'),
            'order' => array('Article.id' => 'DESC')
        ));
    ?>
    <h3 class="rs m-sTitle"><?php echo __('ĐANG DIỄN RA');?></h3>
    <div class="pd10 m-going">

    <?php echo $this->element('top_payment'); ?>

    <?php if(!empty($article_going)){
        foreach($article_going as $article){
    ?>
        <?php if(!empty($article['Article']['event_start']) && !empty($article['Article']['event_end'])){?>
            <a href="<?php echo $this->Html->url(array('controller' => 'articles', 'action' => 'view', 'category' => $article['Category']['slug'], 'slug' => $article['Article']['slug'])) ?>" class="itemNews">
            <span class="itemNewsImg">
                <?php
                echo $this->Nav->image($article['Avatar'], 110, 73, array(
                    'retina' => true,
                    'empty' => array('u' => 'no_thumb.jpg')
                ));
                ?>
            </span>
            <?php
            $hotnews = '';
            if ($article['Article']['is_new']) {
                $hotnews = "<span class='snews'>new</span>";
            }
            if ($article['Article']['is_hot']) {
                $hotnews = "<span class='shot'>hot</span>";
            }
            ?>
            <h3 class="rs itemNewsTitle m-read">
                <?php echo $hotnews; ?> <span title="<?php echo $article['Article']['title']; ?>">
                    <?php
                    if (strlen($article['Article']['title']) > 45 && $game['screen'] == 'vertical') {
                        echo $this->Text->truncate($article['Article']['title'], 43, array('exact' => false));
                    } else {
                        echo h($article['Article']['title']);
                    }
                    ?>
                </span>
            </h3>
            <?php $idGoing = "mob-boxTimeCD-going-" . $article['Article']['id']; ?>
            <span class="date dategoing" id="<?php echo $idGoing; ?>"></span>
            <script type="text/javascript">
                CountDownTimer("<?php echo $article['Article']['event_end']; ?>", "<?php echo $idGoing; ?>","<?php echo __('thời gian còn lại'); ?>","<?php echo $game['language_default']; ?>");
            </script>

            </a>
        <?php }} ?>
        <?php }else{
            App::import('Lib', 'RedisQueue');
            $Redis = new RedisQueue('default','top_payment_' . $game['alias']);
            $size = $Redis->lSize();
            if(empty($size) || $size == 0){
                echo "<p class='m-coming'>".__('Không có sự kiện mới') . "</p>";
            }
        } ?>
    </div>

    <!--    Comming up -->
    <?php
    $Article = ClassRegistry::init('Article');
    $Article->enablePublishable(false);
    $article_comming = $Article->find('all', array(
        'conditions' => array(
            'Article.website_id' => $game['website_id'],
            'Article.published' => true,
            'Article.event_start >' => date('Y-m-d H:i:s')
        ),
        'contain' => array('Avatar', 'Category'),
        'order' => array('Article.id' => 'DESC')
    ));
    if(!empty($article_comming)){
    ?>
        <h3 class="rs m-sTitle"><?php echo __('SẮP DIỄN RA');?></h3>
        <div class="pd10 m-comming">
            <?php foreach($article_comming as $article){
                if(!empty($article['Article']['event_start']) && !empty($article['Article']['event_end'])){
            ?>
            <a href="<?php echo $this->Html->url(array('controller' => 'articles', 'action' => 'view', 'category' => $article['Category']['slug'], 'slug' => $article['Article']['slug'])) ?>" class="itemNews">
            <span class="itemNewsImg">
                <?php
                echo $this->Nav->image($article['Avatar'], 110, 73, array(
                    'retina' => true,
                    'empty' => array('u' => 'no_thumb.jpg')
                ));
                ?>
            </span>
            <?php
            $hotnews = '';
            if ($article['Article']['is_new']) {
                $hotnews = "<span class='snews'>new</span>";
            }
            if ($article['Article']['is_hot']) {
                $hotnews = "<span class='shot'>hot</span>";
            }
            ?>
            <h3 class="rs itemNewsTitle m-read">
                <?php echo $hotnews; ?> <span title="<?php echo $article['Article']['title']; ?>">
                    <?php
                    if (strlen($article['Article']['title']) > 45 && $game['screen'] == 'vertical') {
                        echo $this->Text->truncate($article['Article']['title'], 43, array('exact' => false));
                    } else {
                        echo h($article['Article']['title']);
                    }
                    ?>
                </span>
            </h3>

            <span class="date datesk" id="mob-boxTimeCD-comming">
                <?php
                if($game['language_default'] == 'vie'){
                    if($this->Time->isThisYear($article['Article']['event_start']))
                        echo $this->Time->format("d \\t\h\á\\n\g m \l\ú\c h:i A",$article['Article']['event_start']) . ' ' . '(GMT+7)';
                    else echo $this->Time->format("d \\t\h\á\\n\g m \l\ú\c\, Y h:i A",$article['Article']['event_start']) . ' ' . '(GMT+7)';
                }else{
                    $time_zone = "Asia/Hong_Kong";
                    if(!empty($game['data']['time_zone'])) $time_zone = $game['data']['time_zone'];
                    if($this->Time->isThisYear($article['Article']['event_start'], $time_zone))
                        echo $this->Nav->timeShort($article['Article']['event_start'], "M d \a\\t h:i A", $time_zone);
                    else echo $this->Nav->timeShort($article['Article']['event_start'], "M d, Y \a\\t h:i A", $time_zone);
                }
                ?>
            </span>
            </a>
            <?php }} ?>
        </div>
    <?php } ?>
    <?php } ?>
    <!--/nocache-->

        <?php if(!empty($this->request->params['pass'][0]) && $this->request->params['pass'][0] == 'events'){ ?>
            <h3 class="rs m-sTitle"><?php echo __('SỰ KIỆN CŨ');?></h3>
            <div class="mob-listNew m-listevent pd10">
        <?php } ?>
        <!--  finished check ajax   -->
        <?php } ?>

        <?php if (!empty($articles)) {?>

        <!--    remove $articles from going, comming        -->
        <?php
            if (!empty($article_going)) {
                foreach ($article_going as $arti) {
                    $key = array_search($arti, $articles);
                    unset($articles[$key]);
                }
            }
            if (!empty($article_comming)) {
                foreach ($article_comming as $arti) {
                    $key = array_search($arti, $articles);
                    unset($articles[$key]);
                }
            }
        ?>
        <?php foreach($articles as $article) {?>
                <a href="<?php echo $this->Html->url(array('controller' => 'articles', 'action' => 'view', 'category' => $article['Category']['slug'], 'slug' => $article['Article']['slug'])) ?> " class='itemNews'>
                    <span class="itemNewsImg">
                        <?php
                        echo $this->Nav->image($article['Avatar'], 110, 73, array(
                            'retina' => true,
                            'empty' => array('u' => 'no_thumb.jpg')
                        ));

                        $class_css = 'm-read';

                        $hotnews = '';
                        if(strtotime($article['Article']['event_end']) > strtotime(date('Y-m-d H:i:s'))){
                            if ($article['Article']['is_new']) {
                                $hotnews = "<span class='snews'>new</span>";
                            }
                            if ($article['Article']['is_hot']) {
                                $hotnews = "<span class='shot'>hot</span>";
                            }
                        }

                        $time_start =  $this->Time->format("M d \a\\t h:i A",$article['Article']['event_start']);
                        if($game['language_default'] == 'vie'){
                            if($this->Time->isThisYear($article['Article']['event_start']))
                                $time_start =  $this->Time->format("d \\t\h\á\\n\g m \l\ú\c h:i A",$article['Article']['event_start']) . ' ' . '(GMT+7)';
                            else $time_start =  $this->Time->format("d \\t\h\á\\n\g m \l\ú\c\, Y h:i A",$article['Article']['event_start']) . ' ' . '(GMT+7)';
                        }else{
                            $time_zone = "Asia/Hong_Kong";
                            if(!empty($game['data']['time_zone'])) $time_zone = $game['data']['time_zone'];
                            if($this->Time->isThisYear($article['Article']['event_start'], $time_zone))
                                $time_start = $this->Nav->timeShort($article['Article']['event_start'], "M d \a\\t h:i A", $time_zone);
                            else $time_start = $this->Nav->timeShort($article['Article']['event_start'], "M d, Y \a\\t h:i A", $time_zone);
                        }

                        $class_date = "date";
                        ?>
                    </span>
                    <h3 class="rs itemNewsTitle <?php echo $class_css;?>">
                        <?php echo $hotnews; ?> <span title="<?php echo $article['Article']['title']; ?>">
                            <?php
                            if (strlen($article['Article']['title']) > 45 && $game['screen'] == 'vertical') {
                                echo $this->Text->truncate($article['Article']['title'], 43, array('exact' => false));
                            } else {
                                echo h($article['Article']['title']);
                            }
                            ?>
                        </span>
                    </h3>
                    <span class="<?php echo $class_date; ?>"><?php echo $time_start; ?></span>
                </a>
            <?php }} ?>
    </div>
    <?php
    if (!$this->request->is('ajax')) {
    if ($this->Paginator->hasNext('Article')) {?>
        <a href="javascript:void(0)" class="mob-loadmore"><?php echo __('Thêm') ?></a>
    <?php } ?>
</div>

<?php
    $param = $this->request->params['pass'][0];
    if(!empty($article["Category"]["slug"])) $param = $article["Category"]["slug"];
?>
    <script type='text/javascript'>
        $(function() {

            var fetchUrl = '<?php echo $this->Html->url(array("controller" =>
                        "categories", "action" => "index", $param, "page"))?>';
            var page = <?php echo (int) $this->Paginator->current('Article');?>;
            var end = <?php echo $this->Paginator->counter('{:pages}');?>;
            var fetching = false;

            $(".mob-loadmore").click(function() {
                var $this = $(this);
                if (!fetching) {
                    fetching = true;
                    page++;
                    $this.addClass('loading');
                    $.get(fetchUrl + ':' + page + '.ajax?mobgame_advertising_id=true', function(data) {
                        appendMore(data);
                        fetching = false;
                        $this.removeClass('loading');

                        if (page >= end) {
                            $(".mob-loadmore").remove();
                        }
                    })
                }
                return false;
            });
        });

        function appendMore(articles) {
            $(".mob-listNew > a:last-child").after(articles);
        }
    </script>
<?php } ?>