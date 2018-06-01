<div class="app-login">

<div class="app-header">
    <p class="app-title">{{ title }}</p>
</div>
<div class="clear-float"></div>
<div class="app-body">
    <!-- Form dang nhap -->
    <form ng-submit="processForm()">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-fw fa-user"></i>
                </div>
                <input type="text" name="username" class="form-control" placeholder="Tên đăng nhập" ng-model="formData.username" required>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-fw fa-lock"></i>
                </div>
                <input type="password" name="userpass" class="form-control" placeholder="Mật khẩu" ng-model="formData.userpass" required>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
        </div>
    </form>

    <div class="app-or">
        <hr class="hr-or">
        <span class="span-or">Hoặc</span>
    </div>
    <div class="app-ext">
        <a href="#register" class="btn btn-warning btn-block" role="button">Đăng kí</a>
    </div>
</div>

<div class="app-info">
    <span>Game Version: {{ game_version }}</span>
    <span class="text-right">SDK Version: {{ sdk_version }}</span>
</div>


</div>
