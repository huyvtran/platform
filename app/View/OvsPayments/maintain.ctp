<?php
$back_url = "#";
if(!empty($currentGame['data']['payment']['url_sdk'])){
    $back_url = $currentGame['data']['payment']['url_sdk'];
}
?>
<body>
<div class="toolbar">
    <div class="toolbar-left">
        <a href="<?php echo $back_url; ?>"><i class="fa fa-home fa-lg" aria-hidden="true"></i></a>
    </div>
    <div class="toolbar-brand">
        <?php echo 'Banking (visa, master)'; ?>
    </div>
    <div class="toolbar-right">
        <a href="#" onclick="document.location = 'js-oc:kunlunClose:null';return false">
            <i class="fa fa-times fa-lg" aria-hidden="true"></i>
        </a>
    </div>
</div>
<div class="container">
    <div class="row" align="center">
        <h3 style="text-align: center">The system is maintain, please come back later.</h3>
    </div>
</div>
</div>
</body>