<body>
<section id="wrapper">
    <div class="input-text">
        <aside class="payment-banking">
            <p class="desc"><strong>Thanh toán bằng thẻ cào</strong></p>
        </aside>

        <form  action="" method="post" id="frmInvite" name="frmInvite">
            <label for="card_type" class="required">Loại thẻ</label>
            <div class="input-box">
                <select id="card_type" name="vpc_Bank">
                    <option selected="selected">-- Chọn loại thẻ --</option>
                    <option value="VIETTEL">Viettel</option>
                    <option value="VMS">Mobifone</option>
                    <option value="VNP">Vinaphone</option>
                    <option value="VCOIN">VCOIN</option>
                    <option value="GATE">GATE</option>
                    <option value="ZING">ZING</option>
                </select>
            </div>

            <div class="fieldset">
                <ul class="form-list">
                    <li>
                        <div class="field">

                        </div>
                    </li>
                    <li>
                        <div class="field">
                            <label for="card_code" class="required">Mã thẻ</label>
                            <div class="input-box">
                                <input type="text" name="card_number" id="card_code" />
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="field">
                            <label for="serial_number" class="required">Seri number</label>
                            <div class="input-box">
                                <input type="text" name="card_serial" id="serial_number" />
                            </div>
                        </div>
                    </li>
                    <?php if (isset($error_mess)) {
                        echo "<li>";
                        echo '<div class="field message">';
                        echo '<span class="msg-error">' . $error_mess . '</span>';
                        echo "</div>";
                        echo "</li>";
                    } ?>
                    <li>
                        <div class="form-button">
                            <button class="button btn-common btn-next" type="submit" name="btn-continue"><span>Thanh toán</span></button>
                        </div>
                        <!--div style="height: 50px">&nbsp;</div-->
                    </li>
                </ul>
            </div>
        </form>
    </div>
</section>
</body>
