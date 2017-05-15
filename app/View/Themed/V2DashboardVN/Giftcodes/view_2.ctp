<!--nocache-->
<?php
$model = Inflector::classify($this->params['controller']);
$this->Nav->markAsReaded($model,$this->Session->read('Auth.User.id'));
?>
<!--/nocache-->
<?php

$MobileDetect = new Mobile_Detect();
?>
<style type="text/css">
    .none-click{
        pointer-events: none;
    }
</style>
<section id="wrapper">
    <div class="page-gc">
        <!--nocache-->
        <?php
            echo $this->element('userShow');
        ?>
        <!--/nocache-->
        <article class="listcode">
            <h3 class="rs title-gc"><?php echo __('Giftcode miễn phí') ?></h3>
            <div class="box-code">
                <!-- giftcode thường -->
                <?php $no_gc = 0; ?>
                <?php if( $result_1['code'] == 1 ): ?>
                 <div class="f-boxcode">
                    <span class="f-namecode rs">
                        <?php echo $result_1['giftcode']['GiftcodeEvent']['title']; ?>
                    </span>
                    <span class="rs f-mescode nomarl"><?php echo $result_1['giftcode']['GiftcodeEvent']['description']; ?></span>
                    <span class="rs f-code2"><?php echo $result_1['giftcode']['Giftcode']['code']; ?></span>
                    <?php if($currentGame['alias'] == 'mobgamedemo'){ ?>
                    <a href="javascript:MobAppSDKexecute('mobAppInvite', {
                    applinkurl: '<?php echo $result_1['giftcode']['Giftcode']['code']; ?>'" class="f-copy">Copy</a>
                    <?php } ?>
                </div>
                <?php 
                    $no_gc++;
                endif; 
                ?>

                <!-- giftcode vip -->
                <?php 
                    if($result_2['code'] == 1){ 
                        if($result_3['code'] == 1):
                ?>
                <span class="f-boxcode">
                    <span class="f-namecode rs">
                        <?php echo $result_2['giftcode']['GiftcodeEvent']['title']; ?>
                    </span>
                    <span class="rs f-mescode nomarl"><?php echo $result_2['giftcode']['GiftcodeEvent']['description']; ?></span>
                    <span class="rs f-code2"><?php echo $result_2['giftcode']['Giftcode']['code']; ?></span>
                </span>
                <?php
                        else:
                ?>
                <a href="<?php echo $this->Html->url(array("controller" => "profiles", "action" => "getgiftcode")); ?>" class="f-boxcode f-boxcodeVip">
                    <span class="f-namecode rs">
                        <?php echo $result_2['giftcode']['GiftcodeEvent']['title']; ?>
                    </span>
                    <span class="rs f-mescode nomarl"><?php echo $result_2['giftcode']['GiftcodeEvent']['description']; ?></span>
                    <span class="rs f-code2">Nhập Email & nhận FREE</span>
                </a>
                <?php
                        endif;
                        $no_gc++;
                    } 
                ?>
                <?php if($no_gc == 0): ?> 
                    <p class="rs nocode"><?php echo __('Không có sự kiện giftcode'); ?></p>
                <?php endif; ?>
                <?php if($currentGame['alias'] == 'heroes-alliance' || $currentGame['alias'] == 'crouching-dragon-3d' || $currentGame['alias'] == 'pocket-dai-chien' || $currentGame['alias'] == 'who-sking' || $currentGame['alias'] == 'binh-phap-3d'){ ?>
                    <ul class="rs lstActionC">
                        <li><a href="javascript:MobAppSDKexecute('mobOpenFanPage', {'pageid': '<?php echo $currentGame['fbpage_id'] ?>'})" class="vote-fb"><?php echo __('Like fanpage'); ?></a></li>
                        <?php if (!$MobileDetect->isAndroidOS()): ?>
                        <li><a href="javascript:MobAppSDKexecute('mobOpenBrowser', {'url' : '<?php echo $currentGame['appstore_link'] ?>'})" class="vote-app"><?php echo __('Vote game'); ?></a></li>
                        <?php endif; ?>
                        <?php if (!$MobileDetect->isiOS()): ?>
                        <li><a href="javascript:MobAppSDKexecute('mobOpenBrowser', {'url' : '<?php echo $currentGame['appstore_link'] ?>'})" class="vote-app"><?php echo __('Vote game'); ?></a></li>
                        <?php endif; ?>
                    </ul>
                <?php } else { ?>
                    <ul class="rs lstActionC">
                        <li><a href="javascript:MobAppSDKexecute('mobOpenFanPage', {'pageid': '<?php echo $currentGame['fbpage_id'] ?>'})" class="vote-fb"><?php echo __('Thích fanpage nhận giftcode miễn phí'); ?></a></li>
                        <?php if (!$MobileDetect->isAndroidOS()): ?>
                        <li><a href="javascript:MobAppSDKexecute('mobOpenBrowser', {'url' : '<?php echo $currentGame['appstore_link'] ?>'})" class="vote-app"><?php echo __('Vote game nhận giftcode miễn phí'); ?></a></li>
                        <?php endif; ?>
                        <?php if (!$MobileDetect->isiOS()): ?>
                        <li><a href="javascript:MobAppSDKexecute('mobOpenBrowser', {'url' : '<?php echo $currentGame['appstore_link'] ?>'})" class="vote-app"><?php echo __('Vote game nhận giftcode miễn phí'); ?></a></li>
                        <?php endif; ?>
                    </ul>
                <?php } ?>
            </div>
        </article>

    </div>
</section>
<script>
    $(document).ready(function(){
        $(".f-boxcode .f-namecode").on('click', function() {
            $(this).closest('.f-boxcode').toggleClass('active')
        });
        $('.f-spentcoin').on('click', function() {
            if (confirm('<?php echo __('Bạn có đồng ý mua gói này không?') ?>')) {
                target = event.target;
                var gc_id   = $(event.target).attr('data-gc');
                $.ajax({
                    type: "POST",
                    url: '/platform/giftcodes/buy_giftcode_from_point.json',
                    data: {'gc_id': gc_id},
                    dataType: 'json',
                    success: function(data) {
                        if(Object.keys(data) == 'success'){
                            var output = "#out-giftcode-"+gc_id;
                            $(output).html(data.success.giftcode);
                            $(target).html('Đã mua').off().addClass('active');
                        }else if(Object.keys(data) == 'error'){
                            alert('Sorry, something error!');
                            console.log('code :'+data.error.code+' message :'+data.error.message);
                        }
                    }
                });
            } else {
    
            }

        });

    });
</script>
