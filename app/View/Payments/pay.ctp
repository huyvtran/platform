<body>
<div class="container-fluid" style="max-width: 800px">
    <br/><br/>
    <div id="page-wrapper">
		<center><span style="color: red;"><?php echo "Note: Mobifone network is maintainning"; ?></span></center>
        <form  method="post" id="frmInvite" name="frmInvite">
            <div class="form-group">
                <label class="col-xs-3 control-label" id="label-seri">Seri thẻ</label>
                <div class="col-xs-9">
                    <input type="text" class="form-control input-sm" name="card_serial" id="card_serial" value="" placeholder="Seri thẻ">
                    <p class="help-block" id="help-maseri"></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-3 control-label" id="label-PIN">Mã PIN</label>
                <div class="col-xs-9">
                    <input type="text" class="form-control input-sm" name="card_code" id="card_code" value="" placeholder="Mã PIN">
                    <p class="help-block" id="help-mathe"></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-3 control-label">Loại thẻ</label>
                <div class="col-xs-9">
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default class-type">
                            <input type="radio" name="type" value="<?php echo Payment::TYPE_NETWORK_VIETTEL ; ?>"> <img src="<?php echo $this->Html->url('/uncommon/payment/images/logo_vtel.png'); ?>" width="79px" height="39px">
                        </label>
                        <!--<label class="btn btn-default class-type">
                            <input type="radio" name="type" value="<?php echo Payment::TYPE_NETWORK_MOBIFONE ; ?>"> <img src="<?php echo $this->Html->url('/uncommon/payment/images/logo_mobi.png'); ?>" width="79px" height="39px">
                        </label>-->
                        <label class="btn btn-default class-type">
                            <input type="radio" name="type" value="<?php echo Payment::TYPE_NETWORK_VINAPHONE ; ?>"> <img src="<?php echo $this->Html->url('/uncommon/payment/images/logo_vina.png'); ?>" width="79px" height="39px">
                        </label>

                    </div>
                    <p class="help-block" id="help-card">Nếu có vấn đề về nạp thẻ, vui lòng liên hệ Facebook<br> Ghi rõ tên tài khoản, seri thẻ</p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-offset-3 col-xs-9">
                    <button type="submit" class="btn btn-primary">Nạp thẻ</button>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-offset-3 col-xs-9" id="alertResult"></div>
            </div>
        </form>
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