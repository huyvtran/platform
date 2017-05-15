<?php
// lấy appkey cho refresh view
$app_key = $this->request->header('mobgame_appkey');
?>
<section id="wrapper" style="background-color: #fff;">
   <div class="f-boxmini">
       <div class="f-listmini cf">
            <div href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'mission')) ?>" class="f-btnmini f-btnmini1">
               <span class="f-icominig"></span>
               <a href="javascript:void(0)" class="f-titlemini">  <?php echo __('Nhiệm vụ FunCoin'); ?> <span class="f-icoarr spr-mini"></span></a>
               <div class="f-boxminiF">
                   <p class="rs"><?php echo __('Tổ hợp các nhiệm vụ có thể thực hiện hàng ngày, như Like Page, mời bạn bè...'); ?></p>
                   <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'mission')) ?>"><?php echo __('Tham gia'); ?></a>
               </div>
           </div>
            <?php
           foreach($minigames as $item) {
              // biến lưu trạng thái báo chưa tham gia game và có kết quả
              $minigame_notification = '';

              // xử lý thông báo game đã tham gia chưa
              if( !isset($arr_minigame_has_result[ $item['Minigame']['id'] ]) || $arr_minigame_has_result[ $item['Minigame']['id'] ]['total_view'] == 0 ) {
                $minigame_notification = '<span class="f-iconotice">!</span>';
              } else if( $arr_minigame_has_result[ $item['Minigame']['id'] ]['not_view'] > 0 ) {
                $minigame_notification = '<span class="f-iconotice">' . $arr_minigame_has_result[ $item['Minigame']['id'] ]['not_view'] . '</span>';
              }
             ?>
             <div href="<?php echo $this->Html->url(array('controller' => 'minigames', 'action' => 'sdkGame', 'slug' => $item['Minigame']['slug'])); ?>" class="f-btnmini <?php echo $item['Minigame']['icon_class']; ?>">
                 <span class="f-icominig"><?php echo $minigame_notification; ?></span>
                 <a href="javascript:void(0)" class="f-titlemini">  <?php echo $item['Minigame']['title']; ?> <span class="f-icoarr spr-mini"></span></a>
                 <div class="f-boxminiF">
                     <p class="rs"><?php echo $item['Minigame']['description']; ?></p>
                     <a href="javascript:void(0);" class="minigame-join" data-id="<?php echo $item['Minigame']['id']; ?>"><?php echo __('Tham gia'); ?></a>
                 </div>
             </div>
             <?php
           }
           ?>
       </div>
   </div>
</section>

<script>
  $(document).ready(function(){
      $(".f-btnmini .f-titlemini").on('click', function() {
          if ($(this).hasClass('active')){
              $(this).removeClass('active');
              $(this).next('.f-boxminiF').children('p').slideUp();
          } else {
              $(this).addClass('active');
              $(this).next('.f-boxminiF').children('p').slideDown();
          }

//          $(this).closest('.f-boxcode').addClass('active').siblings('.active').removeClass('active');
      });

      $( '.minigame-join' ).click(function() {
        var this_e = $(this);

        $.ajax({
          url: '<?php echo $this->Html->url(array('controller'=>'minigames','action' => 'checkTimeOfMinigame')); ?>',
          async: true,
          type: 'get',
          data: {
            minigame_id: this_e.data('id')
          },
          dataType: 'json',
          success:function(returnedData) {
            //window.location.href = returnedData.data.link;
            if(returnedData.code == 1) {
              window.location.href = returnedData.data.link;
            } else {
              alert(returnedData.message);
            }
          }
        });
      });
  });
</script>