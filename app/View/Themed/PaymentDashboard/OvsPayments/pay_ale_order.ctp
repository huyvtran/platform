<style type="text/css">
    body {
        font-family: "Myriad Pro";
        background: #EEEEEE;
    }

    a:hover {
        text-decoration: none;
    }

    .form-control,
    .input-group-addon {
        border: #E3E3E3 1px solid;
        border-radius: 0;
        -webkit-appearance: none;
    }

    .form-group {
        margin-bottom: 10px;
    }

    .input-group-addon {
        background: #DADADA;
        color: #B4B4B4;
    }

    .btn {
        border-radius: 0;
        -webkit-appearance: none;
    }

    .btn-primary,
    .btn-primary:hover,
    .btn-primary:active,
    .btn-primary:visited,
    .btn-primary:focus {
        background-color: #00B0EB;
        border: none;
    }

    .btn-warning {
        background: #ff7e00;
        border: none;
    }

    .app-login {
        background: #FFFFFF;
        position: absolute;
        left: 50%;
        top: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        min-width: 300px;
    }

    .app-header {
        color: #26b6ec;
        font-size: 12.5pt;
        font-weight: bold;
    }

    .app-title {
        margin-top: 10px;
        text-align: center;
    }

    .btn-back a {
        color: #00B0EB;
        position: absolute;
        float: left;
        padding-left: 25px;
    }

    .app-body {
        margin: 0 25px;
    }

    .app-helper a {
        color: #014258;
        font-size: 12px;
        text-decoration: none;
        margin-bottom: 0;
    }

    .app-plugins {
        margin-top: 5px;
    }

    .app-ext {
        margin-bottom: 10px;
    }

    .app-ext a {
        color: #FFFFFF;
        font-weight: bold;
    }

    .app-info {
        color: #DADADA;
        font-size: 10px;
        margin-top: 10px;
        margin-left: 25px;
        margin-right: 25px;
    }

    .app-or {
        position: relative;
        font-size: 12px;
        color: #000;
        margin-top: 10px;
        margin-bottom: 10px;
        padding-top: 5px;
        padding-bottom: 5px;
    }

    .hr-or {
        background: #000;
        height: 0.1px;
        margin-top: 0px !important;
        margin-bottom: 0px !important;
    }

    .span-or {
        display: block;
        position: absolute;
        left: 50%;
        top: -1px;
        margin-left: -25px;
        background-color: #fff;
        width: 50px;
        text-align: center;
    }

    .btn-facebook {
        background: #3c5a9a;
        border: none;
    }

    .btn-google {
        background: #d44837;
        border: none;
    }

    .btn-facebook,
    .btn-google {
        width: 120px;
        height: 25px;
        line-height: 25px;
        margin-bottom: 0;
    }

    .btn-facebook a,
    .btn-google a {
        color: #FFFFFF;
        font-weight: bold;
        padding: 0 10px;
    }

    .text-right {
        float: right;
    }

    .clear-float {
        clear: both;
    }
</style>
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
        <?php
        $notice = $this->Session->flash('payment');
        if( !empty($notice) ){ ?>
            <center><span style="color: red;"><?php  echo $notice; ?></span></center><br/>
        <?php } ?>
        <div class="app-body">
            <?php echo $this->Form->create(false, array(
                'id' => 'reset',
                'inputDefaults' => array('label' => false, 'error' => false)));
            ?>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-fw fa-user"></i>
                    </div>
                    <?php echo $this->Form->input('buyer_name', array(
                        'class' => 'form-control',
                        'type' => 'text',
                        'id' => 'fullname',
                        'required' => 'required',
                        'placeholder' => __('Fullname')
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-fw fa-envelope"></i>
                    </div>
                    <?php echo $this->Form->input('buyer_email', array(
                        'class' => 'form-control',
                        'type' => 'text',
                        'required' => 'required',
                        'placeholder' => 'Email',
                        'id' => 'email'));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-fw fa-phone"></i>
                    </div>
                    <?php echo $this->Form->input('buyer_phone', array(
                        'class' => 'form-control',
                        'type' => 'text',
                        'required' => 'required',
                        'placeholder' => 'Mobile (+84985005006)',
                        'id' => 'mobile'));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-fw fa-home"></i>
                    </div>
                    <?php echo $this->Form->input('buyer_address', array(
                        'class' => 'form-control',
                        'type' => 'text',
                        'required' => 'required',
                        'placeholder' => 'Address',
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-fw location_city"></i>
                    </div>
                    <?php echo $this->Form->input('buyer_city', array(
                        'class' => 'form-control',
                        'type' => 'text',
                        'required' => 'required',
                        'placeholder' => 'City',
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-fw fa-globe"></i>
                    </div>
                    <?php echo $this->Form->input('buyer_country', array(
                        'class' => 'form-control',
                        'type' => 'text',
                        'required' => 'required',
                        'placeholder' => 'Country',
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $this->Form->button(__('Gửi'), array('class' => 'btn btn-primary btn-block')) ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </ul>
</div>
