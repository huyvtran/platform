<body>
<div class="container-fluid" style="max-width: 800px">
    <br/><br/>
    <div id="page-wrapper">
        <div class="col-xs-9">
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-default class-type">
                    <input type="radio" name="type" value="<?php echo Payment::TYPE_NETWORK_VIETTEL ; ?>"> <img src="<?php echo $this->Html->url('/uncommon/payment/images/logo_vtel.png'); ?>" width="79px" height="39px">
                </label>
                <label class="btn btn-default class-type">
                    <input type="radio" name="type" value="<?php echo Payment::TYPE_NETWORK_MOBIFONE ; ?>"> <img src="<?php echo $this->Html->url('/uncommon/payment/images/logo_mobi.png'); ?>" width="79px" height="39px">
                </label>
                <label class="btn btn-default class-type">
                    <input type="radio" name="type" value="<?php echo Payment::TYPE_NETWORK_VINAPHONE ; ?>"> <img src="<?php echo $this->Html->url('/uncommon/payment/images/logo_vina.png'); ?>" width="79px" height="39px">
                </label>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    $(document).ready(function () {
        $('.class-type').on('click', function (e) {
            $('.class-type').css('background-color', 'white');
            $(this).css('background-color', 'wheat');
        });
    });
</script>