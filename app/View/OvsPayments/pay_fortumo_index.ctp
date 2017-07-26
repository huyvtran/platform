<script src='https://assets.fortumo.com/fmp/fortumopay.js' type='text/javascript'></script>
<body>
    <div class="toolbar">
        <div class="toolbar-left">
            <a href="<?php echo $currentGame['data']['payment']['url_sdk']; ?>"><i class="fa fa-home fa-lg" aria-hidden="true"></i></a>
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
        <a id="fmp-button" href="#" rel="d0161fc335d3d6193e7b023078aa39e2/<?php echo 'app='.$game['app'] . '&token='. $token ?>">
            <img src="https://assets.fortumo.com/fmp/fortumopay_150x50_red.png" width="150" height="50" alt="Mobile Payments by Fortumo" border="0" />
        </a>
    </div>
</div>
</body>