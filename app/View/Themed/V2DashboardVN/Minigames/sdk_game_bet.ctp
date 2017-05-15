<?php
// xử lý cho link ko lưu vào history cho việc back

$MobileDetect = new Mobile_Detect();

$url_rules = $this->Html->url(array( 'controller' => 'minigames', 'action' => 'sdkRule', 'slug' => $minigame['Minigame']['slug'], '?'=>array('clearHistoryBackstack'=>true) ));
$url_game = $this->Html->url(array( 'controller' => 'minigames', 'action' => 'sdkGame', 'slug' => $minigame['Minigame']['slug'], '?'=>array('clearHistoryBackstack'=>true) ));
$url_history = $this->Html->url(array( 'controller' => 'minigames', 'action' => 'sdkHistory', 'slug' => $minigame['Minigame']['slug'], '?'=>array('clearHistoryBackstack'=>true) ));

if($MobileDetect->isiOS()){
  $url_rules   = "javascript:MobAppSDKexecute('mobLoadURL', {'url' : '" . $this->Html->url(array( 'controller' => 'minigames', 'action' => 'sdkRule', 'slug' => $minigame['Minigame']['slug'] )) . "'})";
  $url_game   = "javascript:MobAppSDKexecute('mobLoadURL', {'url' : '" . $this->Html->url(array( 'controller' => 'minigames', 'action' => 'sdkGame', 'slug' => $minigame['Minigame']['slug'] )) . "'})";
  $url_history   = "javascript:MobAppSDKexecute('mobLoadURL', {'url' : '" . $this->Html->url(array( 'controller' => 'minigames', 'action' => 'sdkHistory', 'slug' => $minigame['Minigame']['slug'] )) . "'})";
}

// check xem còn trong khoảng thời gian diễn ra minigame ko
$current_time = new DateTime();
$minigame_online = ( ($minigame['Minigame']['start_time'] <= $current_time->format('H:i:s') || empty($minigame['Minigame']['start_time']) )
  && ($minigame['Minigame']['end_time'] >= $current_time->format('H:i:s') || empty($minigame['Minigame']['end_time']) ) )
  ? 1 : 0;

// lấy appkey cho refresh view
$app_key = $this->request->header('mobgame_appkey');
?>
<section id="wrapper" style="background-color: #fff;">
   <div class="f-boxmini">
       <ul class="rs lstmini cf">
           <li><a href="<?php echo $url_rules; ?>"><?php echo _('Thể lệ'); ?></a></li>
           <li><a href="<?php echo $url_game; ?>" class="active"><?php echo _('Gom gà'); ?></a></li>
           <li><a href="<?php echo $url_history; ?>"><?php echo _('Lịch sử'); ?></a></li>
       </ul>
       <?php
       //if( $minigame_online ) {
       foreach($minigame_rounds as $item) {
          $funcoin_for_winner = ceil($item['MinigameRound']['funcoin_join'] * ( empty($round_count_user_joined[ $item['MinigameRound']['id'] ]) ? 0 : $round_count_user_joined[ $item['MinigameRound']['id'] ] ) * 0.6);
         ?>
         <div class="f-miniInner">
             <a href="javascript:void(0)" class="spr-mini f-icomini">
                 <span><?php echo $item['MinigameRound']['funcoin_join']; ?></span>
             </a>
             <h3 class="rs">Gom gà <?php echo $item['MinigameRound']['funcoin_join']; ?> FunCoin, cơ hội trúng <span class="f-orage"><?php echo $funcoin_for_winner < $item['MinigameRound']['prize_min'] ? $item['MinigameRound']['prize_min'] : $funcoin_for_winner; ?> FunCoin</span></h3>
             <p class="rs">Có <span class="minigame-join-count"><?php echo empty($round_count_user_joined[ $item['MinigameRound']['id'] ]) ? 0 : $round_count_user_joined[ $item['MinigameRound']['id'] ]; ?></span> người tham gia</p>
             <p class="rs">Trúng kỳ trước: <?php echo isset($winners[ $item['MinigameRound']['id'] ]) ? ($winners[ $item['MinigameRound']['id'] ]['username'] . ' - <b>' . $winners[ $item['MinigameRound']['id'] ]['prize_description'] . '</b>') : 'Chưa xác định'; ?></p>
             <div class=" cf f-minibtn">
                 <a href="#" class="f-minitime f-minitime-<?php echo $item['MinigameRound']['id']; ?>" data-countdowntime="<?php echo empty($item['MinigameRound']['countdown_time']) ? '0' : $item['MinigameRound']['countdown_time']; ?>"></a>
                 <a href="javascript:void(0);" class="f-minijoin <?php echo $round_was_joined[ $item['MinigameRound']['id'] ] ? 'active' : ''; ?>" data-id="<?php echo $item['MinigameRound']['id']; ?>"><?php echo $round_was_joined[ $item['MinigameRound']['id'] ] ? __('Đã tham gia') : __('Tham gia'); ?></a>
             </div>
         </div>
         <?php
       }
       /*} else {
        ?>
        <div style="text-align: center; color: #333;">
          <p>Gom Gà đã kết thúc, mời xem lịch sử</p>
          <a style="color: #f14307;" href="<?php echo $url_history; ?>"><?php echo _('Xem lịch sử'); ?></a>
        </div>
      <?php
      }*/
      ?>
   </div>
