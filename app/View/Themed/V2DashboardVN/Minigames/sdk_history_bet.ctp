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
?>
<section id="wrapper">
   <div class="f-boxmini">
       <ul class="rs lstmini cf">
           <li><a href="<?php echo $url_rules; ?>"><?php echo _('Thể lệ'); ?></a></li>
           <li><a href="<?php echo $url_game; ?>"><?php echo _('Gom gà'); ?></a></li>
           <li><a href="<?php echo $url_history; ?>" class="active"><?php echo _('Lịch sử'); ?></a></li>
       </ul>
       <div class="f-boxLs">
           <table class="tg">
               <thead>
               <tr>
                   <th class="tg-yw4l"><?php echo __('Gom gà'); ?></th>
                   <th class="tg-yw4l"><?php echo __('Thời gian'); ?></th>
                   <th class="tg-yw4l"><?php echo __('Thắng Giải'); ?></th>
               </tr>
               </thead>
               <tbody>
               <?php
                if( !empty($minigame_rounds) ) {
                  foreach ($minigame_rounds as $item) {
                    $count_round = 1;
                    if( !empty($winners[ $item['MinigameRound']['id'] ]) ) {
                      foreach ($winners[ $item['MinigameRound']['id'] ] as $item1) {
                    ?>
                 <tr>
                     <?php
                      if($count_round == 1) {
                        ?>
                        <td class="tg-yw4l" rowspan="<?php echo count($winners[ $item['MinigameRound']['id'] ]); ?>"><?php echo $item['MinigameRound']['title']; ?></td>
                        <?php
                      }
                      ?>
                     <td class="tg-yw4l"><?php echo $item1['time']; ?></td>
                     <td class="tg-yw4l"><?php echo $item1['username']; ?></td>
                 </tr>
                 <?php
                      $count_round++;
                      }
                    }
                  }
                }
                ?>
               </tbody>
           </table>

       </div>
   </div>
</section>