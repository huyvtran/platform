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
    <br/>
    <center> <span style="color: red"><?= $this->Session->flash('error'); ?> </span></center>
    <div id="page-wrapper">
        <?php
        echo $this->Form->create(false, array(
            'inputDefaults' => array(
                'class' => 'form-control input-sm',
                'div' => 'form-group',
                'between' => '<div class="col-xs-9">',
                'after' => '<p class="help-block"></p></div>',
            )
        ));
        echo $this->Form->input('card_serial', array(
            'class' => 'form-control input-sm',
            'type' => 'text',
            'label' => array(
                'text' => 'Card seria',
                'class' => 'col-xs-2 control-label'
            )
        ));

        echo $this->Form->input('card_code', array(
            'class' => 'form-control input-sm',
            'type' => 'text',
            'label' => array(
                'text' => 'Card code',
                'class' => 'col-xs-2 control-label'
            )
        ));

        echo $this->Form->input('card_price', array(
            'class' => 'form-control input-sm',
            'type' => 'select',
            'label' => array(
                'text' => 'Price',
                'class' => 'col-xs-2 control-label'
            ),
            'empty' => '--- Chose price ---',
            'options' => array(
                10000 => '10.000 vnđ',
                20000 => '20.000 vnđ',
                50000 => '50.000 vnđ',
                100000 => '100.000 vnđ',
                200000 => '200.000 vnđ',
                300000 => '300.000 vnđ',
                500000 => '500.000 vnđ',
            ),
        ));
        ?>

        <div class="form-group">
            <label class="col-xs-2 control-label">Type</label>
            <div class="col-xs-9">
                <div class="btn-group" data-toggle="buttons">
<!--                    <label class="btn btn-default class-type">-->
<!--                        <input type="radio" name="type" value="--><?php //echo Payment::TYPE_NETWORK_VIETTEL ; ?><!--"> <img src="--><?php //echo $this->Html->url('/uncommon/payment/images/logo_vtel.png'); ?><!--" width="79px" height="39px">-->
<!--                    </label>-->
                    <label class="btn btn-default class-type">
                        <input type="radio" name="type" value="<?php echo Payment::TYPE_NETWORK_VINAPHONE ; ?>"> <img src="<?php echo $this->Html->url('/uncommon/payment/images/logo_vina.png'); ?>" width="79px" height="39px">
                    </label>
<!--                    <label class="btn btn-default class-type">-->
<!--                        <input type="radio" name="type" value="--><?php //echo Payment::TYPE_NETWORK_MOBIFONE ; ?><!--"> <img src="--><?php //echo $this->Html->url('/uncommon/payment/images/logo_mobi.png'); ?><!--" width="79px" height="39px">-->
<!--                    </label>-->
<!--                    <label class="btn btn-default class-type">-->
<!--                        <input type="radio" name="type" value="--><?php //echo Payment::TYPE_NETWORK_GATE ; ?><!--"> <img src="--><?php //echo $this->Html->url('/uncommon/payment/images/logo_gate.png'); ?><!--" width="79px" height="39px">-->
<!--                    </label>-->
                </div>
                <p class="help-block" id="help-card">If there is a problem rechage by card, please contact admin fanpage</p>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-offset-2 col-xs-9">
                <button type="submit" class="btn btn-primary">Submit</button>
                <p class="help-block" id="help-card"></p>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div><br/><br/>
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