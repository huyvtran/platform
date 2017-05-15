<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8">
    <title>Giftcode Facebook</title>    
    <?php
        $url_css     = '';
        $url_imagebg = '';
        $message_hi  = '';
        $this->request->query;
        switch ($this->request->query['appkey']) {
            case '5e0687502c622bdc29bddfd193d688a9':
                $url_css     = '/uncommon/app-fb/css/invite-hr3.css';
                $url_imagebg = 'http://cdn.smobgame.com/newfolder/app-fb/hr-bg.jpg';
                $message_hi  = '<p class="rs title"><span id="_faceName"></span> | Bạn đã: chia sẻ <strong id="fb_share_num">0</strong> lần, mời <strong id="fb_invite_num">0</strong> bạn</p>';
                $share_s = 'Đã chia sẻ thành công!';
                $share_e = 'Xin lỗi, đã xảy ra sự cố trong lúc xử lý';
                $share_c = 'Bạn đã hủy chia sẻ.';
                $invite_s_1 = 'Đã mời ';
                $invite_s_2 = ' bạn thành công';
                $invite_e = 'Đã có lỗi xảy ra. Xin vui lòng thử lại!';
                $get_e_1 = 'Xin lỗi. Bạn chưa thực hiện đủ yêu cầu để nhận gift code này.';
                $get_e_2 = 'Đã có lỗi xảy ra. Xin vui lòng thử lại!';
                $user_e = 'Xin lỗi, đã xảy ra sự cố trong lúc xử lý';
                break;
            case 'af177f36668c99274fb0a330f68ef820':
                $url_css     = '/uncommon/app-fb/css/invite.css';
                $url_imagebg = 'http://cdn.smobgame.com/newfolder/app-fb/bg-vtq.jpg';
                $message_hi  = '<p class="rs title"><span id="_faceName"></span> | Bạn đã: chia sẻ <strong id="fb_share_num">0</strong> lần, mời <strong id="fb_invite_num">0</strong> bạn</p>';
                $share_s = 'Đã chia sẻ thành công!';
                $share_e = 'Xin lỗi, đã xảy ra sự cố trong lúc xử lý';
                $share_c = 'Bạn đã hủy chia sẻ.';
                $invite_s_1 = 'Đã mời ';
                $invite_s_2 = ' bạn thành công';
                $invite_e = 'Đã có lỗi xảy ra. Xin vui lòng thử lại!';
                $get_e_1 = 'Xin lỗi. Bạn chưa thực hiện đủ yêu cầu để nhận gift code này.';
                $get_e_2 = 'Đã có lỗi xảy ra. Xin vui lòng thử lại!';
                $user_e = 'Xin lỗi, đã xảy ra sự cố trong lúc xử lý';
                break;
            case 'ed975358b4bf641aaeebbd02fe4372d5':
                $url_css     = '/uncommon/app-fb/css/invite-cd.css';
                $url_imagebg = 'http://cdn.smobgame.com/newfolder/app-fb/bg-kmt1.jpg';
                $message_hi  = '<p class="rs title"><span id="_faceName"></span> | You have: shared <strong id="fb_share_num">0</strong> time(s), invited <strong id="fb_invite_num">0</strong> friend(s)</p>';
                $share_s = 'Shared success!';
                $share_e = 'Sorry, something error!';
                $share_c = 'Share cancel.';
                $invite_s_1 = 'Invited ';
                $invite_s_2 = ' friends';
                $invite_e = 'Error. try again!';
                $get_e_1 = 'Sorry Heroes, you have not done enough steps to get this Giftcode.';
                $get_e_2 = 'Error. try again!';
                $user_e = 'Sorry, something error!';
                break;
            case 'd581a17f345418e10d1b515dab668021':
                $url_css     = '/uncommon/app-fb/css/invite-hr.css';
                $url_imagebg = 'http://cdn.smobgame.com/newfolder/app-fb/bg-hr.jpg';
                $message_hi  = '<p class="rs title"><span id="_faceName"></span> | You have: shared <strong id="fb_share_num">0</strong> time(s), invited <strong id="fb_invite_num">0</strong> friend(s)</p>';
                $share_s = 'Shared success!';
                $share_e = 'Sorry, something error!';
                $share_c = 'Share cancel.';
                $invite_s_1 = 'Invited ';
                $invite_s_2 = ' friends';
                $invite_e = 'Error. try again!';
                $get_e_1 = 'Sorry Heroes, you have not done enough steps to get this Giftcode.';
                $get_e_2 = 'Error. try again!';
                $user_e = 'Sorry, something error!';
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
    <!-- test -->
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
    <!-- function it here -->
    <script>
        $(function () {
            $('#linkShare').click(function () {
                FB.ui(
                    {
                      method: 'feed',
                      name: '<?php echo $game_title ?>',
                      link: '<?php echo $fanpage_url ?>',
                      caption: 'Free Dowload form Google Play và Appstore'
                    },
                    function (response) {
                    console.log(response);
                      _logInfo(response);
                      if (response && !response.error_message) {
                           $.ajax({
                                type: "POST",
                                url: '/plf/giftcodes/client_action_post.json',
                                data: {'uid': fb_uid , 'accesstoken' : fb_accessToken, 'gid' : gid, 'type' : 'share' , 'length' : 1 },
                                dataType: 'json',
                                success: function(data) {
                                    _logInfo(data);
                                    if(Object.keys(data) == 'success'){
                                        var _sharedN = eval($('#fb_share_num').html());
                                        _sharedN = _sharedN + 1;
                                        $('#fb_share_num').html(_sharedN);
                                        alert('<?php echo $share_s; ?>');
                                    }else if(Object.keys(data) == 'error'){
                                        alert('<?php echo $share_e; ?>');
                                        console.log('code :'+data.error.code+' message :'+data.error.message);
                                    }

                                }
                            });
                      } else {
                          alert('<?php echo $share_c; ?>');
                      }
                    }
                );
            });
            $('#linkInvite').click(function () {
                    FB.ui(
                    {
                        method: 'apprequests',
                        message: '<?php echo $game_title ?>',
                        redirect_uri: '<?php echo $fanpage_url ?>'
                    },
                    function (response) {
                        if (response != null) {
                            _logInfo(response);
                            var length_invite = response.to;
                            
                            $.ajax({
                                type: "POST",
                                url: '/plf/giftcodes/client_action_post.json',
                                data: {'uid': fb_uid , 'accesstoken' : fb_accessToken, 'gid' : gid, 'type' : 'invite' , 'length' : length_invite.length },
                                dataType: 'json',
                                success: function(data) {
                                    _logInfo(data);
                                    if(Object.keys(data) == 'success'){
                                        var _invitedN = eval($('#fb_invite_num').html());
                                        _invitedN = _invitedN + length_invite.length;
                                        $('#fb_invite_num').html(_invitedN);
                                        alert('<?php echo $invite_s_1; ?>' + length_invite.length + '<?php echo $invite_s_2 ?>');
                                    }else if(Object.keys(data) == 'error'){
                                        alert('<?php echo $invite_e; ?>');
                                        console.log('code :'+data.error.code+' message :'+data.error.message);
                                    }

                                }
                            });
                        }
                    }
                    );
            });

            $('.get_gc_button').click(function(){
            
                var giftcode_event_id = $(this).attr('data-button');
                $.ajax({
                    type: "POST",
                    url: '/plf/giftcodes/client_action_getgc.json',
                    data: {'uid': fb_uid , 'accesstoken' : fb_accessToken, 'gid' : gid, 'gc_id' : giftcode_event_id},
                    dataType: 'json',
                    success: function(data) {
                        _logInfo(data);

                        if(Object.keys(data) == 'success'){
                            var out = '#out-'+giftcode_event_id;
                            var btnid = 'btnGetGiftCode'+giftcode_event_id;
                            $(out).html('Gift code: ' + data.success.giftcode).css("display", "").show();
                            $('input[id='+btnid+']').prop('disabled', true);
                        }else if(Object.keys(data) == 'error'){
                            if(data.error.code == -3){
                                alert('<?php echo $get_e_1; ?>');
                            } else {
                                alert('<?php echo $get_e_2; ?>');
                            }
                            console.log('code :'+data.error.code+' message :'+data.error.message);
                        }

                    }
                });
            });
        });

        function LoadUserInfo(_uid, _accToken, _gid) {
            $('#_avatar').attr("src", "https://graph.facebook.com/" + _uid + "/picture?type=normal");
            var url1= 'https://graph.facebook.com/me?access_token='+_accToken;
            $.ajax({
                url: url1,
                dataType: 'json',
                success: function(data, status) {
                    user_email = data.email;
                    var tageturl = '/plf/giftcodes/datauserfbapp.json'; 
                    $.ajax({
                        type: "POST",
                            url: tageturl,
                            data: {'uid': _uid , 'accesstoken' : _accToken,'gid' : _gid, 'email': user_email},
                            dataType: 'json',
                            success: function(data) {
                                if(Object.keys(data) == 'success'){
                                    $('#fb_invite_num').html(data.success.data.invite);
                                    $('#fb_share_num').html(data.success.data.share);
                                }else if(Object.keys(data) == 'error'){
                                    alert('<?php echo $user_e; ?>');
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
    </script>    
</head>

<body>
    <div class="container" style="background: url('<?php echo $url_imagebg; ?>') 0 0 no-repeat" >
        <header>
            <div class="headerInner">
                <a class="avata">
                    <img src="" id="_avatar" width="65" height="65">
                </a>
                <div class="boxInfo">
                    <?php echo $message_hi ?>
                    <?php
                        if(!empty($event)):
                            foreach ($event as $key => $value) {
                    ?>
                                <p class="rs text-code"><?php echo $value['GiftcodeEvent']['description']; ?></p>
                    <?php
                            }
                        endif;
                    ?>
                    <div>
                        <a class="share btn"  id="linkShare">
                            <img src="<?php echo $this->Html->url('/uncommon/app-fb/images/share.png') ?>" width="73" height="18">
                        </a>
                        <a class="invite btn"  id="linkInvite">
                            <img src="<?php echo $this->Html->url('/uncommon/app-fb/images/invite.png') ?>" width="73" height="18">
                        </a>
                    </div>
                </div>
            </div>
        </header>
        <div class="main">
            <?php
                if(!empty($event)):
                    foreach ($event as $key => $value) {
            ?>
                        <div class="box-code">
                            <input type="button" class="get_gc_button" name="get_giftcode" id="btnGetGiftCode<?php echo $value['GiftcodeEvent']['id']; ?>" value="Nhận <?php echo $value['GiftcodeEvent']['title']; ?>" data-button='<?php echo $value['GiftcodeEvent']['id']; ?>'>
                            <p class="rs giftcode" id='out-<?php echo $value['GiftcodeEvent']['id']; ?>'>No gift code!</p>
                        </div>
            <?php
                    }
                endif;
            ?>
        </div>
    </div>
</body>
</html>