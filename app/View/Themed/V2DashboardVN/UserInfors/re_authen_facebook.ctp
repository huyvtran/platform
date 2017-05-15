<div class="box-thongtin">
    <p class="rs tt-text">
        Bạn cần đăng nhập lại tài khoản Facebook trước khi thực hiện hành động này.<br>
        Đồng ý tiếp tục?
    </p>
    <div class="box-ttBtn cf">
        <a href="#" onclick="myFacebookLogin()" class="ttBtn ttBtn-red">Đồng ý</a>
        <a href="javascript:void(0)" class="ttBtn ttBtn-gray">Hủy</a>
    </div>
</div>
<script type="text/javascript">

    window.fbAsyncInit = function() {
        FB.init({
            appId      : '1381955065391312',
            xfbml      : true,
            version    : 'v2.5'
        });
    };
    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    function myFacebookLogin() {
        FB.login(function(response) {
            console.log(response);
            if (response.authResponse) {
                // Login success, check auth_nonce...
                checkNonce(response.authResponse.access_token);
            } else {
                // User cancelled
            }
        }, { auth_type: 'reauthenticate' })
    }
</script>