<body>
<section id="wrapper">
    <div class="input-text">
        <aside class="payment-banking">
            <p class="desc"><strong>Thanh toán bằng thẻ cào</strong></p>
        </aside>

        <form  method="post" id="frmInvite" name="frmInvite">
            <label for="card_type" class="required">Loại thẻ</label>
            <div class="input-box">
                <select id="card_type" name="type">
                    <option selected="selected">-- Chọn loại thẻ --</option>
                    <option value="<?php echo Payment::TYPE_NETWORK_VIETTEL ; ?>">Viettel</option>
                    <option value="<?php echo Payment::TYPE_NETWORK_MOBIFONE ; ?>">Mobifone</option>
                    <option value="<?php echo Payment::TYPE_NETWORK_VINAPHONE ; ?>">Vinaphone</option>
                </select>
            </div>

            <div class="fieldset">
                <ul class="form-list">
                    <li>
                        <div class="field">
                            <label for="card_code" class="required">Mã thẻ</label>
                            <div class="input-box">
                                <input type="text" name="card_code" id="card_code" />
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
                            <button class="button btn-common btn-next" type="submit"><span>Thanh toán</span></button>
                        </div>
                    </li>
                </ul>
            </div>
        </form>
    </div>
</section>
</body>
