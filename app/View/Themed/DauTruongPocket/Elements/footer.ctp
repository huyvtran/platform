<?php
if (!isset($currentGame)) {
    $currentGame = "";
}
$alias = $currentGame['alias'];
?>

<footer id="footer">
    <div class="container">
        <div class="clearfix">
            <div class="info-company">
                <h2 class="c-logo"><a href="http://funtap.vn"><img src="<?php echo $this->Html->url('/') ?>uncommon/dautruongpk/images/c-logo.png" class="img-fix" alt="Funtap"></a></h2>
                <div class="info">
                    <p>Bản quyền &copy; 2017 Funtap</p>
                    <p>Tầng 8, phòng 803, Tòa nhà 315 Trường Chinh,<br> Thanh Xuân, Hà Nội</p>
                    <p>Chơi quá 180 phút một ngày sẽ ảnh hưởng xấu đến sức khỏe</p>
                    <p>Giấy phép G1 số: 468/GP-BTTTT ngày 17 tháng 09 năm 2015</p>
                </div>
            </div>
            <div class="links">
                <ul class="list-unstyled">
                    <li><a href="http://hotro.funtap.vn/">Hỗ trợ</a></li>
                    <li><a href="<?php echo $this->Html->url('/') .'huong-dan'; ?>">Hướng dẫn</a></li>
                    <li><a href="http://funtap.vn/dieu-khoan">Điều khoản</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<div id="trailer" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modalVideo" id="f-video">
        <div class="modalVideoI"></div>
    </div>
</div>
<div id="DownloadFirst" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="dialogDownload">
        <p>Bạn có muốn tải game game không?</p>
        <a href="javascript:void(0)" data-dismiss="modal" class="btn btn-default">Không</a>
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
            <a href="<?php echo $appstore_link; ?>" class="btn btn-success"  >Có</a>
            <?php
        }
        if ($MobileDetect->isAndroidOS()) {
            ?>
            <a href="<?php echo $google_play_link; ?>" class="btn btn-success"  >Có</a>
            <?php
        }
        if (!$MobileDetect->isMobile()) {
            ?>
            <a href="<?php echo $apk_link; ?>" class="btn btn-success"  >Có</a>
            <?php
        }
        ?>
        <!--/nocache-->
    </div>
</div>
<div class="fixed-box">
    <span class="qrcode"><img src="<?php echo $this->Html->url('/') ?>uncommon/dautruongpk/images/qr.png"  ></span>
<!--    <a href="javascript:void(0)" class="giftcode"></a>-->
<!--    <a href="--><?php //echo $this->Html->url(array("controller" => "pages","action" => "giftcode")); ?><!--" class="sprite giftcode"></a>-->
    <a href="http://funtap.vn/nap-the-game/<?php echo $alias;?>" class="napthe" target="_blank"></a>
    <a href="javascript:void(0)" class="sprite toggle-box open"></a>
</div>