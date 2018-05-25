<body>
<?php
$is_open = true;

$url_sdk = "";
if( !empty($currentGame['data']['payment']['url_sdk']) ) {
    $url_sdk = $currentGame['data']['payment']['url_sdk'];
}
?>
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

<div class="container-fluid" style="max-width: 800px">
    <div id="page-wrapper">
        <br/>
        <h4>Cards will be checked and send coin between 10h - 22h (GMT +8) </h4> <br/>
        <h4>Your card will be checked, the coin will add to the account when the check is complete. (max time for delay is 24h)</h4>
    </div>
</div>
</body>