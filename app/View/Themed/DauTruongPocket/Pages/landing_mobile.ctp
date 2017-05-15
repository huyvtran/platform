<!DOCTYPE html>
<html>
<head>
    <meta name = "viewport" content = "user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <?php echo $this->element('schema_invite'); ?>
    <?php
    $webroot = '/uncommon/dautruongpk';

    echo $this->element('meta_data');

    echo $this->Html->css('/uncommon/all-bootrap/css/bootstrap.min.css');
    echo $this->Html->css($webroot . '/css/landing-m.css');
    echo $this->Html->css('/uncommon/navtop-login/css/style.css');
    echo $this->fetch('css');
    ?>
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
    <div class="f-container ">
        <div class="fixCen pRel">
            <a href="/home" class="home"></a>
            <a href="https://www.facebook.com/dautruongmegaxy/" class="facebook"></a>
            <a href="/home" class="logo"></a>
            <div class="owl-heroes">
                <div class="hero hero1">
                    <div class="desc">
                        <h2>mega mewtwo</h2>
                        <div class="meta">
                            <div class="index">
                                <span>Công: 615</span><br>
                                <span>HP: 6963</span><br>
                                <span>Thủ v.lý: 379</span><br>
                                <span>Thủ phép: 316</span>
                            </div>
                            <div class="skill">
                                <span>Tuyệt chiêu</span><br>
                                <span>Sương trắng</span><br>
                                <span>Đạn dẫn sóng</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hero hero2">
                    <div class="desc">
                        <h2>mega blastoise</h2>
                        <div class="meta">
                            <div class="index">
                                <span>Công: 152</span><br>
                                <span>HP: 1719</span><br>
                                <span>Thủ v.lý: 77</span><br>
                                <span>Thủ phép: 93</span>
                            </div>
                            <div class="skill">
                                <span>Tuyệt chiêu</span><br>
                                <span>Va đập</span><br>
                                <span>Vẫy đuôi</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hero hero3">
                    <div class="desc">
                        <h2>mega charizard</h2>
                        <div class="meta">
                            <div class="index">
                                <span>Công: 152</span><br>
                                <span>HP: 1719</span><br>
                                <span>Thủ v.lý: 93</span><br>
                                <span>Thủ phép: 78</span>
                            </div>
                            <div class="skill">
                                <span>Tuyệt chiêu</span><br>
                                <span>Lợi trảo</span><br>
                                <span>Tiêu hỏa diệm</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hero hero4">
                    <div class="desc">
                        <h2>mega ho-oh</h2>
                        <div class="meta">
                            <div class="index">
                                <span>Công: 615</span><br>
                                <span>HP: 6963</span><br>
                                <span>Thủ v.lý: 315</span><br>
                                <span>Thủ phép: 380</span>
                            </div>
                            <div class="skill">
                                <span>Tuyệt chiêu</span><br>
                                <span>Mưa cát</span><br>
                                <span>Cao không kích</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hero hero5">
                    <div class="desc">
                        <h2>mega arceus</h2>
                        <div class="meta">
                            <div class="index">
                                <span>Công: 615</span><br>
                                <span>HP: 6963</span><br>
                                <span>Thủ v.lý: 379</span><br>
                                <span>Thủ phép: 316</span>
                            </div>
                            <div class="skill">
                                <span>Tuyệt chiêu</span><br>
                                <span>Hồi âm</span><br>
                                <span>Cao âm</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hero hero6">
                    <div class="desc">
                        <h2>mega raichu</h2>
                        <div class="meta">
                            <div class="index">
                                <span>Công: 152</span><br>
                                <span>HP: 1719</span><br>
                                <span>Thủ v.lý: 93</span><br>
                                <span>Thủ phép: 78</span>
                            </div>
                            <div class="skill">
                                <span>Tuyệt chiêu</span><br>
                                <span>Làm nũng</span><br>
                                <span>Thiềm quang</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hero hero7">
                    <div class="desc">
                        <h2>mega rayquaza</h2>
                        <div class="meta">
                            <div class="index">
                                <span>Công: 615</span><br>
                                <span>HP: 6963</span><br>
                                <span>Thủ v.lý: 316</span><br>
                                <span>Thủ phép: 379</span>
                            </div>
                            <div class="skill">
                                <span>Tuyệt chiêu</span><br>
                                <span>Điệp điệp</span><br>
                                <span>Tự do</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hero hero8">
                    <div class="desc">
                        <h2>mega venusaur</h2>
                        <div class="meta">
                            <div class="index">
                                <span>Công: 145</span><br>
                                <span>HP: 1746</span><br>
                                <span>Thủ v.lý: 95</span><br>
                                <span>Thủ phép: 79</span>
                            </div>
                            <div class="skill">
                                <span>Tuyệt chiêu</span><br>
                                <span>Khẩu tà</span><br>
                                <span>Tập kích</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--nocache-->
            <?php
            if (!isset($currentGame)) {
                $currentGame = "";
            }
            $gameConfigs        = $this->Cms->getLinkForSite($currentGame);
            $appstore_link      = (isset($gameConfigs['appstore_link'])&& $gameConfigs['appstore_link'] != '') ? $gameConfigs['appstore_link'] : "";
            $google_play_link   = (isset($gameConfigs['google_play_link'])&& $gameConfigs['google_play_link'] != '') ? $gameConfigs['google_play_link'] : "";
            $apk_link           = (isset($gameConfigs['apk_link'])&& $gameConfigs['apk_link'] != '') ? $gameConfigs['apk_link'] : "";
            
            $MobileDetect = new Mobile_Detect();
            if ($MobileDetect->isiOS()) {
                ?>
                <a href="<?php echo $appstore_link; ?>" class="dlnow"  >Download</a>
                <?php
            }
            if ($MobileDetect->isAndroidOS()) {
                ?>
                <a href="<?php echo $google_play_link; ?>" class="dlnow"  >Download</a>
                <?php
            }
            if (!$MobileDetect->isMobile()) {
                ?>
                <a href="<?php echo $apk_link; ?>" class="dlnow"  >Download</a>
                <?php
            }
            ?>
            <!--/nocache-->
        </div>
    </div>
    <?php echo $this->element('footer_landing'); ?>
    <a href="/teaser" class="btn-ip" target="_blank"></a>
    <a href="javascript:void(0)" class="btn_giftcode" data-toggle="modal" id="giftcode_show"></a>
    <?php echo $this->element('footer_landingm_script'); ?>

    <div class="modal fade" id="trailer" tabindex="-1" role="dialog" aria-labelledby="trailer" aria-hidden="true">
        <div class="f-video" id="f-video"></div>
    </div>


    <div class="modal fade" id="codeModal" tabindex="-1" role="dialog" aria-labelledby="codeModal" aria-hidden="true">
        <div class="f-pop">
            <a href="javascript:void(0)" data-dismiss="modal" aria-label="Close" class="f-close"></a>
            <p class="rs">Code của bạn là:   <span id="outgift"></span></p>
        </div>
    </div>



</body>
</html>