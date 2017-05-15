<?php
if (!isset($currentGame)) {
    $currentGame = "";
}
    $gameConfigs        = $this->Cms->getLinkForSite($currentGame);
    $appstore_link      = (isset($gameConfigs['appstore_link'])&& $gameConfigs['appstore_link'] != '') ? $gameConfigs['appstore_link'] : "";
    $google_play_link   = (isset($gameConfigs['google_play_link'])&& $gameConfigs['google_play_link'] != '') ? $gameConfigs['google_play_link'] : "";
    $apk_link           = (isset($gameConfigs['apk_link'])&& $gameConfigs['apk_link'] != '') ? $gameConfigs['apk_link'] : "";
?>
<div class="main fixCen">
    <div class="s-boxHead pRel">
        <div class="breadcrumb-b3 cf">
            <div itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
                <a href="<?php echo $this->Html->url('/home') ?>" itemprop="url">
                    <span itemprop="title">Trang chủ </span>
                </a>
                /
            </div>
            <div itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
                <span itemprop="title">Danh sách class</span>
            </div>
        </div>
    </div>
    <div class="box-content pRel cf">
        <div class="s-boxLeft">
            <ul class="lstBoxHeroes rs cf">
                <?php if(count($articles) >0){
                    foreach($articles as $hero){
                ?>
                <li>
                    <a href="<?php echo $this->Html->url(array('controller' => 'articles', 'action' => 'view','category'=>'heroes','slug'=>$hero['Article']['slug'])) ?>" class="s-thumbH">
                        <img class="lazy" width="100" height="100" src="<?php echo $this->Nav->image($hero['Avatar'], 100, 100, array('urlonly' => true,'empty' => 'transparent.gif')) ?>">
                        <span class="s-nameH"><?php echo $hero['Article']['title']; ?></span>
                    </a>
                </li>
                <?php }} ?>
            </ul>
            <?php
            $hasPages = ($this->params['paging']['Article']['pageCount'] > 1);
            if ($hasPages) :
                ?>
                <ul class="rs tc box-page pRel">
                    <?php
                    unset($this->params['controller']);
                    unset($this->params['action']);
                    $this->params['controller']='';
                    $this->params['action']='';
                    $this->Paginator->options(
                        array(
                            'url'=> array('controller' => 'categories', 'action'=>'index', 'slug'=> empty($articles[0]['Category']['slug']) ? 'heroes' : $articles[0]['Category']['slug'] )
                        )
                    );
                    //               <?php
                    echo $this->Paginator->first('<<', array('tag'=>'li'));
                    echo $this->Paginator->prev('<', array('tag'=>'li'));
                    echo $this->Paginator->numbers(
                        array('separator' => '','tag'=>'li'));
                    echo $this->Paginator->next('>', array('tag'=>'li'));
                    echo $this->Paginator->last('>>', array('tag'=>'li'));
                    ?>
                </ul>
            <?php endif; ?>
        </div>
        <div class="s-boxRight">
            <div class="s-boxDl ">
                <h2 class="rs s-boxTitle">Tải game</h2>
                <div class="s-boxButton cf">
                    <a href="<?php echo $google_play_link; ?>" class="s-button spr s-gg"><span>Google Play</span></a>
                    <a href="<?php echo $appstore_link; ?>" class="s-button spr s-app"><span>App Store</span></a>
                    <a href="<?php echo $apk_link; ?>" class="s-button spr s-apk"><span>APK</span></a>
                </div>
            </div>
            <div class="s-boxFB">
                <div class="fb-page" data-href="https://www.facebook.com/badaochimong/" data-width="257" data-height="214" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/baodaochimong/"><a href="https://www.facebook.com/badaochimong/">Mộng Vương Quyền</a></blockquote></div></div>
            </div>
        </div>
    </div>
</div>