</section>
<div id="pop-alert" class="modalDialog cd-popup">
    <div class="innertDialog">
        <div class="contentDialog">
            <h2><?php echo __('Thông báo'); ?></h2>
            <p></p>
        </div>
        <div class="actionDialog cf">
            <a href="javascript:void(0)" title="Close" class="btn close cd-popup-close" style="width: 100%;">Đóng</a>
        </div>
    </div>
</div>
<script>
    /**
     * Hàm xử lý thời gian đếm ngược
     * @param  {int} duration       Thời gian còn lại
     * @param  {[tag]} display        Thẻ hiển thị
     * @return {[void]}                [description]
     */
    function startTimer(duration, display) {
        var timer = duration,
            minutes,
            seconds,
            minute_remain = parseInt(duration / 60, 10),
            second_remain = parseInt(duration % 60, 10),
            client_end_time = new Date(),
            client_now_time;
            client_end_time.setMinutes( client_end_time.getMinutes() + minute_remain );
            client_end_time.setSeconds( client_end_time.getSeconds() + second_remain );

        setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            client_now_time = new Date();
            client_now_time.setMinutes( client_now_time.getMinutes() + minutes );
            client_now_time.setSeconds( client_now_time.getSeconds() + seconds );

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.text(minutes + ":" + seconds);

            // thời gian đếm hết hoặc thời gian bị lệch
            if ( --timer < 0 || Math.abs(client_end_time - client_now_time) >= 10000) {
                timer = duration;
                //window.location.reload();
                window.location.href = "<?php echo $this->Html->url(array('controller'=>'Minigames','action' => 'sdkGame', 'slug' => $minigame['Minigame']['slug'], '?' => array('appkey' => $app_key) )); ?>";
            }
        }, 1000);
    }

    $(document).ready(function() {
      var time_remain = <?php echo $time_remain; ?>,
              display = $('.f-minitime');
      <?php
      foreach($minigame_rounds as $item) {
        ?>
        display = $('.f-minitime-' + <?php echo $item['MinigameRound']['id']; ?>);
        startTimer( time_remain % display.data('countdowntime'), display );
        <?php
      }
      ?>

      $( '.f-minijoin' ).click(function() {
        if( $(this).hasClass('active') ) {
          //alert('<?php echo __('Đã tham gia'); ?>');
          $('#pop-alert').find('.contentDialog').find('p').text('<?php echo __('Đã tham gia'); ?>');
          $('#pop-alert').addClass('is-visible');
          return false;
        }
        var this_e = $(this);
        this_e.text('');
        this_e.append('<span class="f-loading"></span>');
        this_e.addClass('active');

        $.ajax({
          url: '<?php echo $this->Html->url(array('controller'=>'minigames','action' => 'sdkJoinGame')); ?>',
          async: true,
          type: 'get',
          data: {
            minigame_id: <?php echo $minigame['Minigame']['id']; ?>,
            minigame_round_id: this_e.data('id'),
            slug: '<?php echo $minigame['Minigame']['slug']; ?>'
          },
          dataType: 'json',
          success:function(returnedData) {
            if(returnedData.code == 1) {
              this_e.text('<?php echo __('Đã tham gia'); ?>');
              var this_e_count = this_e.parent().parent().find('.minigame-join-count');
              this_e_count.text( parseInt(this_e_count.text()) + 1 );
            } else {
              this_e.removeClass('active');
              this_e.text('<?php echo __('Tham gia'); ?>');
              //alert(returnedData.message);
              // hiển thị thông báo
              $('#pop-alert').find('.contentDialog').find('p').text(returnedData.message);
              $('#pop-alert').addClass('is-visible');
            }
          }
        });
      });


      //close popup
      $('.cd-popup').on('click', function(event){
          if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup') ) {
              event.preventDefault();
              $(this).removeClass('is-visible');
          }
      });
    });
</script>