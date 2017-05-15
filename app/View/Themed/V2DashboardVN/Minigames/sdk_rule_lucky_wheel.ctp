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

<?php
  echo $this->Html->css('/uncommon/dashboard_v2/css/wheel.css');
  echo $this->fetch('css');
?>
<nav  class="tab-controls">
    <div class="container">
        <ul class="list-unstyled clearfix">
            <li class="active"><a href="<?php echo $url_rules; ?>"><?php echo _('Thể lệ'); ?></a></li>
            <li><a href="<?php echo $url_game; ?>"><?php echo _('Quay thưởng'); ?></a></li>
            <li><a href="<?php echo $url_history; ?>"><?php echo _('BXH'); ?></a></li>
        </ul>
    </div>
</nav>

<div class="leaderboard">
    <div class="container">
        <?php echo $minigame['Minigame']['rules']; ?>
    </div>
</div>