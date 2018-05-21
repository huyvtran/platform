<?php
$this->extend('/Common/blank');
?>

<div class='row'>
    <div class="span12">
        <h3 class='page-header'>
            Update giao dịch
        </h3>
    </div>

    <div class='row'>
        <?php
        echo $this->Form->create('CardManual',array(
            'class' => 'form-horizontal',
            'inputDefaults' => array(
                'label' => array(
                    'class' => 'control-label'
                ),
                'div' => 'control-group',
                'between' => '<div class="controls">',
                'after' => '</div>',
            )
        ));
        if (!empty($this->data['CardManual']['id'])) {
            echo $this->Form->input('id');
        }
        ?>

        <div class="span4">
            <?php
            echo $this->Form->input('order_id', array(
                'type' => 'text',
                'readonly' => 'readonly',
                'label' => array(
                    'text' => 'Mã giao dịch',
                    'class' => 'control-label',
                )
            ));
            echo $this->Form->input('card_code', array(
                'type' => 'text',
                'readonly' => 'readonly',
                'label' => array(
                    'text' => 'Mã code ',
                    'class' => 'control-label',
                )
            ));
            echo $this->Form->input('card_serial', array(
                'type' => 'text',
                'readonly' => 'readonly',
                'label' => array(
                    'text' => 'Mã Seria',
                    'class' => 'control-label',
                )
            ));

            echo $this->Form->input('card_price', array(
                'type' => 'text',
                'readonly' => 'readonly',
                'label' => array(
                    'text' => 'Price from user',
                    'class' => 'control-label',
                )
            ));

            echo $this->Form->input('type', array(
                'type' => 'text',
                'readonly' => 'readonly',
                'label' => array(
                    'text' => 'Loại thẻ ',
                    'class' => 'control-label',
                )
            ));


            ?>

            <div class="form-actions">
                <?php
                echo $this->Form->submit('Submit', array(
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'div' => false
                ));
                ?>
            </div>
        </div>
        <div class="span4">
            <?php
            echo $this->Form->input('status', array(
                'type' => 'select',
                'label' => array(
                    'class' => 'control-label',
                    'text' => 'Trạng thái <span style="color: red">(*)</span>'
                ),
                'empty' => '-- Chose --',
                'options' => array(
                    WaitingPayment::STATUS_ERROR => 'Thất bại',
                    WaitingPayment::STATUS_REVIEW => 'Thành công',
                ),
            ));

            echo $this->Form->input('price', array(
                'type' => 'select',
                'label' => array(
                    'class' => 'control-label',
                    'text' => 'Giá tiền <span style="color: red">(*)</span>'
                ),
                'empty' => '-- Price chose --',
                'options' => array(
                    10000 => '10.000 VNĐ',
                    20000 => '20.000 VNĐ',
                    40000 => '40.000 VNĐ',
                    30000 => '30.000 VNĐ',
                    50000 => '50.000 VNĐ',
                    100000 => '100.000 VNĐ',
                    200000 => '200.000 VNĐ',
                    300000 => '300.000 VNĐ',
                    500000 => '500.000 VNĐ'
                ),
            ));
            echo $this->Form->input('detail', array('type' => 'textarea'));
            ?>
        </div>

        <?php echo $this->Form->end(); ?>
    </div>
</div>