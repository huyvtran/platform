<?php

$dataInviteEmail = json_encode(array(
    'subject' => $this->element('invite_email_title', array('code' => $code)),
    'body' => $this->element('invite_email', array('code' => $code))
));

?>

<section id="wrapper">
    <?php
        if (empty($installedFriends)) :
    ?>
        <div class="box-connect">
                <p class="rs c-mess"><?php echo __("Không có bạn bè nào đang chơi game này."); ?>&nbsp;<?php echo __('Mời bạn bè ngay và nhận Coin miễn phí.'); ?></p>
        </div>
    <?php
        else :
    ?>
    <div class="wrap-invite">
			<article class="boxInvite">
				<!--check 1 friend & >= 2 friends-->
				<p class="rs"><?php echo __("Có %s người bạn đang chơi game.", count($installedFriends)); ?></p>
				<div class="boxFriend">
					<?php foreach ($installedFriends as $friend) : ?>
		                <div class="boxFriend-inner">
                            <img src="<?php echo $friend['picture']['data']['url'] ?>" width="40" height="40">
                            <span class="name"><?php echo $friend['name'] ?></span>
		                </div>
					<?php endforeach; ?>
				</div>
			</article>
		<?php endif; ?>

        <article class="infoInner inviteInner ">
            <ul class="rs lstGuide lstInvite">
                <li ><a href="javascript:MobAppSDKexecute('mobAppInvite', {
                    applinkurl: '<?php echo 'http://'.$website['url'].'/landing'; ?>',
                    previewimageurl:'<?php if(isset($game['data']['invitefb2_image']['url']) && $game['data']['invitefb2_image']['url'] != ''){ echo $game['data']['invitefb2_image']['url']; } else {echo 'no image';} ?>'})" class="fb"><?php echo __('Mời bạn bè trên Facebook'); ?></a></li>
                <li >
                    <?php echo $this->Html->link(__('Mời bạn bè bằng Email'), "javascript:MobAppSDKexecute('mobSendEmail', $dataInviteEmail)",array('class'=>'email')); ?>
                    <!--<a href="javascript:MobAppSDKexecute('mobSendEmail', <?php echo $dataInviteEmail; ?>)" class='email'><?php echo __('Mời bạn bè bằng Email'); ?></a>-->
                </li>
            </ul>
        </article>


<!--        <p class="rs notice">-->
<!--            --><?php
//            $link = '#';
//            if (Configure::read('Config.language') == 'vie') {
//                $link = $this->Html->url(array('controller' => 'articles', 'action' => 'view', 'category' => 'faq', 'slug' => 'diem-thuong-mpoint'));;
//            } else {
//                $link = "javascript:void(0)";
//            }
//            // echo 'You will get reward scores if your friends download and play game. You can tranfer reward scores to value presents in game.<a href="">See more';
////            echo __("Mời bạn và tích lũy MPoints (MP) để đổi những phần thưởng hấp dẫn. Xem thêm %stại đây%s!", "<a href='$link'>", '</a>')
//            ?><!--</a>-->
<!--        </p>-->
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function(){
        $("body").addClass("info");
    });
</script>