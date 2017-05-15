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
           <li><a href="<?php echo $url_rules; ?>" class="active"><?php echo _('Thể lệ'); ?></a></li>
           <li><a href="<?php echo $url_game; ?>"><?php echo _('Gom gà'); ?></a></li>
           <li><a href="<?php echo $url_history; ?>"><?php echo _('Lịch sử'); ?></a></li>
       </ul>
       <div class="f-boxLs">
           <?php echo $minigame['Minigame']['rules']; ?>
       </div>
   </div>
</section>