<?php
$isiOS = false;
$MobileDetect = new Mobile_Detect();
if ($game['os'] == 'ios' || $MobileDetect->isiOS() ) {
    $isiOS = true;
}

?>
<body>
    <div class="container">
        <div class="row" align="center"><br/>
            <?php foreach ($products as $product){?>
                <?php
                    $link_inapp = $this->Html->url(array( 'controller' => 'Payments', 'action' => 'order',
                        '?' => array(
                            'app'   => $game['app'],
                            'token' => $token,
                            'productId' => $product['Product']['id']
                        )
                    ));
                    if($isiOS) $link_inapp = "javascript:AppSDKexecute('PaymentStartInapp', {plf_product_id: " . $product['Product']['id'] . "})";
                ?>
                <div class="col-xs-4">
                    <a href="<?php echo $link_inapp; ?>" class="btn btn-info btn-md" style="border: 1px #337ab7 solid !important; margin-top: 5px; width: 110px;">
                        <font color="yellow"><?php echo number_format($product['Product']['platform_price'], 0, '.', ','); ?> Coin</font><br/>
                        <font color="#ffd700"> <b>+ 10% </b></font>
                        <br/>
                        <i class="fa fa-diamond fa-2x"></i><br/>
                        <?php echo $product['Product']['price']; ?>$
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</body>