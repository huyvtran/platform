<body>
<!--<section id="wrapper">-->
<!--    <div class="input-text">-->
<!--        <aside class="payment-banking">-->
<!--            <p class="desc"><strong>Thanh toán bằng thẻ cào</strong></p>-->
<!--        </aside>-->
<!---->
<!--        <form  method="post" id="frmInvite" name="frmInvite">-->
<!--            <label for="card_type" class="required">Loại thẻ</label>-->
<!--            <div class="input-box">-->
<!--                <select id="card_type" name="type">-->
<!--                    <option selected="selected">-- Chọn loại thẻ --</option>-->
<!--                    <option value="--><?php //echo Payment::TYPE_NETWORK_VIETTEL ; ?><!--">Viettel</option>-->
<!--                    <option value="--><?php //echo Payment::TYPE_NETWORK_MOBIFONE ; ?><!--">Mobifone</option>-->
<!--                    <option value="--><?php //echo Payment::TYPE_NETWORK_VINAPHONE ; ?><!--">Vinaphone</option>-->
<!--                </select>-->
<!--            </div>-->
<!---->
<!--            <div class="fieldset">-->
<!--                <ul class="form-list">-->
<!--                    <li>-->
<!--                        <div class="field">-->
<!--                            <label for="card_code" class="required">Mã thẻ</label>-->
<!--                            <div class="input-box">-->
<!--                                <input type="text" name="card_code" id="card_code" />-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <div class="field">-->
<!--                            <label for="serial_number" class="required">Seri number</label>-->
<!--                            <div class="input-box">-->
<!--                                <input type="text" name="card_serial" id="serial_number" />-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </li>-->
<!--                    --><?php //if (isset($error_mess)) {
//                        echo "<li>";
//                        echo '<div class="field message">';
//                        echo '<span class="msg-error">' . $error_mess . '</span>';
//                        echo "</div>";
//                        echo "</li>";
//                    } ?>
<!--                    <li>-->
<!--                        <div class="form-button">-->
<!--                            <button class="button btn-common btn-next" type="submit"><span>Thanh toán</span></button>-->
<!--                        </div>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </div>-->
<!--        </form>-->
<!--    </div>-->
<!--</section>-->
<!---->
<!---->


<div class="container-fluid" style="max-width: 800px">
    <div id="page-wrapper">
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
                        <label class="btn btn-default">
                            <input type="radio" name="type" value="<?php echo Payment::TYPE_NETWORK_VIETTEL ; ?>"> <img src="/uncommon/payment/images/logo_vtel.png" width="79px" height="39px">
                        </label>
                        <label class="btn btn-default">
                            <input type="radio" name="type" value="<?php echo Payment::TYPE_NETWORK_MOBIFONE ; ?>"> <img src="/uncommon/payment/images/logo_mobi.png" width="79px" height="39px">
                        </label>
                        <label class="btn btn-default">
                            <input type="radio" name="type" value="<?php echo Payment::TYPE_NETWORK_VINAPHONE ; ?>"> <img src="/uncommon/payment/images/logo_vina.png" width="79px" height="39px">
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
