<?php
$role_id = $area_id = 1;
?>
<div class="wrapper">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="nav navbar-left">
                <a href="<?php echo $this->Html->url(array( 'controller' => 'Payments', 'action' => 'index',
                    '?' => array(
                        'app'   => $currentGame['app'],
                        'token' => $token,
                        'role_id'   => $role_id,
                        'area_id'   => $area_id
                    )
                )); ?>"><i class="fa fa-chevron-left fa-2x"></i></a>
            </div>

            <?php echo __('Nạp qua thẻ cào'); ?>

            <div class="nav navbar-right">
            </div>
        </div>
    </nav>
    <div class="clearfix"></div>

    <ul class="crumbs list-unstyled">
        <li> <?php echo __('Cách nạp'); ?></li>
        <li class="active">
            <?php echo __('Chọn gói'); ?>
        </li>
        <li><?php echo __('Hoàn thành'); ?></li>
    </ul>
    <div class="clearfix"></div>

    <?= $this->Element('user_info'); ?>

    <div class="container page-wrapper">
        <?php if(!empty($currentGame['data']['payment']['notice'])){ ?>
            <center><span style="color: red;"><?= $currentGame['data']['payment']['notice']; ?></span></center><br/>
        <?php } ?>

        <?php if(!empty($this->Session->flash('payment'))){ ?>
        <center><span style="color: red;"><?= $this->Session->flash('payment'); ?></span></center><br/>
        <?php } ?>

        <?php
            $card_code = $card_serial = '';
            if( !empty($this->request->data['card_code']) ) $card_code = $this->request->data['card_code'];
            if( !empty($this->request->data['card_serial']) ) $card_serial = $this->request->data['card_serial'];
        ?>
        <form  method="post" id="frmInvite" name="frmInvite">
            <div class="form-group">
                <label class="col-xs-3 control-label" id="label-seri"><?php echo __('Seri thẻ'); ?></label>
                <div class="col-xs-9">
                    <input type="text" class="form-control input-sm" name="card_serial" id="card_serial" value="<?= $card_serial ?>" placeholder="Seri thẻ">
                    <p class="help-block" id="help-maseri"></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-3 control-label" id="label-PIN"><?php echo __('Mã pin'); ?></label>
                <div class="col-xs-9">
                    <input type="text" class="form-control input-sm" name="card_code" id="card_code" value="<?= $card_code ?>" placeholder="Mã PIN">
                    <p class="help-block" id="help-mathe"></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-3 control-label"><?php echo __('Loại thẻ'); ?></label>
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
                        <label class="btn btn-default class-type">
                            <input type="radio" name="type" value="<?php echo Payment::TYPE_NETWORK_GATE ; ?>"> <img src="<?php echo $this->Html->url('/uncommon/payment/images/logo_gate.png'); ?>" width="79px" height="39px">
                        </label>
                    </div>
                    <p class="help-block" id="help-card"><?php echo __('Nếu có vấn đề về nạp thẻ, vui lòng liên hệ Facebook<br> Ghi rõ tên tài khoản, seri thẻ</p>'); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-offset-3 col-xs-9">
                    <button type="submit" class="btn btn-primary"><?php echo __('Nạp thẻ'); ?></button>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-offset-3 col-xs-9" id="alertResult"></div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.class-type').on('click', function (e) {
            $('.class-type').css('background-color', 'white');
            $(this).css('background-color', 'wheat');
        });
    });
</script>