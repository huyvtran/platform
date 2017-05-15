<!-- v2 -->
<?php if (empty($currentGame) || $this->Nav->showFunction('hide_login_facebook', $currentGame)) { ?>
    <?php
    $checkUpgradeFb = $this->Session->read('Auth.User.facebook_uid');
    if( $checkUpgradeFb == null ){
        $contentUpgrade = __("Bạn đang sử dụng chế độ đăng nhập nhanh. Tài khoản này có nguy cơ bị mất khi bạn cài lại game hay reset thiết bị. Hãy kết nối ngay với Facebook để bảo vệ tài khoản") ;
        if($this->Session->read('Auth.User.email'))
            $contentUpgrade = __("Bạn đang đăng nhập sử dụng email và mật khẩu. Hãy nối tài khoản với Facebook ngay để đăng nhập nhanh hơn mỗi lần mà không cần đăng nhập lại thông tin tài khoản và dễ dàng chia sẻ game với bạn bè");
        ?>
        <div class="box-facebook cf">
            <div class="ico"></div>
            <div class="short-desc">
                <?php if ($currentGame['language_default'] == 'vie') {?>
                <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'connect_facebook')) ?>" >
                    <b><?php echo __("Nối tài khoản với Facebook") ;?></b>
                    <span><?php echo $contentUpgrade ;?></span>
                </a>
            <?php } else {?>
                <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'view')) ?>" >
                    <b><?php echo __("Nối tài khoản với Facebook") ;?></b>
                    <span><?php echo $contentUpgrade ;?></span>
                </a>
            <?php }?>
            </div>
            <div class="btn-face">
                <?php if ($currentGame['language_default'] == 'vie') {?>
                    <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'connect_facebook')) ?>"><?php echo __('Kết nối Facebook') ;?></a>
                <?php } else {?>
                    <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'view')) ?>"><?php echo __('Kết nối Facebook') ;?></a>
                <?php }?>
            </div>

        </div>
    <?php }} ?>