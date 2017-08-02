<?php
$this->extend('/Common/blank');
?>

<div class='row'>
    <div class="span12">
        <h3 class='page-header'>
            Đền bù giao dịch
        </h3>
    </div>

    <div class='row'>
        <?php
        echo $this->Form->create('CompensePayment',array(
            'action' => 'add',
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
        if (!empty($this->data['CompensePayment']['id'])) {
            echo $this->Form->input('id');
        }
        ?>
        <div class="span4">
            <?php
            echo $this->Form->input('order_id', array(
                'type' => 'text',
                'label' => array(
                    'text' => 'Mã giao dịch <span style="color: red">(*)</span>',
                    'class' => 'control-label',
                )
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
        <div class="span8">
            <?php
            echo $this->Form->input('description', array('type' => 'textarea'));
            ?>
        </div>

        <?php echo $this->Form->end(); ?>
    </div>
</div>