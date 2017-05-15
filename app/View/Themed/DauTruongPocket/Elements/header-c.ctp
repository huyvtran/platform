<header id="header">
    <div class="container">
        <ul class="list-unstyled main-nav">
            <li><a href="<?php echo $this->Html->url('/home') ?>">Trang chủ</a></li>
            <li class="logo">
                <h1> 
                    <a class="" href="<?php echo $this->Html->url('/home') ?>" title="<?php echo isset($currentGame['title'])?$currentGame['title']:''; ?>">
                        <img src="<?php echo $this->Html->url('/') ?>uncommon/dautruongpk/images/logo.png" class="img-fix" alt="<?php echo isset($currentGame['title'])?$currentGame['title']:''; ?>">
                    </a>
                </h1>
            </li>
            <li><a href="https://www.facebook.com/dautruongmegaxy/">Fanpage</a></li>
        </ul>
    </div>
</header>