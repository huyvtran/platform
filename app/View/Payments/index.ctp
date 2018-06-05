<?php
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
            <?php echo __('Nạp thẻ'); ?>
        </div>
        <div class="toolbar-right">
            <a href="#" onclick="document.location = 'js-oc:kunlunClose:null';return false">
                <i class="fa fa-times fa-lg" aria-hidden="true"></i>
            </a>
        </div>
    </div>
    <div class="container">
        <div class="row" align="center">
            <?php foreach ($products as $product){?>
                <div class="col-xs-4">
                    <a href="<?php echo $this->Html->url(array( 'controller' => 'Payments', 'action' => 'inapp',
                        '?' => array(
                            'app'   => $game['app'],
                            'token' => $token,
                            'productId' => $product['Product']['id']
                        )
                    )); ?>" class="btn btn-info btn-md" style="border: 1px #337ab7 solid !important; margin-top: 5px; width: 110px;">
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