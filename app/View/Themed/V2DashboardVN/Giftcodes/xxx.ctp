<!--nocache-->
<?php
    $model = Inflector::classify($this->params['controller']);
    $this->Nav->markAsReaded($model,$this->Session->read('Auth.User.id'));
    $time_zone = (isset($gameConfigs['time_zone'])&& $gameConfigs['time_zone'] != '') ? $gameConfigs['time_zone'] : "Asia/Hong_Kong";
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
        <article class="listcode">
            <?php $no_gc = 0; ?>
            <?php if( $result_voteapp['code'] == 1 ): ?>
            <h3 class="rs title-gc">Giftcode Event</h3>
            <div class="box-code">
                <div id="check-box" class="f-boxVote">
                    <span class="f-namevote rs">
                    <?php echo $result_voteapp['giftcode']['GiftcodeEvent']['title']; ?>
                    </span>
                    <span class="rs f-codeVote" id="code-message"><?php echo $result_voteapp['giftcode']['GiftcodeEvent']['description']; ?></span>
                    <span class="rs f-code2 hide" id="code_vote"><?php echo $result_voteapp['giftcode']['Giftcode']['code']; ?></span>
                    <a href="javascript:MobAppSDKexecute('mobCopyToClipboard', {data: '<?php echo $result_voteapp['giftcode']['Giftcode']['code']; ?>'})" class="f-copy hide" id="btn_vote">Copy</a>
                    <?php if (!$MobileDetect->isAndroidOS()): ?>
                    <a href="javascript:myCustomFunction()" class="f-vote">Vote now<span id="loadspin" class="spin hide"></span></a>
                    <?php endif; ?>
                    <?php if (!$MobileDetect->isiOS()): ?>
                    <a href="javascript:myCustomFunction()" class="f-vote">Vote now<span id="loadspin" class="spin hide"></span></a>
                    <?php endif; ?>
                </div>
            </div>
            <?php 
            endif;
            ?>
            <h3 class="rs title-gc"><?php echo __('Giftcode miễn phí') ?></h3>
            <div class="box-code">
                <!-- giftcode tân thủ -->
                
                <?php if( $result_normal['code'] == 1 ): ?>
                <div  class="f-boxcode">
                    <span class="f-namecode rs">
                        <?php echo $result_normal['giftcode']['GiftcodeEvent']['title']; ?>
                    </span>
                    <span class="rs f-mescode nomarl"><?php echo $result_normal['giftcode']['GiftcodeEvent']['description']; ?></span>
                    <span class="rs f-code2"><?php echo $result_normal['giftcode']['Giftcode']['code']; ?></span>
                    <?php if(in_array($this->request->header('mobgame-sdk-version'),array('2.3.0','2.3.2','2.4.0.1'))){ ?>
                    <a href="javascript:MobAppSDKexecute('mobCopyToClipboard', {data: '<?php echo $result_normal['giftcode']['Giftcode']['code']; ?>'})" class="f-copy">Copy</a>
                    <span class="f-hh"><?php echo __('Hết hạn') ?>: <?php echo $this->Time->format('d/m/Y', $result_normal['giftcode']['GiftcodeEvent']['code_expires'], null, $time_zone); ?></span>
                    <?php } ?>
                </div>
                <?php 
                    $no_gc++;
                endif; 
                ?>
                <!-- giftcode hội viên thường -->
                <?php if( $result_hv['code'] == 1 ): ?>
                <div  class="f-boxcode f-boxcodeVip">
                    <span class="f-namecode rs">
                        <?php 
                            // echo $result_hv['giftcode']['GiftcodeEvent']['title']; 
                            echo __('Giftcode KHTT Funtap');
                        ?>
                    </span>
                    <span class="rs f-mescode nomarl"><?php echo $result_hv['giftcode']['GiftcodeEvent']['description']; ?></span>
                    <span class="rs f-code2"><?php echo $result_hv['giftcode']['Giftcode']['code']; ?></span>
                    <?php if(in_array($this->request->header('mobgame-sdk-version'),array('2.3.0','2.3.2','2.4.0.1'))){ ?>
                    <a href="javascript:MobAppSDKexecute('mobCopyToClipboard', {data: '<?php echo $result_hv['giftcode']['Giftcode']['code']; ?>'})" class="f-copy">Copy</a>
                    <span class="f-hh"><?php echo __('Hết hạn') ?>: <?php echo $this->Time->format('d/m/Y', $result_hv['giftcode']['GiftcodeEvent']['code_expires'], null, $time_zone); ?></span>
                    <?php } ?>
                </div>
                <?php 
                    $no_gc++;
                endif; 
                ?>
                <!-- giftcode vip -->
                <?php 
                    if($result_vip['code'] == 1){ 
                ?>
                <div  class="f-boxcode f-boxcodeVip">
                    <span class="f-namecode rs">
                        <?php 
                            // echo $result_vip['giftcode']['GiftcodeEvent']['title']; 
                            echo __('VIP code KHTT Funtap');
                        ?>
                    </span>
                    <span class="rs f-mescode nomarl"><?php echo $result_vip['giftcode']['GiftcodeEvent']['description']; ?></span>
                    <span class="rs f-code2"><?php echo $result_vip['giftcode']['Giftcode']['code']; ?></span>
                    <?php if(in_array($this->request->header('mobgame-sdk-version'),array('2.3.0','2.3.2','2.4.0.1'))){ ?>
                    <a href="javascript:MobAppSDKexecute('mobCopyToClipboard', {data: '<?php echo $result_vip['giftcode']['Giftcode']['code']; ?>'})" class="f-copy">Copy</a>
                    <span class="f-hh"><?php echo __('Hết hạn') ?>: <?php echo $this->Time->format('d/m/Y', $result_vip['giftcode']['GiftcodeEvent']['code_expires'], null, $time_zone); ?></span>
                    <?php } ?>
                </div>
                <?php
                        $no_gc++;
                    } 
                ?>
                <!-- giftcode birthday -->
                <?php 
                if($result_birthday['code'] == 1):
                ?>
                <div  class="f-boxcode f-boxcodeSN">
                    <span class="f-namecode rs">
                        <?php echo $result_birthday['giftcode']['GiftcodeEvent']['title']; ?>
                    </span>
                    <span class="rs f-mescode nomarl"><?php echo $result_birthday['giftcode']['GiftcodeEvent']['description']; ?></span>
                    <span class="rs f-code2"><?php echo $result_birthday['giftcode']['Giftcode']['code']; ?></span>
                    <?php if(in_array($this->request->header('mobgame-sdk-version'),array('2.3.0','2.3.2','2.4.0.1'))){ ?>
                    <a href="javascript:MobAppSDKexecute('mobCopyToClipboard', {data: '<?php echo $result_birthday['giftcode']['Giftcode']['code']; ?>'})" class="f-copy">Copy</a>
                    <span class="f-hh"><?php echo __('Hết hạn') ?>: <?php echo $this->Time->format('d/m/Y', $result_birthday['giftcode']['GiftcodeEvent']['code_expires'], null, $time_zone); ?></span>
                    <?php } ?>
                </div>
                <?php 
                    $no_gc++;
                endif; 
                ?>
                <?php if($no_gc == 0): ?> 
                    <p class="rs nocode"><?php echo __('Không có sự kiện giftcode'); ?></p>
                <?php endif; ?>
                <?php if(in_array($currentGame['alias'], array())){ ?>
                <ul class="rs lstActionC">
                    <li>
                        <a href="javascript:MobAppSDKexecute('mobOpenFanPage',
                        {
                            'pageid': '<?php echo isset($infoLike['fanpage'])?$infoLike['fanpage']:''; ?>',
                            'callbackUrl':'http://a.smobgame.com/plf/oauthv2/afterActionExecuted?action_name=likeFacebook&id_action=<?php echo $infoLike['id'] ; ?>'
                        })" class="vote-fb"><?php echo __('Thích fanpage'); ?></a>
                    </li>
                    <li><a href="javascript:MobAppSDKexecute('mobAppInvite', {
                        'applinkurl': '<?php echo 'http://'.$website_url.'/download'; ?>',
                        'previewimageurl':'<?php if(isset($game['data']['invitefb2_image']['url']) && $game['data']['invitefb2_image']['url'] != ''){ echo $game['data']['invitefb2_image']['url']; } else {echo 'no image';} ?>',
                        'callbackUrl':'http://a.smobgame.com/plf/oauthv2/afterActionInvite?action_name=inviteFriend'
                    })" class="vote-app"><?php echo __('Mời bạn'); ?></a></li>
                    <li><a href="javascript:MobAppSDKexecute('mobActionShareFB', {
                        'url':'http://<?php echo isset($infoShare['url'])?$infoShare['url']:''; ?>',
                        'content':'<?php echo isset($infoShare['content'])?$infoShare['content']:''; ?>',
                        'callbackUrl':'http://a.smobgame.com/plf/oauthv2/afterActionExecuted?action_name=shareFacebook&id_action=<?php echo $infoShare['id'] ; ?>'
                    })" class="vote-fb"><?php echo __('Chia sẻ'); ?></a></li>
                </ul>
                <?php } ?>
            </div>
            <!-- <h3 class="rs title-gc">Redeem Coins</h3> -->
            <?php if (empty($event_redeem)): ?>
            <!-- <p class="rs nocode"><?php echo __('Không có sự kiện giftcode'); ?></p> -->
            <?php else: ?>

            <!-- <div class="box-code"> -->
            <?php
                // debug($event_redeem);
                // debug($hasGiftcodes);
                // die;
                foreach ($event_redeem as $key => $value) {
            ?>
                <!-- <div  class="f-boxcode"> -->
                    <!-- <span class="f-namecode rs"> -->
                        <?php //echo $value['GiftcodeEvent']['title']; ?>
                    <!-- </span> -->
                    <?php if (isset($hasGiftcodes[$value['GiftcodeEvent']['id']])): ?>
                    <!-- <a href="javascript:void(0)"  class="f-spentcoin active none-click">Đã mua</a>     -->
                    <?php else: ?>
                    <!-- <a href="javascript:void(0)"  class="f-spentcoin" data-gc="<?php echo $value['GiftcodeEvent']['id'];?>"><?php echo $value['GiftcodeEvent']['mobpoint']; ?></a> -->
                    <?php endif ?>
                    <!-- <span class="rs f-mescode"><?php echo $value['GiftcodeEvent']['description']; ?></span> -->
                    <?php if (isset($hasGiftcodes[$value['GiftcodeEvent']['id']])): ?>
                    <!-- <span class="rs f-code2"><?php echo $hasGiftcodes[$value['GiftcodeEvent']['id']]['Giftcode']['code'] ?></span> -->
                    <!-- <a href="javascript:MobAppSDKexecute('mobCopyToClipboard', {data: '<?php echo $hasGiftcodes['giftcode']['Giftcode']['code']; ?>'})" class="f-copy hide">Copy</a> -->
                    <?php else: ?>
                    <!-- <span class="rs f-code2" id="out-giftcode-<?php echo $value['GiftcodeEvent']['id'];?>"></span> -->
                    <!-- <a href="" class="f-copy hide">Copy</a> -->
                    <?php endif ?>
                <!-- </div> -->
            <?php
                }
            ?>
            <?php endif ?>
        </article>

    </div>
