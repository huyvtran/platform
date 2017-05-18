<!DOCTYPE html>
<html>
<head>
    <!--<meta name = "viewport" content = "user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />-->
    <!--<meta name="apple-mobile-web-app-capable" content="yes"/>-->
    <?php
    $webroot = '/uncommon/dautruongpk';
    echo $this->element('meta_data');
    echo $this->Html->css($webroot . '/css/mini.css');
    echo $this->Html->css($webroot . '/css/teaser.css');
    echo $this->Html->css('/uncommon/all-bootrap/css/bootstrap.min.css');
    echo $this->Html->css('/uncommon/navtop-login/css/style.css');
    echo $this->fetch('css');
    echo $this->Html->script('/uncommon/all-js/jquery-1.10.2.min.js');
    echo $this->fetch('script');
    ?>
	<?php echo $this->element('schema_invite'); ?>
    <?php
    if (!isset($currentGame)) {
        $currentGame = "";
    }
    $gameConfigs        = $this->Cms->getLinkForSite($currentGame);
    $appstore_link      = (isset($gameConfigs['appstore_link'])&& $gameConfigs['appstore_link'] != '') ? $gameConfigs['appstore_link'] : "";
    $google_play_link   = (isset($gameConfigs['google_play_link'])&& $gameConfigs['google_play_link'] != '') ? $gameConfigs['google_play_link'] : "";
    $apk_link           = (isset($gameConfigs['apk_link'])&& $gameConfigs['apk_link'] != '') ? $gameConfigs['apk_link'] : "";
    ?>

</head>
<body class="rs">
<?php echo $this->element('nav-login'); ?>
<div class="f-container">
    <div class="main fixCen">
        <a href="javascript:void(0)" class="code spr"  id="play-now">code</a>
        <h1 class="rs">
            <a class="logo" href="<?php echo $this->Html->url('/home') ?>" title="<?php echo $currentGame['title']; ?>"><?php echo $currentGame['title']; ?></a>
        </h1>
        <!--nocache-->
