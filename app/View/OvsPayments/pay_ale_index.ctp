<?php
    /*
    $is_open = false;
    if( in_array($this->Session->read('Auth.User.id'), array(19054))
        || in_array($currentGame, array(37, 38, 45, 46, 49, 50))
    ) $is_open = true;
    */
    $is_open = true;

    $url_sdk = "";
    if( !empty($currentGame['data']['payment']['url_sdk']) ) {
        $url_sdk = $currentGame['data']['payment']['url_sdk'];
    }
?>
<body>
    <div class="toolbar">
        <div class="toolbar-left">
            <a href="<?php echo $url_sdk; ?>"><i class="fa fa-home fa-lg" aria-hidden="true"></i></a>
        </div>
        <div class="toolbar-brand">
            <?php echo 'Visa/Master Card'; ?>
        </div>
        <div class="toolbar-right">
            <a href="#" onclick="document.location = 'js-oc:kunlunClose:null';return false">
                <i class="fa fa-times fa-lg" aria-hidden="true"></i>
            </a>
        </div>
    </div>
    <div class="container">
        <?php if( !empty($products) ){ ?>
            <div class="row" align="center">

                <!--      thông báo bảo trì          -->
                <?php if( !$is_open ){ ?>
                <div class="alert alert-success font-small" style="color: black">
                    The recharge system is maintaining
                </div>
                <?php } ?>

                <!--      mở cổng thanh toán 1pay          -->
                <?php if( $is_open ){ ?>
                <div class="alert alert-success font-small" style="color: black">
                    Bonus <font color="red">30%</font> coin when recharge via <span class="text-danger">Visa/Master Card</span>
                </div>

                <?php foreach ($products as $product){?>
                    <div class="col-xs-4">
                        <a href="<?php echo $this->Html->url(array( 'controller' => 'OvsPayments', 'action' => 'pay_ale_order',
                            '?' => array(
                                'app'   => $game['app'],
                                'token' => $token,
                                'productId' => $product['Product']['id']
                            )
                        )); ?>" class="btn btn-info btn-md" style="border: 1px #337ab7 solid !important; margin-top: 5px; width: 110px;">
                            <font color="yellow"><b><?php echo number_format($product['Product']['platform_price'], 0, '.', ','); ?> Coin </b> <br/> + 30%</font><br/>
                            <i class="fa fa-diamond fa-2x"></i><br/>
                            <?php echo $product['Product']['price']; ?>$
                        </a>
                    </div>
                <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>
</body>