</section>
</body>
<script>

    $(document).ready(function(){
        $(".f-boxcode .f-namecode").on('click', function() {
            $(this).closest('.f-boxcode').toggleClass('active')
//              $(this).closest('.f-boxcode').addClass('active').siblings('.active').removeClass('active');
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

        // eraseCookie('flag_vote');
        var cookie_flag = readCookie('flag_vote');
        if(cookie_flag)
        {
            $('#code_vote').removeClass('hide');
            $('#btn_vote').removeClass('hide');
            $('#code-message').addClass('hide');
        }
        else
        {
            
            $('.f-vote').on('click', function() {
                // var state_1   = my_element.isVisible();
                var stop_flag_out = false;
                var stop_flag_in  = false;
                setTimeout(function(){ 
                    $('#loadspin').removeClass('hide');
                }, 1000);

                setTimeout(function(){ 
                    createCookie('flag_vote','1',1); 
                }, 10000);
                setTimeout(function(){ 
                    $('#code_vote').removeClass('hide');
                    $('#btn_vote').removeClass('hide');
                    $('#loadspin').addClass('hide');
                    $('#code-message').addClass('hide');
                }, 15000);
            //     function timeout() {
            //         setTimeout(function () {
                        
            //             if (stop_flag_in) 
            //             {
            //                 setTimeout(function(){ 
            //                     createCookie('flag_vote','1',1); 
            //                     $('#code_vote').removeClass('hide');
            //                     $('#btn_vote').removeClass('hide');
            //                 }, 2000);
            //                 return 0;
            //             }
            //             if(stop_flag_out)
            //             {
            //                 //do any
            //                 // clearInterval(timer); 
                            
            //                 $([window, document]).focusin(function(){
            //                   stop_flag_in = true;
            //                 }).focusout(function(){
            //                   // console.log('123345');
            //                 });
            //             } 
            //             $([window, document]).focusin(function(){
            //                 // stop_flag = true;
            //             }).focusout(function(){
            //                 stop_flag_out = true;
            //             });
            //             // if(my_element.isVisible() == false && state_1 == true)
            //             // stop_flag = true;
            //             timeout();
            //         }, 2000);
            //     }
                
            //     setTimeout(function(){ 
            //                     timeout();
            //                 }, 3000);

            //     // 
            });
        }
    });

    function createCookie(name,value,days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
        }
        else var expires = "";
        document.cookie = name+"="+value+expires+"; path=/";
    }

    function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }

    function eraseCookie(name) {
        createCookie(name,"",-1);
    }

    function myCustomFunction(){
        <?php if($result_voteapp['code'] == 1): ?>
        MobAppSDKexecute('mobCopyToClipboard', {data: '<?php echo $result_voteapp['giftcode']['GiftcodeEvent']['description']; ?>'})
        setTimeout(function() {
        MobAppSDKexecute('mobOpenBrowser', {'url' : '<?php echo 'http://'.$currentWebsite['url'].'/dl'; ?>'})
        }, 2000);
        <?php endif; ?>
    }
</script>
</html>