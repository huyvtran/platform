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
    <div id="page-wrapper"><br/>
        <?php
        if( empty($disable[Payment::TYPE_NETWORK_VIETTEL][0]['status'])
            && empty($disable[Payment::TYPE_NETWORK_ZING][0]['status'])
        ){
            ?>
            <div class="alert alert-danger">
                The system is maintain, please come back later. <br/>
            </div>
        <?php }else{
            $img50k = $this->Html->url('/payment/images/vt50k.png');
            $img100k = $this->Html->url('/payment/images/vt100k.png');
            $img200k = $this->Html->url('/payment/images/vt200k.png');
            $img300k = $this->Html->url('/payment/images/vt300k.png');
            $img500k = $this->Html->url('/payment/images/vt500k.png');
            ?>

            <center> <span style="color: red"><?= $this->Session->flash('error'); ?> </span></center>
            <?php
            echo $this->Form->create(false, array(
                'name' => 'frmInvite',
                'id' => 'frmInvite',
                'class' => 'form-horizontal',
                'inputDefaults' => array(
                    'class' => 'form-control input-sm',
                    'div' => 'form-group',
                    'between' => '<div class="col-md-10">',
                    'after' => '<p class="help-block"></p></div>',
                )
            ));

            echo $this->Form->input('card_serial', array(
                'class' => 'form-control input-sm',
                'type' => 'text',
                'label' => array(
                    'text' => 'Serial',
                    'class' => 'col-md-2 control-label'
                )
            ));

            echo $this->Form->input('card_code', array(
                'class' => 'form-control input-sm',
                'type' => 'text',
                'label' => array(
                    'text' => 'Cardcode',
                    'class' => 'col-md-2 control-label'
                )
            ));
            ?>

            <div class="form-group">
                <label class="col-md-2 control-label">Price</label>
                <div class="col-md-10">
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default class-type" style="border-color: white">
                            <input type="radio" name="data[card_price]" value="50000"/>
                            <img src="<?php echo $img50k ?>" width="80px" height="60px" />
                        </label>

                        <label class="btn btn-default class-type" style="border-color: white">
                            <input type="radio" name="data[card_price]" value="100000" />
                            <img src="<?php echo $img100k; ?>" width="80px" height="60px" />
                        </label>

                        <label class="btn btn-default class-type" style="border-color: white">
                            <input type="radio" name="data[card_price]" value="200000" />
                            <img src="<?php echo $img200k; ?>" width="80px" height="60px" />
                        </label>

                        <label class="btn btn-default class-type" style="border-color: white">
                            <input type="radio" name="data[card_price]" value="300000" />
                            <img src="<?php echo $img300k; ?>" width="80px" height="60px" />
                        </label>

                        <label class="btn btn-default class-type" style="border-color: white">
                            <input type="radio" name="data[card_price]" value="500000" />
                            <img src="<?php echo $img500k; ?>" width="80px" height="60px" />
                        </label>
                    </div>
                </div>
            </div>

            <?php if( empty($this->request->query('type')) ){ ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">Type</label>
                    <div class="col-xs-9">
                        <div class="btn-group" data-toggle="buttons">
                            <?php if( !empty($disable[Payment::TYPE_NETWORK_VIETTEL][0]['status'])){ ?>
                                <label class="btn btn-default class-type">
                                    <input type="radio" name="type" value="<?php echo Payment::TYPE_NETWORK_VIETTEL ; ?>"> <img src="<?php echo $this->Html->url('/uncommon/payment/images/logo_vtel.png'); ?>" width="79px" height="39px">
                                </label>
                            <?php } ?>

                            <?php if( !empty($disable[Payment::TYPE_NETWORK_ZING][0]['status'])){ ?>
                                <label class="btn btn-default class-type">
                                    <input type="radio" name="type" value="<?php echo Payment::TYPE_NETWORK_ZING ; ?>"> <img src="<?php echo $this->Html->url('/uncommon/payment/images/logo_mobi.png'); ?>" width="79px" height="39px">
                                </label>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="form-group">
                <div class="col-md-offset-2 col-md-9">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        <?php } ?>
    </div>
</div>
</body>

<div class="overlay"></div>
<div class="modal fade" id="content-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <center><h4 class="modal-title"><?php echo __("Chú ý") ?></h4></center>
            </div>
            <div id="wysiwyg-content">
                <p>Bạn vừa chọn nạp mệnh giá thẻ <strong>50.000</strong>đ.</p>
                <p>Chọn sai mệnh giá thẻ sẽ bị mất không hoàn trả.</p>
            </div>
        </div>
    </div>
</div>

<style>
    .overlay { display: none; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); position: fixed; top: 0; left: 0; z-index: 5; }
    .modal-content { padding : 0 10px; background : wheat; overflow : hidden;}
    .modal-header{border-bottom: 1px solid}
    .close { font-size : 27px;}
    #wysiwyg-content { font-size : 16px; padding : 5px 5px; font-weight : 300; }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $('.class-type').on('click', function (e) {
            var price = $(this).find('input').val();
            $('.class-type').css('background-color', 'white');
            $(this).css('background-color', 'wheat');

            var str = "<p>Bạn vừa chọn nạp mệnh giá thẻ <strong>" + Number(price).toLocaleString("en") + "</strong> đ.</p>";
            str += "<p>Chọn sai mệnh giá thẻ sẽ bị mất không hoàn trả.</p>";
            $( "#wysiwyg-content" ).html( str );
            $('#content-modal').modal('show');
        });
    });
</script>