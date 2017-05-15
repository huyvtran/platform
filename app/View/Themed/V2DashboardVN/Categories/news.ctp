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
    <!--/nocache-->

    <div class="box-listtin">
    <div class="mob-listNew pd10">
        <!--  finished check ajax   -->
        <?php } ?>

        <?php if (!empty($articles)) {?>
            <?php
            foreach($articles as $article) {?>
                <a href="<?php echo $this->Html->url(array('controller' => 'articles', 'action' => 'view', 'category' => $article['Category']['slug'], 'slug' => $article['Article']['slug'])) ?> " class='itemNews'>
                    <span class="itemNewsImg">
                        <?php
                        echo $this->Nav->image($article['Avatar'], 110, 73, array(
                            'retina' => true,
                            'empty' => array('u' => 'no_thumb.jpg')
                        ));

                        $class_css = 'm-read';

                        $hotnews = '';
                        if ($article['Article']['is_new']) {
                            $hotnews = "<span class='snews'>new</span>";
                        }
                        if ($article['Article']['is_hot']) {
                            $hotnews = "<span class='shot'>hot</span>";
                        }

                        $class_date = "date";
                        $time_published =  $this->Time->format("M d \a\\t h:i A",$article['Article']['published_date']);
                        if($game['language_default'] == 'vie'){
                            if($this->Time->isThisYear($article['Article']['published_date']))
                                $time_published =  $this->Time->format("d \\t\h\á\\n\g m \l\ú\c h:i A",$article['Article']['published_date']) . ' ' . '(GMT+7)';
                            else $time_published =  $this->Time->format("d \\t\h\á\\n\g m \l\ú\c\, Y h:i A",$article['Article']['published_date']) . ' ' . '(GMT+7)';

                            if ($this->Time->isToday($article['Article']['published_date'])) {
                                $time_published = __('Hôm nay') . ' ' . $this->Time->format("\l\ú\c h:i A",$article['Article']['published_date']) . ' ' . '(GMT+7)';
                                $class_date = $class_date. " datesk";
                            }elseif ($this->Time->wasYesterday($article['Article']['published_date'])) {
                                $time_published = __('Hôm qua') . ' ' . $this->Time->format("\l\ú\c h:i A",$article['Article']['published_date']) . ' ' . '(GMT+7)';
                            }

                        }else{
                            $time_zone = "Asia/Hong_Kong";
                            if(!empty($game['data']['time_zone'])) $time_zone = $game['data']['time_zone'];
                            if($this->Time->isThisYear($article['Article']['published_date'], $time_zone))
                                $time_published =  $this->Nav->timeShort($article['Article']['published_date'], "M d \a\\t h:i A", $time_zone);
                            else $time_published =  $this->Nav->timeShort($article['Article']['published_date'], "M d, Y \a\\t h:i A", $time_zone);

                            if ($this->Time->isToday($article['Article']['published_date'], $time_zone)) {
                                $time_published = __('Hôm nay') . " " . $this->Nav->timeShort($article['Article']['published_date'], "\a\\t h:i A", $time_zone);
                                $class_date = $class_date. " datesk";
                            }elseif ($this->Time->wasYesterday($article['Article']['published_date'], $time_zone)) {
                                $time_published = __('Hôm qua') . " " . $this->Nav->timeShort($article['Article']['published_date'], "\a\\t h:i A", $time_zone);
                            }
                        }

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
                    <span class="<?php echo $class_date; ?>"><?php echo $time_published; ?></span>
                    
                </a>
            <?php }} ?>
    </div>
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