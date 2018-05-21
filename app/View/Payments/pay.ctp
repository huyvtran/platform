<body>
<div class="container-fluid" style="max-width: 800px">
    <br/><br/>
    <div id="page-wrapper">
        <form  method="post" id="frmInvite" name="frmInvite">
            <center> <span style="color: red"><?= $this->Session->flash('error'); ?> </span></center><br/>
            <div class="form-group">
                <label class="col-xs-3 control-label" id="label-seri">card seria</label>
                <div class="col-xs-9">
                    <input type="text" class="form-control input-sm" name="card_serial" id="card_serial" value="" placeholder="Card Seri">
                    <p class="help-block" id="help-maseri"></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-3 control-label" id="label-PIN">card code</label>
                <div class="col-xs-9">
                    <input type="text" class="form-control input-sm" name="card_code" id="card_code" value="" placeholder="Card code">
                    <p class="help-block" id="help-mathe"></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-3 control-label" id="label-PIN">Price</label>
                <div class="col-xs-9">
                    <select name="card_price" id="CardManualStatus" class="form-control input-sm">
                        <option value=""> --- chose price ---</option>
                        <option value="10000">10.000 vnđ</option>
                        <option value="20000">20.000 vnđ</option>
                        <option value="50000">50.000 vnđ</option>
                        <option value="100000">100.000 vnđ</option>
                        <option value="200000">200.000 vnđ</option>
                        <option value="300000">300.000 vnđ</option>
                        <option value="500000">500.000 vnđ</option>
                    </select>
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
<!--                        <label class="btn btn-default class-type">-->
<!--                            <input type="radio" name="type" value="--><?php //echo Payment::TYPE_NETWORK_MOBIFONE ; ?><!--"> <img src="--><?php //echo $this->Html->url('/uncommon/payment/images/logo_mobi.png'); ?><!--" width="79px" height="39px">-->
<!--                        </label>-->
<!--                        <label class="btn btn-default class-type">-->
<!--                            <input type="radio" name="type" value="--><?php //echo Payment::TYPE_NETWORK_VINAPHONE ; ?><!--"> <img src="--><?php //echo $this->Html->url('/uncommon/payment/images/logo_vina.png'); ?><!--" width="79px" height="39px">-->
<!--                        </label>-->
<!--                        <label class="btn btn-default class-type">-->
<!--                            <input type="radio" name="type" value="--><?php //echo Payment::TYPE_NETWORK_GATE ; ?><!--"> <img src="--><?php //echo $this->Html->url('/uncommon/payment/images/logo_gate.png'); ?><!--" width="79px" height="39px">-->
<!--                        </label>-->
                    </div>
                    <p class="help-block" id="help-card">If there is a problem rechage by card, please contact admin fanpage</p>
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