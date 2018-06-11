<div class="app-login">

<div class="app-header">
    <p class="app-title">{{ title }}</p>
</div>
<div class="clear-float"></div>
<div class="app-body">
    <form ng-submit="processForm()">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-fw fa-user"></i>
                </div>
                <input type="text" name="username" class="form-control" placeholder="Tên đăng nhập" ng-model="formData.user_name" required="required">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-fw fa-envelope"></i>
                </div>
                <input type="email" name="email" class="form-control" placeholder="Email" ng-model="formData.email" required="required">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-fw fa-lock"></i>
                </div>
                <input type="text" name="password" class="form-control" placeholder="Mật khẩu" ng-model="formData.password" required="required">
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Đăng kí</button>
        </div>
    </form>
</div>

<div class="app-info">
    <span>Game Version: {{ game_version }}</span>
    <span class="text-right">SDK Version: {{ sdk_version }}</span>
</div>

</div>