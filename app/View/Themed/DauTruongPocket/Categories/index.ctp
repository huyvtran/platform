<?php
$slug = 'events';
if(isset($this->request->params['slug'])){
    $slug = $this->request->params['slug'];
}
switch($slug){
    case 'news+events':
        $label = 'Tin mới';
        $type  = 'tin-tuc-su-kien';
        break;
    case 'news':
        $label = 'Tin tức';
        $type  = 'tin-tuc';
        break;
    case 'events':
        $label = 'Sự kiện';
        $type  = 'su-kien';
        break;
    case 'features':
        $label = 'Đặc sắc';
        $type  = 'dac-sac';
        break;
    case 'khuyen-mai':
        $label = 'Khuyến mại';
        $type  = 'khuyen-mai';
        break;
}
if( !empty($this->request->params['tag']) ) {
    $label = ' ' . $obj_tag[0]['Tag']['name'];
}
?>
<main id="main-content">
    <div class="container">
        <div class="page-news clearfix">
            <div class="wrap-post">
                <header class="clearfix page-title">
                    <h2><?php echo $label; ?></h2>
                    <ul class="list-unstyled breadcrumb">
                        <li><a href="<?php echo $this->Html->url('/home') ?>">Trang chủ</a></li>
                        <li class="divide"> > </li>
                        <li><?php echo $label; ?></li>
                    </ul>
                </header>
                <ul class="list-unstyled list-cate">
                    <?php if(count($articles) > 0){
                        foreach($articles as $article ){
                    ?>
                        <li>
                            <a href="<?php echo $this->Html->url(array("controller" => "articles","action" => "view","category"=> $article['Category']['slug'],"slug"=>$article['Article']['slug'])); ?>">
                                <img src="<?php echo $this->Nav->image($article['Avatar'], 101, 60, array('urlonly' => true,'empty' => 'transparent.gif')) ?>" alt="">
                                <?php echo $article['Article']['title'];?>
                            </a>
                            <span class="date"><?php echo $this->Time->format('d.m.Y', $article['Article']['published_date']); ?></span>
                        </li>
                    <?php }} ?>
                </ul>
                <div class="pagination">
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
                                    'url'=> array('controller' => 'categories', 'action'=>'index', 'slug'=> $slug)
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
            </div>
            <div class="community clearfix">
                <?php echo $this->element('box-right'); ?>
            </div>
           

        </div>
    </div>
</main>