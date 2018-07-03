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

            <?php echo __('Nạp từ Banking'); ?>

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
        <div class="row" align="center" >
    <!--        &nbsp;&nbsp;<span style="color: red;">Note: payment via Banking will reduce 15% coins</span>
            <span style="color: red;">Note: get 100% coins when recharge via Banking</span>-->
        </div><br/>
        <ul class="package list-unstyled">
        <?php if( !empty($products) ){ ?>
            <?php foreach ($products as $product){?>
                <li>
                    <img src="/payment/images/sms.png" alt="">
                    <a href="<?php echo $this->Html->url(array( 'controller' => 'OvsPayments', 'action' => 'pay_paymentwall_order',
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
    <footer class="container-fluid text-center bg-lightgray">
        <div class="copyrights" style="margin-top:25px;">
            COPYRIGHT FunGame Inc. Global Digital Entertainment Leader<br/>
        </div>
    </footer>
    </div>
</div>