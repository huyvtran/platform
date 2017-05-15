<!--nocache-->
<?php
$model = 'Article';
$this->Nav->markAsReaded($model,$this->Session->read('Auth.User.id'));
?>
<!--/nocache-->

<?php echo $this->element('count_time'); ?>

<?php if (!$this->request->is('ajax')) { ?>
    <div style="display: none">
        <?php echo $this->Session->flash() ?>
    </div>
<div id="sb-site" class="m-container">
    <!--Test store in app-->
    <!--    <a class="button" href="javascript:MobAppSDKexecute('mobOpenStoreInApp', {id: 533886215})">Test store in app</a>-->

    <!--nocache-->
    <?php if(!empty($userInfo)){?>
    <div class="m-boxNotice animated">
        <div class="m-noticeInfo">
            <a href="javascript:void(0)" class="notice-vip">ID: <span class="text-notice"><?php echo $userInfo['Account'][0]['account_id']; ?></span>
                <?php if(!empty($userInfo['User']['vip']) && $userInfo['User']['vip'] != 0){
                    $class_vip = "";
                    if($userInfo['User']['vip'] == 1) $class_vip = "vip1";
                    if($userInfo['User']['vip'] == 2) $class_vip = "vip2";
                    if($userInfo['User']['vip'] == 3) $class_vip = "vip3";
                    if($userInfo['User']['vip'] == 4) $class_vip = "vip4";
                    if($userInfo['User']['vip'] == 5) $class_vip = "vip5";
                    if($userInfo['User']['vip'] == 6) $class_vip = "vip6";
                    if($userInfo['User']['vip'] == 7) $class_vip = "vip7";
                    if($userInfo['User']['vip'] == 8) $class_vip = "vip8";
                    if($userInfo['User']['vip'] == 9) $class_vip = "vip9";
                    if($userInfo['User']['vip'] == 10) $class_vip = "vip10";
                ?>
                    <span class="m-vip <?php echo ' '.$class_vip;?>"><?php echo 'Vip '.$userInfo['User']['vip']; ?></span>
                <?php } ?>
            </a>
            <a class=" f-mescoin" href="javascript:void(0)"><span class="f-coin"><?php echo $userInfo['User']['mobpoint_total']; ?></span></a>
        </div>
        <?php if (!$this->Session->read('Auth.User.email') && $this->Nav->showFunction('hide_update_account', $game['Game']) ): ?>
        <a href="javascript:MobAppSDKexecute('mobFacebookForUpdate', {})" class="m-upgrade"><?php echo __('Kết nối Facebook ngay +') ?>
            <?php   if (empty( $game['Game']) || $this->Nav->showFunction('hide_popup_coin',  $game['Game'])) {?>
                <span class="f-coin">100</span>
            <?php } ?>
        </a>
        <?php endif; ?>
    </div>
    <?php } ?>

    <?php if(!empty($this->request->params['pass'][0]) && $this->request->params['pass'][0] == 'events'){ ?>
    <!--  Going  -->
    <?php
        $Article = ClassRegistry::init('Article');
        $article_going = $Article->find('first', array(
            'conditions' => array(
                'Article.website_id' => $game['Website']['id'],
                'Article.published' => true,
                'Article.event_start <' => date('Y-m-d H:i:s'),
                'Article.event_end >' => date('Y-m-d H:i:s')
            ),
            'contain' => array('Avatar', 'Category'),
            'order' => array('Article.id' => 'DESC')
        ));
    ?>
    <div class="pd10 m-going">
        <h3 class="rs m-sTitle"><?php echo __('ĐANG DIỄN RA');?></h3>
        <?php if(!empty($article_going) && !empty($article_going['Article']['event_start']) && !empty($article_going['Article']['event_end'])){?>
            <a href="<?php echo $this->Html->url(array('controller' => 'articles', 'action' => 'view', 'category' => $article_going['Category']['slug'], 'slug' => $article_going['Article']['slug'])) ?>" class="itemNews">
            <span class="itemNewsImg">
                <?php
                echo $this->Nav->image($article_going['Avatar'], 110, 73, array(
                    'retina' => true,
                    'empty' => array('u' => 'no_thumb.jpg')
                ));
                ?>
            </span>
            <?php
            $title_going = '';
            $title_going .= $article_going['Article']['title'];
            if ($article_going['Article']['is_new']) {
                $title_going .= ' ' . $this->Html->image('/uncommon/dashboard/images/new.gif', array('class' => 'absmiddle', 'alt' => 'new')) . ' ';
            }
            if ($article_going['Article']['is_hot']) {
                $title_going .= ' ' . $this->Html->image('/uncommon/dashboard/images/hot.gif', array('class' => 'absmiddle', 'alt' => 'hot'));
            }
            ?>
            <h3 class="rs itemNewsTitle m-read">
                <span title="<?php echo $article_going['Article']['title']; ?>"> <?php echo $title_going; ?> </span>
            </h3>
            <span class="date dategoing" id="mob-boxTimeCD-going"></span>
            <script type="text/javascript">
                CountDownTimer("<?php echo $article_going['Article']['event_end']; ?>", 'mob-boxTimeCD-going');
            </script>

            </a>
        <?php }else{
            echo "<p class='m-coming'>".__('Không có sự kiện mới') . "</p>";
        } ?>
    </div>

    <!--    Comming up -->
    <?php
    $Article = ClassRegistry::init('Article');
    $Article->enablePublishable(false);
    $article_comming = $Article->find('first', array(
        'conditions' => array(
            'Article.website_id' => $game['Website']['id'],
            'Article.event_start >' => date('Y-m-d H:i:s')
        ),
        'contain' => array('Avatar', 'Category'),
        'order' => array('Article.id' => 'DESC')
    ));
    if(!empty($article_comming) && !empty($article_comming['Article']['event_start']) && !empty($article_comming['Article']['event_end'])){
    ?>
        <div class="pd10 m-comming">
            <h3 class="rs m-sTitle"><?php echo __('SẮP DIỄN RA');?></h3>
            <a href="<?php echo $this->Html->url(array('controller' => 'articles', 'action' => 'view', 'category' => $article_comming['Category']['slug'], 'slug' => $article_comming['Article']['slug'])) ?>" class="itemNews">
            <span class="itemNewsImg">
                <?php
                echo $this->Nav->image($article_comming['Avatar'], 110, 73, array(
                    'retina' => true,
                    'empty' => array('u' => 'no_thumb.jpg')
                ));
                ?>
            </span>
            <?php
            $title_comming = '';
            $title_comming .= $article_comming['Article']['title'];
            if ($article_comming['Article']['is_new']) {
                $title_comming .= ' ' . $this->Html->image('/uncommon/dashboard/images/new.gif', array('class' => 'absmiddle', 'alt' => 'new')) . ' ';
            }
            if ($articles[0]['Article']['is_hot']) {
                $title_comming .= ' ' . $this->Html->image('/uncommon/dashboard/images/hot.gif', array('class' => 'absmiddle', 'alt' => 'hot'));
            }
            ?>

            <h3 class="rs itemNewsTitle m-read">
                <span  title="<?php echo $article_comming['Article']['title']; ?> "><?php echo $title_comming; ?></span>
            </h3>

            <span class="date datesk" id="mob-boxTimeCD-comming">
                <script type="text/javascript">
                    CountDownTimer("<?php echo $article_comming['Article']['event_start']; ?>", 'mob-boxTimeCD-comming');
                </script>
            </span>
            </a>
        </div>
    <?php } ?>
    <?php }else if(!empty($this->request->params['pass'][0]) && $this->request->params['pass'][0] == 'news'){ ?>
        <?php if (!empty($articles)) {?>
            <div class="itemNewsHot">
                <a href="<?php echo $this->Html->url(array('controller' => 'articles', 'action' => 'view', 'category' => $articles[0]['Category']['slug'], 'slug' => $articles[0]['Article']['slug'])) ?>" class="itemNews">
                    <span class="itemNewsImg">
                        <?php
                        echo $this->Nav->image($articles[0]['Avatar'], 110, 73, array(
                            'retina' => true,
                            'empty' => array('u' => 'no_thumb.jpg')
                        ));

                        $title = '';
                        $title .= $articles[0]['Article']['title'];
                        if ($articles[0]['Article']['is_new']) {
                            $title .= ' ' . $this->Html->image('/uncommon/dashboard/images/new.gif', array('class' => 'absmiddle', 'alt' => 'new')) . ' ';
                        }
                        if ($articles[0]['Article']['is_hot']) {
                            $title .= ' ' . $this->Html->image('/uncommon/dashboard/images/hot.gif', array('class' => 'absmiddle', 'alt' => 'hot'));
                        }

                        $time_published = $this->Time->format("M d, Y",$articles[0]['Article']['published_date']);

                        $class_date = "date";

                        if ($this->Time->isToday($articles[0]['Article']['published_date'])) {
                            $time_published = __('Hôm nay');
                            $class_date = $class_date. " datesk";
                        }elseif ($this->Time->wasYesterday($articles[0]['Article']['published_date'])) {
                            $time_published = __('Hôm qua');
                        }
                        ?>
                    </span>
                    <h3 class="rs itemNewsTitle"><span  title="<?php echo $articles[0]['Article']['title']; ?>"> <?php echo $title; ?> </span></h3>
                    <span class="<?php echo $class_date; ?>"><?php echo $time_published; ?></span>
                </a>
            </div>
        <?php } ?>
    <?php } ?>
    <!--/nocache-->

    <div class="mob-listNew pd10">
        <?php if(!empty($this->request->params['pass'][0]) && $this->request->params['pass'][0] == 'events'){ ?>
            <h3 class="rs m-sTitle"><?php echo __('SỰ KIỆN CŨ');?></h3>
        <?php } ?>
        <!--  finished check ajax   -->
        <?php } ?>

        <?php if (!empty($articles)) {
                if(!empty($this->request->params['pass'][0]) && $this->request->params['pass'][0] == 'news')
                    unset($articles[0]);
            ?>
            <?php
            foreach($articles as $article) {?>
                <?php
                    if(!empty($this->request->params['pass'][0]) && $this->request->params['pass'][0] == 'events'
                        && !empty($article['Article']['id']) && isset($article_going) && !empty($article_going['Article']['id'])
                        && $article['Article']['id'] == $article_going['Article']['id']) continue;
                ?>
                <?php
                if(!empty($this->request->params['pass'][0]) && $this->request->params['pass'][0] == 'events'
                    && !empty($article['Article']['id']) && isset($article_comming) && !empty($article_comming['Article']['id'])
                    && $article['Article']['id'] == $article_comming['Article']['id']) continue;
                ?>

                <a href="<?php echo $this->Html->url(array('controller' => 'articles', 'action' => 'view', 'category' => $article['Category']['slug'], 'slug' => $article['Article']['slug'])) ?> " class='itemNews'>
                    <span class="itemNewsImg">
                        <?php
                        echo $this->Nav->image($article['Avatar'], 110, 73, array(
                            'retina' => true,
                            'empty' => array('u' => 'no_thumb.jpg')
                        ));

                        $class_css = 'm-read';
//                        if(!empty($article['Article']['published_date']) && !empty($timeReaded['NotificationRead']['created']) &&
//                            strtotime($article['Article']['published_date']) > strtotime($timeReaded['NotificationRead']['created']))
//                            $class_css = "";

                        $title = '';
                        $title .= $article['Article']['title'];
                        if ($article['Article']['is_new']) {
                            $title .= ' ' . $this->Html->image('/uncommon/dashboard/images/new.gif', array('class' => 'absmiddle', 'alt' => 'new')) . ' ';
                        }
                        if ($article['Article']['is_hot']) {
                            $title .= ' ' . $this->Html->image('/uncommon/dashboard/images/hot.gif', array('class' => 'absmiddle', 'alt' => 'hot'));
                        }

                        $time_published = $this->Time->format("M d, Y",$article['Article']['published_date']);

                        $class_date = "date";

                        if ($this->Time->isToday($article['Article']['published_date'])) {
                            $time_published = __('Hôm nay');
                            $class_date = $class_date. " datesk";
                        }elseif ($this->Time->wasYesterday($article['Article']['published_date'])) {
                            $time_published = __('Hôm qua');
                        }

                        ?>
                    </span>
                    <h3 class="rs itemNewsTitle <?php echo $class_css;?>"><span  title="<?php echo $article['Article']['title']; ?>"> <?php echo $title; ?> </span></h3>
                    <span class="<?php echo $class_date; ?>"><?php echo $time_published; ?></span>
                    
                </a>
            <?php }} ?>

        <?php
        if (!$this->request->is('ajax')) {
        if ($this->Paginator->hasNext('Article')) {?>
            <a href="javascript:void(0)" class="mob-loadmore">More</a>
        <?php } ?>
    </div>
</div>

    <script type='text/javascript'>
        $(function() {

            var fetchUrl = '<?php echo $this->Html->url(array("controller" =>
                        "categories", "action" => "index", $article["Category"]["slug"], "page"))?>';
            var page = <?php echo (int) $this->Paginator->current('Article');?>;
            var end = <?php echo $this->Paginator->counter('{:pages}');?>;
            var fetching = false;

            $(".mob-loadmore").click(function() {
                var $this = $(this);
                if (!fetching) {
                    fetching = true;
                    page++;
                    $this.addClass('loading');
                    $.get(fetchUrl + ':' + page + '.ajax', function(data) {
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
            $(".mob-loadmore").before(articles);
        }

        var iden1;
        var position=0;
        header_h = $(".m-boxNotice").height();
        document.addEventListener("scroll", onScroll, false);
        function onScroll() {
            processScroll()
        }
        function processScroll() {
            var a = $(window).scrollTop();
            if (a <= 100) {
                $('.m-boxNotice').addClass('fadeInUp').show();
            }
            if (a > position) {
                jQuery('.m-boxNotice').removeClass('fadeInUp').hide();
                iden1 = 0;
                console.log('dcmm');
            } else {
                if (position > a + 1) {
                    if ($(window).scrollTop() > header_h) {
                        if (iden1 == 0) {
                            $('.m-boxNotice').addClass('fadeInUp').show();
                            iden1 = 1
                        }
                    }
                }
            }
            position = a
        }
    </script>
<?php } ?>
