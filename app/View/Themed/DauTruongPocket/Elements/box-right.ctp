<div class="community-desktop">
    <div class="clearfix">
        <a href="<?php echo $this->Html->url(array("controller" => "articles","action" => "view","category"=> 'guides',"slug"=>'dac-quyen-vip')); ?>" class="vip sprite" title="VIP">Vip</a>
        <a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index','slug'=>'guides')); ?>" class="guide sprite" title="Hướng dẫn">Hướng dẫn</a>
    </div>
    <a href="mailto:hotro@funtap.vn" class="sprite support" title="Hỗ trợ">Hỗ trợ</a>
</div>
<div class="community-mobile">
    <a href="<?php echo $this->Html->url(array("controller" => "articles","action" => "view","category"=> 'features',"slug"=>'dac-quyen-vip')); ?>" class="vip">
        <img src="<?php echo $this->Html->url('/') ?>uncommon/dautruongpk/images/vip.jpg" alt="VIP">
    </a>
    <a href="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'index','slug'=>'guides')); ?>" class="guide">
        <img src="<?php echo $this->Html->url('/') ?>uncommon/dautruongpk/images/guide.jpg" alt="Hướng dẫn">
    </a>
    <a href="mailto:hotro@funtap.vn" class="support"><img src="<?php echo $this->Html->url('/') ?>uncommon/dautruongpk/images/support.jpg" alt="Hỗ trợ"></a>
</div>