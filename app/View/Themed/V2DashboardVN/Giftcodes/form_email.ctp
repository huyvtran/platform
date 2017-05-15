<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8">
    <title>Email Form</title>
    <?php
        $url_css     = '';
        $url_imagebg = '';
        switch ($this->request->query['appkey']) {
            case 'ed975358b4bf641aaeebbd02fe4372d5':
                $url_css     = '/uncommon/app-fb/css/form-fb.css';
                $url_imagebg = 'http://cdn.smobgame.com/newfolder/crouching/app-fb/bg.jpg';
                break;
            case 'beaad6bb893c6ae984379b7d53d9ae44':
                $url_css     = '/uncommon/app-fb/css/form-fbpk.css';
                $url_imagebg = 'http://cdn.smobgame.com/newfolder/pokemon/app/bg.jpg';
                break;
                
            default:
                # code...
                break;
        }
        echo $this->Html->css($url_css, null); 
        echo $this->fetch('css');
        echo $this->Html->script('/uncommon/app-fb/js/jquery-2.0.3.min.js');
        echo $this->fetch('script');
    ?>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">

    <script>
        function NotInFacebookFrame() {
            return top === self;
        }
        function ReferrerIsFacebookApp() {
            if (document.referrer) {
                return document.referrer.indexOf("apps.facebook.com") != -1;
            }
            return false;
        }
    </script>
    <!-- Init FB -->
    <script>
        //init
        var fb_uid         = '';
        var fb_accessToken = '';
        var gid            = <?php echo $game_id; ?>;
        var user_email     = ''; 
        window.fbAsyncInit = function () {
            FB.init({
                appId: '<?php echo $game_appkey_fb ?>',
                cookie: true,
                status: true,
                xfbml: true,
                version: 'v2.4',
            });

            FB.getLoginStatus(function (response) {
                // _logInfo(response);
                if (response.status === 'connected') {
                    fb_uid         = response.authResponse.userID;
                    fb_accessToken = response.authResponse.accessToken;
                    LoadUserInfo(fb_uid, fb_accessToken, gid);
                } else {
                    FB.login(function (response) {
                        if (_logInfo) {
                            _logInfo(response);
                        }
                        if (response.authResponse) {
                            fb_uid         = response.authResponse.userID;
                            fb_accessToken = response.authResponse.accessToken;
                            LoadUserInfo(fb_uid, fb_accessToken, gid);
                        }
                    }, { scope: 'email, public_profile, user_friends', return_scopes: true });
                }
            });
        };

        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) { return; }
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

    <script>
        function LoadUserInfo(_uid, _accToken, _gid) {
            var url1= 'https://graph.facebook.com/me?access_token='+_accToken;
            $.ajax({
                url: url1,
                dataType: 'json',
                success: function(data, status) {
                    console.log(data);
                    user_email = data.email;
                    var tageturl = '/plf/giftcodes/datauserfbapp.json'; 
                    $.ajax({
                        type: "POST",
                            url: tageturl,
                            data: {'uid': _uid , 'accesstoken' : _accToken,'gid' : _gid, 'email': user_email},
                            dataType: 'json',
                            success: function(data) {
                                if(Object.keys(data) == 'success'){
                                    $.ajax({
                                        type: "POST",
                                            url: '/plf/giftcodes/checkemailprereg.json',
                                            data: {'uid': _uid , 'gid' : _gid},
                                            dataType: 'json',
                                            success: function(data) {
                                                if(Object.keys(data) == 'success'){
                                                    $('.box-code').append(data.success.html).removeClass('hide');
                                                    $('.form-smb').addClass('hide');
                                                }else if(Object.keys(data) == 'error'){
                                                    console.log('code :'+data.error.code+' message :'+data.error.message);
                                                    $('.form-smb').append(data.error.html);
                                                }
                                            }
                                    });
                                }else if(Object.keys(data) == 'error'){
                                    alert('Xin lỗi, đã xảy ra sự cố trong lúc xử lý');
                                    console.log('code :'+data.error.code+' message :'+data.error.message);
                                }
                            }
                    });
                    $('#_faceName').html(data.name);
                },
                error: function(data, e1, e2) {
                    console.log(data);
                }
            })
            
        }
        function _logInfo(_info) {
            if (console && _logInfo)
                console.log(_info);
        }
        // $(function () {
        //     $("#idForm").submit(function(e) {
        //         alert('1');
        //         var url = "path/to/your/script.php"; // the script where you handle the form input.

        //         $.ajax({
        //                type: "POST",
        //                url: url,
        //                data: $("#idForm").serialize(), // serializes the form's elements.
        //                success: function(data)
        //                {
        //                    alert(data); // show response from the php script.
        //                }
        //              });

        //         e.preventDefault(); // avoid to execute the actual submit of the form.
        //     });
        // });
    </script>
</head>

<body>
    <div class="container" style="background: url('<?php echo $url_imagebg; ?>') 0 0 no-repeat" >
        <div class="box-img"></div>
        <div class="form-smb">

        </div>
        <div class="box-code hide">
            
        </div>
    </div>
</body>
</html>