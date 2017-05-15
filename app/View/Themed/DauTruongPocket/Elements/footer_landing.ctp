<?php
if (!isset($currentGame)) {
    $currentGame = "";
}
$gameConfigs        = $this->Cms->getLinkForSite($currentGame);
$contactEmail       = (isset($gameConfigs['support_email'])&&$gameConfigs['support_email'] != '') ? $gameConfigs['support_email'] : "#";
?>
<footer class="footer">
    <div class="footerInner fixCen pRel" itemscope="" itemtype="http://schema.org/Organization">
        <a href="<?php echo $this->Html->url('/') ?>" title="FunTap" class="funtap pAbs " target="_blank"><span itemprop="legalName">FunTap</span></a>
        <p class="rs textright">
            <a href='http://hotro.funtap.vn' title="FAQ" class="bs" target="_blank">FAQ</a>
            |
            <?php
                echo $this->Html->link('Cài đặt', array('controller' => 'categories', 'action' => 'index','slug'=>'guides'), array('target'=>'_blank','class'=>'bs'));
            ?>
            |
            <a href='http://funtap.vn/dieu-khoan' title="Điều Khoản" class="bs" target="_blank">Điều khoản</a></p>
        <p class="rs" itemprop="name">Bản quyền &copy; 2017 Funtap. </p>
        <p class="rs" itemprop="address" ><span itemprop="streetAddress">Tầng 8, phòng 803, Tòa nhà 315 Trường Chinh, Thanh Xuân, Hà Nội</span></p>
        <p class="rs">Giấy phép G1 số: 468/GP-BTTTT  ngày 17 tháng 09 năm 2015</p>
        <p class="rs">Chơi quá 180 phút một ngày sẽ ảnh hưởng xấu đến sức khỏe</p>
        <img src="http://cdn.smobgame.com/newfolder/limit/12t.png" width="60" height="86" class="ghdt">
    </div>
</footer>
