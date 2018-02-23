<body>
<div class="toolbar">
    <div class="toolbar-left">
        <a href="<?php echo $currentGame['data']['payment']['url_sdk']; ?>"><i class="fa fa-home fa-lg" aria-hidden="true"></i></a>
    </div>
    <div class="toolbar-brand"> </div>
    <div class="toolbar-right">
        <a href="#" onclick="document.location = 'js-oc:kunlunClose:null';return false">
            <i class="fa fa-times fa-lg" aria-hidden="true"></i>
        </a>
    </div>
</div>
<div class="container">
    <div class="row" align="center" >
        &nbsp;&nbsp;<span style="color: red;">Note: payment via Banking will reduce 15% coins</span>
<!--        &nbsp;&nbsp;<span style="color: red;">Note: get 100% coins when recharge via Banking</span> -->
    </div><br/>
    <?php if( !empty($products) ){ ?>
        <div class="row" align="center">
            <?php foreach ($products as $product){?>
                <div class="col-xs-4">
                    <a href="<?php echo $this->Html->url(array( 'controller' => 'OvsPayments', 'action' => 'pay_paymentwall_order',
                        '?' => array(
                            'app'   => $game['app'],
                            'token' => $token,
                            'productId' => $product['Product']['id']
                        )
                    )); ?>" class="btn btn-info btn-md" style="border: 1px #337ab7 solid !important; margin-top: 5px; width: 110px;">
                        <font color="yellow"><b><?php echo number_format($product['Product']['platform_price'], 0, '.', ','); ?> Coin</b></font><br/>
                        <i class="fa fa-diamond fa-2x"></i><br/>
                        <?php echo $product['Product']['price']; ?>$
                    </a>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>
<footer class="container-fluid text-center bg-lightgray">
    <div class="copyrights" style="margin-top:25px;">
        COPYRIGHT FunGame Inc. Global Digital Entertainment Leader<br/>
    </div>
</footer>

</div>
</body>