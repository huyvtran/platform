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

            <?php echo __('Nạp từ Visa/Master'); ?>

            <div class="nav navbar-right">
                <a href="#" onclick="document.location = 'js-oc:kunlunClose:null';return false"><i class="fa fa-close fa-2x"></i></a>
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

    <ul class="crumbs list-unstyled">
    </ul>

    <div class="container page-wrapper">
        <ul class="package list-unstyled">
        <?php if( !empty($products) ){ ?>
            <?php foreach ($products as $product){?>
                <li>
                    <img src="/payment/images/credit-card.png" alt="">
                    <a href="<?php echo $this->Html->url(array( 'controller' => 'OvsPayments', 'action' => 'pay_ale_order',
                        '?' => array(
                            'app'   => $game['app'],
                            'token' => $token,
                            'productId' => $product['Product']['id']
                        )
                    )); ?>">
                        <?php echo $product['Product']['price']; ?> $
                        <span><?php echo number_format($product['Product']['platform_price'], 0, '.', ','); ?> Coin</span>
                    </a>
                </li>
            <?php } ?>
        <?php } ?>
        </ul>
    </div>
</div>
