<body>
<div class="toolbar">
    <div class="toolbar-left">
        <a href="<?php echo $currentGame['data']['payment']['url_sdk']; ?>"><i class="fa fa-home fa-lg" aria-hidden="true"></i></a>
    </div>
    <div class="toolbar-brand">
        <?php echo 'Paypal'; ?>
    </div>
    <div class="toolbar-right">
        <a href="#" onclick="document.location = 'js-oc:kunlunClose:null';return false">
            <i class="fa fa-times fa-lg" aria-hidden="true"></i>
        </a>
    </div>
</div>
<div class="container">
    <?php
    App::import('Lib', 'RedisCake');
    $Redis2 = new RedisCake('action_count');
    $paypal_enable = $Redis2->get('payment-paypal-enable');
    ?>
    <?php if( !empty($products) ){ ?>
        <div class="row" align="center">
            <!-- <div class="alert alert-success font-small" style="color: black">
                Note: receive 120% coins when recharge via Paypal
            </div> -->
            <?php foreach ($products as $product){?>
                <div class="col-xs-4">
                    <a href="<?php echo $this->Html->url(array( 'controller' => 'OvsPayments', 'action' => 'pay_paypal_order',
                        '?' => array(
                            'app'   => $game['app'],
                            'token' => $token,
                            'productId' => $product['Product']['id']
                        )
                    )); ?>" class="btn btn-info btn-md" style="border: 1px #337ab7 solid !important; margin-top: 5px;">
                        <font color="yellow"><b><?php echo number_format($product['Product']['description'], 0, '.', ','); ?> Coin</b></font><br/>
                        <i class="fa fa-diamond fa-2x"></i><br/>
                        <?php echo $product['Product']['price']; ?>$
                    </a>
                </div>
            <?php } ?>
        </div>
    <?php }else{ ?>
        <div class="alert alert-success font-small" style="color: black">
            The recharge system is maintaining
        </div>
    <?php } ?>
</div>
</div>
</body>