<?php $role_id = $area_id = 1; ?>
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

            <?php echo __('Nạp từ Visa/Master'); ?>

            <div class="nav navbar-right">
            </div>
        </div>
    </nav>
    <div class="clearfix"></div>

    <ul class="crumbs list-unstyled">
        <li> <?php echo __('Phương thức nạp'); ?></li>
        <li class="active">
            <?php echo __('Chọn gói'); ?>
        </li>
        <li><?php echo __('Hoàn thành'); ?></li>
    </ul>
    <div class="clearfix"></div>

    <ul class="crumbs list-unstyled">
    </ul>

    <div id="page-wrapper"><br/>
        <?php
        if( empty($disable[Payment::TYPE_NETWORK_GATE][0]['status']) ){
            ?>
            <div class="alert alert-danger">
                The system is maintain, please come back later. <br/>
            </div>
        <?php }else{
            $str_bonus = "Note: bonus 30% coins when recharge via Zing";
            if( !empty($this->request->query('type')) && $this->request->query('type') == Payment::TYPE_NETWORK_GATE){
                $str_bonus = "Note: bonus 30% coins when recharge via Gate";
            }
            if( !empty($this->request->query('type')) && $this->request->query('type') == Payment::TYPE_NETWORK_VCOIN){
                $str_bonus = "Note: bonus 30% coins when recharge via Vcoin";
            }
            ?>

            <center> <span style="color: green"> <?php echo $str_bonus; ?> </span></center>
            <center> <span style="color: red"><?= $this->Session->flash('error'); ?> </span></center>
            <br/>
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
                    'text' => 'Card serial',
                    'class' => 'col-md-2 control-label'
                )
            ));

            echo $this->Form->input('card_code', array(
                'class' => 'form-control input-sm',
                'type' => 'text',
                'label' => array(
                    'text' => 'Card code',
                    'class' => 'col-md-2 control-label'
                )
            ));
            ?>
            <div class="form-group">
                <p class="help-block" id="help-card"><a href="https://www.seagm.com/fpt-gate-card-vn">Buy Gate</a></p>
                <div class="col-md-offset-2 col-md-9">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        <?php } ?>
    </div>
</div>
