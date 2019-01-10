<div class="wrapper">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="nav navbar-left">
            </div>
            <?php echo "Bank manual"; ?>
            <div class="nav navbar-right">
            </div>
        </div>
    </nav>
    <div class="clearfix"></div>

    <ul class="crumbs list-unstyled">
        <li class="active"> <?php echo __('Nạp thẻ'); ?></li>
        <li> <?php echo __('Lịch sử'); ?> </li>
    </ul>
    <div class="clearfix"></div>

    <div class="app-login">
        <div class="clear-float"></div>
        <div class="app-body">

            <form ng-submit="processForm()">
                <div class="form-group">
                    <div class="error-message text-center" style="color:red"></div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-2x fa-fw fa-user"></i>
                        </div>
                        <input type="text" name="fullname" class="form-control" placeholder="<?= __('Tên liên lạc'); ?>"
                               ng-model="formData.fullname" required
                               <?php if (!empty($profile['Profile']['fullname'])){ ?>ng-init="formData.fullname='<?= $profile['Profile']['fullname'] ?>'" <?php } ?> >
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-2x fa-fw fa-envelope"></i>
                        </div>
                        <input type="text" name="email" class="form-control" placeholder="Email"
                               ng-model="formData.email" required
                               <?php if (!empty($profile['Profile']['email'])){ ?>ng-init="formData.email='<?= $profile['Profile']['email'] ?>'" <?php } ?> >
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-2x fa-fw fa-phone"></i>
                        </div>
                        <input type="text" name="phone" class="form-control" placeholder="Phone"
                               ng-model="formData.phone" required
                               <?php if (!empty($profile['Profile']['phone'])){ ?>ng-init="formData.phone='<?= $profile['Profile']['phone'] ?>'" <?php } ?> >
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-6" style="padding-left: 0;">
                        <button type="submit" class="btn btn-primary btn-block"><?= __('Xác nhận') ?></button>
                    </div>

                    <div class="col-xs-6" style="padding-right: 0;">
                        <a class="btn btn-primary btn-block" style="padding-top: 10px;"><?= __('Hủy') ?></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>