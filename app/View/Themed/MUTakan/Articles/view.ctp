<?php
if(count($relateArticles) >0){
    $relateArticles = array_slice($relateArticles,0,4);
}
$title = 'Articles';
$type   =   'events';
if(isset($this->request->params['category'])){
    $type   =   $this->request->params['category'];
}
switch ($type) {
    case 'news':
        $label = 'Tin tức';
        $title = 'TIN TỨC';
        $param_type = 'tin-tuc';
        break;
    case 'events':
        $label = 'Sự kiện';
        $title = 'SỰ KIỆN';
        $param_type = 'su-kien';
        break;
    case 'heroes':
        $label = 'Danh sách tướng';
        $title = 'DANH SÁCH TƯỚNG';
        $param_type = 'danh-sach-tuong';
        break;
    case 'khuyen-mai':
        $label = 'Khuyến mại';
        $title = 'Khuyến mại';
        $param_type = 'khuyen-mai';
        break;
    case 'features':
        $label = 'Đặc sắc';
        $title = 'ĐẶC SẮC';
        $param_type = 'dac-sac';
        break;
    case 'faq':
        $label = 'Câu hỏi thường gặp';
        $title = 'CÂU HỎI THƯỜNG GẶP';
        $param_type = 'cau-hoi-thuong-gap';
        break;
    default:
        $label = 'Tin tức';
        $title = 'TIN TỨC';
        $param_type = 'tin-tuc';
        break;
}
?>
<main id="main-content">
    <div class="container">

        <div class="page-news clearfix">
            <div class="wrap-post">
                <header class="clearfix page-title">
                    <h2><?php echo $label ?></h2>
                    <ul class="list-unstyled breadcrumb">
                        <li itemtype="http://data-vocabulary.org/Breadcrumb"><a href="<?php echo $this->Html->url('/home') ?>" itemprop="url">Trang chủ</a></li>
                        <li class="divide"> > </li>
                        <li itemtype="http://data-vocabulary.org/Breadcrumb">  <a href="<?php echo $this->Html->url('/').$param_type ;?>" itemprop="url"><?php echo $label ?></a></li>
                    </ul>
                </header>
                <div class="post-detail">
                    <h1> <?php echo $article['Article']['title']; ?></h1>
                    <p class="date">
                        <?php
                            echo date('d/m/Y',strtotime($article['Article']['published_date']));
                        ?>
                    </p>
                    <div class="rte">
                        <?php echo $article['Article']['parsed_body']; ?>
                    </div>
                    <div class="fb-like" data-href="<?php  echo Router::url( $this->here, true ); ?>" data-layout="standard" data-action="like" data-size="small" data-show-faces="true" data-share="true"></div>
                    <div class="dataTag" itemprop="articleTag">
                        <span>Tags: </span>
                        <?php if(count($listTags)){foreach($listTags as $key => $tag){ ?>
                            <a href="<?php echo $this->Html->url('/tag/'.$key); ?>"><?php echo $tag ;?> </a>,
                        <?php }} ?>
                    </div>
                    <div class="box-cmfb"><div class="fb-comments" data-href="<?php  echo Router::url( $this->here, true ); ?>" data-numposts="5" data-width="660"></div></div>
                </div>
                <div class="related-post">
                    <h2>Tin khác</h2>
                    <ul class="list-unstyled list-cate">
                      <?php
                        if(count($relateArticles) >0){
                            foreach($relateArticles as $article){
                      ?>
                                <li>
                                    <a href="<?php echo $this->Html->url(array("controller" => "articles","action" => "view","category"=> $article['Category']['slug'],"slug"=>$article['Article']['slug'])); ?>"><?php echo $article['Article']['title'];?></a>
                                    <span class="date"><?php echo $this->Time->format('d.m.Y', $article['Article']['published_date']); ?></span>
                                </li>
                        <?php }} ?>
                    </ul>
                </div>
            </div>
            <div class="community clearfix">
                <?php echo $this->element('box-right'); ?>
            </div>


        </div>
    </div>
</main>