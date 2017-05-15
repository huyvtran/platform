<?php
App::import('Lib', 'RedisQueue');
$Article = ClassRegistry::init('Article');
$article_top_pay = $Article->find('first', array(
    'conditions' => array(
        'Article.website_id' => $game['website_id'],
        'Article.published' => true,
        'Category.slug'     => 'top-payment'
    ),
    'contain' => array('Avatar', 'Category'),
    'order' => array('Article.id' => 'DESC')
));
if(!empty($article_top_pay['Article']['event_start']) ){
    $Redis = new RedisQueue('default','top_payment_' . $game['alias']);
//    if($this->Session->read('Auth.User.username') == 'quanvh'){
//        $Redis->delete();
//    }
    $size = $Redis->lSize();
    if(empty($size) || $size == 0){
        $this->Cms->topPayment($game, $article_top_pay['Article']);
    }
    $pay = $Redis->lRange(0,-1);

    $payKNB = 0;
    if(!empty($pay[0])) {
        $payKNB = $pay[0];
    }

    ?>
    <a href="<?php echo $this->Html->url(array('controller' => 'articles', 'action' => 'view', 'category' => $article_top_pay['Category']['slug'], 'slug' => $article_top_pay['Article']['slug'])) ?>" class="itemNews">
            <span class="itemNewsImg">
                <?php
                echo $this->Nav->image($article_top_pay['Avatar'], 110, 73, array(
                    'retina' => true,
                    'empty' => array('u' => 'no_thumb.jpg')
                ));
                ?>
            </span>
        <?php
        $hotnews = '';
        if ($article_top_pay['Article']['is_new']) {
            $hotnews = "<span class='snews'>new</span>";
        }
        if ($article_top_pay['Article']['is_hot']) {
            $hotnews = "<span class='shot'>hot</span>";
        }
        ?>
        <h3 class="rs itemNewsTitle m-read">
            <?php echo $hotnews; ?>
            <span title="<?php echo $article_top_pay['Article']['title']; ?>">
                <?php
                if (strlen($article_top_pay['Article']['title']) > 45 && $game['screen'] == 'vertical') {
                    echo $this->Text->truncate($article_top_pay['Article']['title'], 43, array('exact' => false));
                } else {
                    echo h($article_top_pay['Article']['title']);
                }
                ?>
            </span>
        </h3>
        <span class="date dategoing" >
            <?php
            if($game['language_default'] == 'vie'){
                if($this->Time->isThisYear($article_top_pay['Article']['published_date']))
                    echo $this->Time->format("d \\t\h\á\\n\g m \l\ú\c h:i A",$article_top_pay['Article']['published_date']) . ' ' . '(GMT+7)';
                else echo $this->Time->format("d \\t\h\á\\n\g m \l\ú\c\, Y h:i A",$article_top_pay['Article']['published_date']) . ' ' . '(GMT+7)';
            }else{
                $time_zone = "Asia/Hong_Kong";
                if(!empty($game['data']['time_zone'])) $time_zone = $game['data']['time_zone'];
                if($this->Time->isThisYear($article_top_pay['Article']['published_date'], $time_zone))
                    echo $this->Nav->timeShort($article_top_pay['Article']['published_date'], "M d \a\\t h:i A", $time_zone);
                else echo $this->Nav->timeShort($article_top_pay['Article']['published_date'], "M d, Y \a\\t h:i A", $time_zone);
            }
            ?>
        </span>

        <span class="box-top cf">
            <span class="txt-xh">TOP 10 nạp</span>
            <span class="txt-tong"><?php echo $payKNB; ?> KNB</span>
        </span>
    </a>
<?php } ?>
