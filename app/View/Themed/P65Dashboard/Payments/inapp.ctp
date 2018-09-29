<?php
$isiOS = false;
$MobileDetect = new Mobile_Detect();
if ($currentGame['os'] == 'ios' || $MobileDetect->isiOS() ) {
    $isiOS = true;
}
$area_id = $role_id = 1;
?>
<div class="wrapper">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="nav navbar-left">
                <a href="<?php echo $this->Html->url(array( 'controller' => 'Payments', 'action' => 'index',
                    '?' => array(
                        'app'   => $currentGame['app'],
                        'token' => $token,
                        'role_id'   => $role_id,
                        'area_id'   => $area_id
                    )
                )); ?>"><i class="fa fa-chevron-left fa-2x"></i>
                </a>
            </div>

            <?php echo __('Nạp từ store'); ?>

            <div class="nav navbar-right">
            </div>
        </div>
    </nav>
    <div class="clearfix"></div>

    <ul class="crumbs list-unstyled">
        <li> <?php echo __('Cách nạp'); ?> </li>
        <li class="active"> <?php echo __('Chọn gói'); ?> </li>
        <li><?php echo __('Hoàn thành'); ?></li>
    </ul>
    <div class="clearfix"></div>

    <div class="container page-wrapper">
        <ul class="package list-unstyled">
            <?php if( !empty($products) ){?>
                <?php foreach ($products as $product){?>
                    <?php $link_inapp = $this->Html->url(array( 'controller' => 'Payments', 'action' => 'order',
                        '?' => array(
                            'app'   => $currentGame['app'],
                            'token' => $token,
                            'role_id'   => $role_id,
                            'area_id'   => $area_id,
                            'plf_product_id' => $product['Product']['id']
                        )
                    ));
                    if($isiOS) $link_inapp = "javascript:AppSDKexecute('PaymentStartInapp', {plf_product_id: " . $product['Product']['id'] . "})";
                    ?>

                    <li>
                        <img src="/payment/images/apple-store.png" alt="">
                        <a href="<?= $link_inapp ?>">
                            <?php echo $product['Product']['price']; ?> $
                            <span><?php echo number_format($product['Product']['platform_price'], 0, '.', ','); ?> Coin</span>
                        </a>
                    </li>
                <?php } ?>
            <?php } ?>

            
        </ul>
    </div>
</div>