<style>
    .fa-image-bank{
        background-image: url("/uncommon/payment/images/logo_banking.png");
        width: 128px;
        height: 128px;
    }
    .fa-image-card{
        background-image: url("/uncommon/payment/images/logo_card.png");
        width: 128px;
        height: 128px;
    }
</style>
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
            &nbsp;&nbsp;<span style="color: red;">Note: payment via Mobiamo will reduce 50% coins</span><br/>
            &nbsp;&nbsp;<span style="color: red;">Note: payment via Banking will reduce 15% coins</span>
        </div><br/>

        <center>
            <div class="col-xs-6">
                <a href="<?php echo $this->Html->url(array( 'controller' => 'OvsPayments', 'action' => 'pay_paymentwall_bank',
                    '?' => array(
                    'app'   => $game['app'],
                    'token' => $token
                    )
                    )); ?>" class="btn btn-info btn-md" style="border: 1px #337ab7 solid !important; margin-top: 5px;">
                    <i class="fa fa-image-bank"></i>
                    <br/>Banking
                </a>
            </div>

            <div class="col-xs-6">
                <a href="<?php echo $this->Html->url(array( 'controller' => 'OvsPayments', 'action' => 'pay_paymentwall_card',
                    '?' => array(
                    'app'   => $game['app'],
                    'token' => $token
                    )
                    )); ?>" class="btn btn-info btn-md" style="border: 1px #337ab7 solid !important; margin-top: 5px;">
                    <i class="fa fa-image-card"></i>
                    <br/>Card
                </a>
            </div>
        </center>
    </div>
    <footer class="container-fluid text-center bg-lightgray">
        <div class="copyrights" style="margin-top:25px;">
            COPYRIGHT FunGame Inc. Global Digital Entertainment Leader<br/>
        </div>
    </footer>

</div>
</body>