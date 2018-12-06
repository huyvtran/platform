<?php $role_id = $area_id = 1; ?>
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
                )); ?>"><i class="fa fa-chevron-left fa-2x"></i></a>
            </div>

            <?php echo __('Nạp từ Paypal'); ?>

            <div class="nav navbar-right">
            </div>
        </div>
    </nav>
    <div class="clearfix"></div>

    <ul class="crumbs list-unstyled">
        <li> <?php echo __('Phương thức nạp'); ?></li>
        <li class="active">
            <?php echo __('Chọn gói'); ?>
        </li>
        <li><?php echo __('Hoàn thành'); ?></li>
    </ul>
    <div class="clearfix"></div>

    <div class="container page-wrapper">
        <ul class="package list-unstyled">
            <?php
            App::import('Lib', 'RedisCake');
            $Redis2 = new RedisCake('action_count');
            $paypal_enable = $Redis2->get('payment-paypal-enable');
            $paypal_enable = 1;
            ?>
            <?php if( !empty($products) && !empty($paypal_enable) ){ ?>
                <?php foreach ($products as $product){?>
                <li>
                    <img src="/payment/images/paypal.png" alt="">
                    <a href="<?php echo $this->Html->url(array( 'controller' => 'OvsPayments', 'action' => 'pay_paypal_order',
                        '?' => array(
                            'app'   => $game['app'],
                            'token' => $token,
                            'productId' => $product['Product']['id']
                        )
                    )); ?>">
                        <?php echo $product['Product']['price']; ?> $
                        <span><?php echo number_format($product['Product']['description'], 0, '.', ','); ?> <i class="fa fa-1x fa-fw fa-diamond"></i></span>
                    </a>
                </li>
                <?php } ?>
            <?php }else{ ?>
                <div class="alert alert-success font-small" style="color: black">
                    The recharge system is maintaining
                </div>
            <?php } ?>
        </ul>
    </div>
</div>