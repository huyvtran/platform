<section id="wrapper">
    <div class="content-wrap">
        <div class="box-connect">
            <p class="rs c-mess">
                <?php echo __('Xin chào,'); ?><br>
                <?php echo __('Hãy kết nối với Facebook để bảo vệ tài khoản ngay'); ?>
            </p>
            <?php
            $role = 'User';
            $role = $this->Session->read('Auth.User.facebook_uid');
            if ($role != null) {
                echo __('<span class="text">Tài khoản của bạn đã kết nối Facebook</span>');
            } else {
                ?>
                <a href="javascript:MobAppSDKexecute('mobFacebookForUpdate', {})" class="fspr c-facebook">
                    <?php   if (empty( $game['Game']) || $this->Nav->showFunction('hide_popup_coin',  $game['Game'])) {?>
                        <span class="fspr c-coin">100</span>
                    <?php } ?>
                </a>
            <?php } ?>
        </div>
        <?php
            if ($role != null) {
                echo '<div class="rs msuccess">' . __('Đã kết nối Facebook thành công!') . '</div>';
            }
            else {
                echo '<div class="rs mwarning">'.$this->Session->flash("error_fb").'</div>';
            }
//            $email = $user['User']['email'];
//            if(isset($user['User']['fb_verified']) && $user['User']['email'] !=null){
//                $email = $user['User']['email'];
//            }elseif($user['User']['email_google_play'] != null){
//                $email = $user['User']['email_google_play'];
//            }
        ?>
<!--        <article class="infoInner">-->
<!--            <ul>-->
<!--                <li ><a href="javascript: void(0)" class="cf"><span class="text">Username</span><span class="dataT">--><?php //echo $user['User']['username']; ?><!--</span></a></li>-->
<!--                <li ><a href="javascript: void(0)" class="cf"><span class="text">ID</span><span class="dataT">--><?php //echo $user['User']['id']; ?><!--</span></a></li>-->
<!--                <li ><a href="javascript: void(0)" class="cf"><span class="text">Email</span><span class="dataT">--><?php //echo $email; ?><!--</span></a></li>-->
<!--            </ul>-->
<!---->
<!--        </article>-->
    </div>
</section>
