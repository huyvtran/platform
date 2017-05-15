<?php
//    if(!isset($title_for_layout)){
//        $title_for_layout = 'Pocket Đại Chiến - Cùng Pikachu chinh phục thế giới Pokémon huyền thoại.';
//    }
//    if(!isset($description_for_layout)){
//        $description_for_layout = '“Pocket Đại Chiến” là game chiến thuật về thế giới Pokémon chân thực nhất trên điện thoại.';
//    }
    if(!isset($image_title)){
        $image_title = "http://cdn.smobgame.com/newfolder/dautruongpk/teaser/logo3.png";
    }
    if(!isset($key_words) || $key_words == ''){
        $key_words = 'pikachu, pet, bảo bối, truyền kỳ, game thẻ bài, house, pk, bikachu, pokemon online, go, đấu trường, funtap, game mobile';
    }
    if(!isset($description_for_layout)){
        $description_for_layout = 'Đấu Trường Mega XY';
    }
?>
<title><?php echo $title_for_layout; ?></title>
<link href="<?php echo $this->Html->url('/') ?>uncommon/dautruongpk/favicon.ico?v=1" type="image/x-icon" rel="shortcut icon" />

<meta name="keywords" content="<?php echo $key_words; ?>" />
<meta name="author" content="Đấu Trường Mega XY" />
<meta name="description" content='<?php echo $description_for_layout ?>' />
<meta name="generator" content="Đấu Trường Mega XY" />
<!--meta facebook-->
<meta name="author" content="Đấu Trường Mega XY"/>
<meta property="article:author" content="https://www.facebook.com/dautruongmegaxy/" />
<meta property="og:site_name" content="Đấu Trường Mega XY" />
<meta property="og:type" content="article" />
<meta property="og:title" content="<?php echo $title_for_layout; ?>" />
<meta property="og:url" content="<?php  echo Router::url( $this->here, true ); ?>" />
<meta property="og:description" content="<?php echo $description_for_layout ?>" />
<meta property="og:image" content= "<?php echo $image_title; ?>" />
<meta property="fb:app_id" content="803156639786917" />
<!--meta google-->
<meta itemprop="name" content="<?php echo $title_for_layout; ?>" />
<meta itemprop="description" content="<?php echo $description_for_layout ?>" />
<meta itemprop="image" content="<?php echo $image_title; ?>" />
<!--meta index-->
<meta name="revisit-after" content="1 days" />
<meta name="RATING" content="GENERAL" />
<meta name="robots" content="index,follow" />
<meta name="Googlebot" content="index,follow,archive" />

<link href="<?php  echo Router::url( $this->here, true ); ?>" rel="canonical" />
<script type='text/javascript'>
    var BASE_URL = '<?php  echo Router::url( $this->here, true ); ?>'
</script>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-67897836-16', 'auto');
    ga('send', 'pageview');
    ga('create', 'UA-67897836-1', 'auto', 'funtap');
    ga('funtap.send', 'pageview');
</script>
<?php
echo $this->Html->script('/uncommon/all-js/jquery-1.10.2.min.js');
?>