<!--        --><?php
//        $facebook = $this->Session->read('Auth.User.facebook_uid');
//        $email = $this->Session->read('Auth.User.email');
//        if($email != null && strpos($email, '@haitacmobi.com') === false ){
//            ?>
<!---->
<!--        --><?php //}else{ ?>
<!--            <a href="javascript:void(0)" id="giftcode_show"></a>-->
<!--        --><?php //} ?>
        <!--/nocache-->
        <div class="box-game cf" id="box-game">
            <div class="box-gameL">
                <div class="block1">2048</div>
                <div class="score-container ld">0</div>
                <div class="best-container">0</div>
                <a href="javascript:void(0)" class="restart-button">Chơi Lại</a>
                <a href="javascript:void(0)" class="huong-dan">Hướng Dẫn</a>
            </div>
            <div class="box-gameR" id="box-gameR">
                <div class="game-container">
                    <div class="game-message">
                        <div class="box-txt">
                            <p class="rs">Chúc mừng bạn đã chiến thắng.<br> Số điểm của bạn là</p>
                            <span class="txt-ld">0</span>
                            <p class="rs">Lưu hết quả để nhận Vipcode trị giá 5 triệu đồng và có cơ hội nhận được chiếc điện thoại IP7+ thời thượng</p>
                            <form>
                                <input type="email" id="ip-email" class="ip-email" value="" placeholder="Để lại email" required spellcheck="false">
                                <p id="result" class="rs"></p>
                                <div class="lower">
                                    <a href="javascript:void(0)" class="btnluu">Lưu</a>
                                    <a class="retry-button">Bỏ Qua</a>
                                    <a class="keep-playing-button">Chơi Tiếp</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="box-hd">
                        <div class="box-ihd ">
                            <a href="javascript:void(0)" class="btn-close">X</a>
                            <h3 class="rs">Hướng Dẫn</h3>
                            <p class="rs txt-fr">Dùng các phím mũi tên hoặc dùng chuột</p>
                            <span class="spr arr"></span>
                            <p class="rs txt-ls">
                                Khi có 2 hình ảnh PKM giống nhau, chúng sẽ hợp thành 1 hình ảnh PKM mới.
                                Hình ảnh giống nhau trên cùng một hàng (hoặc cột) đều có thể hợp lại nếu chúng
                                không bị ngăn cách bởi một hình ảnh khác.
                            </p>
                            <p class="rs txt-ls">
                                Mỗi lần di chuyển là dịch chuyển toàn bộ hình ảnh sang một hướng.
                                Sau mỗi lần di chuyển, màn hình sẽ xuất hiện thêm một hình ảnh mới.
                                Cách Tính Điểm: Điểm được tính khi người chơi hợp được từ 2 hình ảnh giống
                                nhau trở lên.
                            </p>
                            <table class="tg">
                                <tr>
                                    <th class="tg-yw4l">Pikachu = 2 Điểm</th>
                                    <th class="tg-yw4l">Staryu = 32 Điểm</th>
                                    <th class="tg-yw4l">Ho-oh = 512 Điểm</th>
                                </tr>
                                <tr>
                                    <td class="tg-yw4l">Gastly = 4 Điểm</td>
                                    <td class="tg-yw4l">Phione = 64 Điểm</td>
                                    <td class="tg-yw4l">Lugia = 1024 Điểm</td>
                                </tr>
                                <tr>
                                    <td class="tg-yw4l">Bulbasaur = 8 Điểm</td>
                                    <td class="tg-yw4l">Entei = 128 Điểm</td>
                                    <td class="tg-yw4l">Mewtwo = 2048 Điểm</td>
                                </tr>
                                <tr>
                                    <td class="tg-yw4l">Squirtle = 16 Điểm</td>
                                    <td class="tg-yw4l">Suicune = 256 Điểm</td>
                                    <td class="tg-yw4l"></td>
                                </tr>
                            </table>
                        </div>

                    </div>
                    <div class="grid-container">
                        <div class="grid-row cf">
                            <div class="grid-cell"></div>
                            <div class="grid-cell"></div>
                            <div class="grid-cell"></div>
                            <div class="grid-cell"></div>
                        </div>
                        <div class="grid-row cf">
                            <div class="grid-cell"></div>
                            <div class="grid-cell"></div>
                            <div class="grid-cell"></div>
                            <div class="grid-cell"></div>
                        </div>
                        <div class="grid-row cf">
                            <div class="grid-cell"></div>
                            <div class="grid-cell"></div>
                            <div class="grid-cell"></div>
                            <div class="grid-cell"></div>
                        </div>
                        <div class="grid-row cf">
                            <div class="grid-cell"></div>
                            <div class="grid-cell"></div>
                            <div class="grid-cell"></div>
                            <div class="grid-cell"></div>
                        </div>
                    </div>
                    <div class="tile-container">
                    </div>
                </div>
            </div>
        </div>
        <div class="box-kq">
            <ul class="lstLeader cf rs" role="tablist">
                <li role="presentation" class="active"><a class="bxh" href="#bxh" aria-controls="bxh" role="tab" data-toggle="tab">Bảng Xếp Hạng</a></li>
                <li role="presentation"><a class="thuong" href="#thuong" aria-controls="thuong" role="tab" data-toggle="tab">Phần Thưởng</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="bxh">
