<!--nocache-->
<?php
$model = Inflector::classify($this->params['controller']);
$this->Nav->markAsReaded($model,$this->Session->read('Auth.User.id'));
?>
<!--/nocache-->

<?php

$MobileDetect = new Mobile_Detect();
?>
<section id="wrapper">
    <div class="page-gc">
        <article class="listcode">
            <article class="listcode">
                <?php if (!empty($giftcode)) : ?>
                    <p class="rs"><strong>Free Giftcode</strong></p>
                    <p class="rs f-code1"><?php echo $giftcode['Giftcode']['code'] ?></p>
                <?php else : ?>
                    <p class="rs nocode">No free giftcode event now</p>
                <?php endif; ?>
                    <ul class="rs lstActionC">
                        <li><a href="javascript:MobAppSDKexecute('mobOpenFanPage', {'pageid': '<?php echo $currentGame['fbpage_id'] ?>'})" class="vote-fb">Like fanpage for free giftcode</a></li>
                        <?php if (!$MobileDetect->isAndroidOS()): ?>
                            <li><a href="javascript:MobAppSDKexecute('mobOpenBrowser', {'url' : '<?php echo $currentGame['appstore_link'] ?>'})" class="vote-app">Vote game for free giftcode</a></li>
                        <?php endif; ?>
                            <?php if (!$MobileDetect->isiOS()): ?>
                                <li><a href="javascript:MobAppSDKexecute('mobOpenBrowser', {'url' : '<?php echo $currentGame['appstore_link'] ?>'})" class="vote-app">Vote game for free giftcode</a></li>
                            <?php endif; ?>
                </ul>

            </article
    </div>
</section>