<!--                    <table class="tblBxh">-->
<!--                        <tbody>-->
<!--                        <tr>-->
<!--                            <th class="stt">STT</th>-->
<!--                            <th class="mail">Email</th>-->
<!--                            <th class="time">Điểm</th>-->
<!--                        </tr>-->
<!--                        --><?php
//                            $k = 0;
//                            if (isset($listUser)) {
//                                foreach ($listUser as $value) {
//                                    $k++;
//                                    $email = explode('@', $value['LogTeaser']['email']);
//                        ?>
<!--                            <tr>-->
<!--                                <td><strong class="number">--><?php //echo $k;?><!--</strong></td>-->
<!--                                <td>--><?php //echo $email[0] . '@' . $email[1][0] . '...';?><!--</td>-->
<!--                                <td>--><?php //echo $value['0']['score_int'];?><!--</td>-->
<!--                            </tr>-->
<!--                        --><?php //}}?>
<!--                        </tbody>-->
<!--                    </table>-->
                    <table class="tblBxh">
                        <tbody>
                        <tr>
                            <th class="stt">STT</th>
                            <th class="mail">Email</th>
                            <th class="time">Điểm</th>
                        </tr>
                        <tr>
                            <td><strong class="number">1</strong></td>
                            <td>khanhdinh91@g...</td>
                            <td>179984</td>
                        </tr>
                        <tr>
                            <td><strong class="number">2</strong></td>
                            <td>tamshadow2000@g...</td>
                            <td>157984</td>
                        </tr>
                        <tr>
                            <td><strong class="number">3</strong></td>
                            <td>yagamiraito84264@g...</td>
                            <td>153996</td>
                        </tr>
                        <tr>
                            <td><strong class="number">4</strong></td>
                            <td>anhdungdi1978@g...</td>
                            <td>145894</td>
                        </tr>
                        <tr>
                            <td><strong class="number">5</strong></td>
                            <td>binhnguyen.hbupr@g...</td>
                            <td>143396</td>
                        </tr>
                        <tr>
                            <td><strong class="number">6</strong></td>
                            <td>khanhpham.tph@g...</td>
                            <td>143396</td>
                        </tr>
                        <tr>
                            <td><strong class="number">7</strong></td>
                            <td>huyahihi69@g...</td>
                            <td>143384</td>
                        </tr>
                        <tr>
                            <td><strong class="number">8</strong></td>
                            <td>tiendtt.neu2009@g...</td>
                            <td>135320</td>
                        </tr>
                        <tr>
                            <td><strong class="number">9</strong></td>
                            <td>linhnguyen@g...</td>
                            <td>135320</td>
                        </tr>
                        <tr>
                            <td><strong class="number">10</strong></td>
                            <td>tritam.nguyen69@g...</td>
                            <td>131320</td>
                        </tr>
                        <tr>
                            <td><strong class="number">11</strong></td>
                            <td>trungnguyen2kk@g...</td>
                            <td>128692</td>
                        </tr>
                        <tr>
                            <td><strong class="number">12</strong></td>
                            <td>theshyn987@g...</td>
                            <td>128480</td>
                        </tr>
                        <tr>
                            <td><strong class="number">13</strong></td>
                            <td>Duong.dkt12@g...</td>
                            <td>128364</td>
                        </tr>
                        <tr>
                            <td><strong class="number">14</strong></td>
                            <td>tamtoitinh20@g...</td>
                            <td>127552</td>
                        </tr>
                        <tr>
                            <td><strong class="number">15</strong></td>
                            <td>Haitranphan33322@g...</td>
                            <td>127512</td>
                        </tr>
                        <tr>
                            <td><strong class="number">16</strong></td>
                            <td>monster.nguyen2805@g...</td>
                            <td>125688</td>
                        </tr>
                        <tr>
                            <td><strong class="number">17</strong></td>
                            <td>Nhoxsocklaanh123@g...</td>
                            <td>123988</td>
                        </tr>
                        <tr>
                            <td><strong class="number">18</strong></td>
                            <td>Ng0cphan2009@g...</td>
                            <td>123416</td>
                        </tr>
                        <tr>
                            <td><strong class="number">19</strong></td>
                            <td>nguythiendi0214@g...</td>
                            <td>122588</td>
                        </tr>
                        <tr>
                            <td><strong class="number">20</strong></td>
                            <td>H0angbk.0712@g...</td>
                            <td>122552</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="thuong">
                    <div class="box-thuong"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-left visi">
        <a href="javascript:void(0)" onclick="closeFixedBox();" class="close"></a>
        <a href="javascript:void(0)" class="open"></a>
        <a href="<?php echo $appstore_link; ?>" class="ios"></a>
        <a href="<?php echo $google_play_link; ?>" class="android"></a>
        <a href="<?php echo $apk_link; ?>" class="apk"></a>
        <div class="fb">
            <div class="fb-page" data-href="https://www.facebook.com/dautruongmegaxy/" data-tabs="timeline" data-width="200" data-height="130" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false"><blockquote cite="https://www.facebook.com/dautruongmegaxy/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/dautruongmegaxy/">Đấu Trường Mega XY</a></blockquote></div>
        </div>
    </div>
    <div class="modal fade" id="cms" tabindex="-1" role="dialog" aria-labelledby="trailer" aria-hidden="true">
        <div class="f-pop f-dl">
            <h3 class="rs">Coming Soon</h3>
        </div>
    </div>
    <div class="modal" id="email1" tabindex="-1" role="dialog" aria-labelledby="">
        <div class="box-email f-center">
            <a href="javascript:void(0)"  data-dismiss="modal" aria-label="Close" class="btn-cls"></a>
            <p class="rs">Bước 1: Like fapage <a href="https://www.facebook.com/dautruongmegaxy/" target="_blank">Đấu Trường Mega XY</a></p>
            <p class="rs">Bước 2: Để lại email, nhận link tải game và Vipcode</p>
            <input type="email" class="btn-email" placeholder="Để lại email">
            <a href="javascript:void(0)" class="btn-register">Đăng ký ngay</a>
        </div>
    </div>
    <?php echo $this->element('footer_landing'); ?>
    <?php echo $this->element('footer_teaser_script'); ?>
    <?php echo $this->element('website_giftcode_script'); ?>
    <script type="text/javascript">
        $('#email1').modal('show');
        function validateEmail(email) {
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }

        $('.btn-register').click(function() {
            var score = 0;
            var email = $('.btn-email').val();
            if (validateEmail(email)) {
                $('.btn-email').removeClass('err');
            } else {
                console.log('a');
                $('.btn-email').addClass('err');
                return false;
            }
            if (email != '') {
                $.ajax({
                    url: '<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'teaser')); ?>',
                    type: 'POST',
                    data: {email:email, score:score},
                    dataType:'json',
                    success: function(result) {
                        if (result.code == 1) {
                            Cookies.set('<?php echo $_SERVER['REMOTE_ADDR'];?>', 'in_view', { expires: 1 });
                            $('#email1').modal('hide');
                        }
                    }
                })
            }
        });

        $('.btnluu').click(function() {
            var score = $('.txt-ld').text();
            var email = $('.ip-email').val();
            if (validateEmail(email)) {
                $('.ip-email').removeClass('err');
            } else {
                console.log('a');
                $('.ip-email').addClass('err');
                return false;
            }
            if (score != '' && email != '') {
                $.ajax({
                    url: '<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'teaser')); ?>',
                    type: 'POST',
                    data: {score:score, email:email},
                    dataType:'json',
                    success: function (result) {
                        if (result.code == 1) {
                            window.location.reload();
                        }
                    }
                })
            }
        });
//        $('#layer-login').on('click',function(){
//            $.ajax({
//                url: '<?php //echo $this->Html->url(array('controller' => 'users', 'action' => 'checkUserLanding')); ?>//',
//                type: 'GET',
//                data: {},
//                success: function (data) {
//                    res = JSON.parse(data);
//                    if(res.is_fb != false){
//                        if (res.haitac){
//                            $('#f-nBLogin2').modal('show');
//                        }
//                    }else{
//                        $('#f-nBLogin').modal('show');
//                    }
//                },
//                error: function (result) {
//                    console.log(result);
//                }
//            })
//
//        });

    </script>
</body>
</html>
