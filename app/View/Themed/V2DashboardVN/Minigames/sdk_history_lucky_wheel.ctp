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
            <li><a href="<?php echo $url_rules; ?>"><?php echo _('Thể lệ'); ?></a></li>
            <li><a href="<?php echo $url_game; ?>"><?php echo _('Quay thưởng'); ?></a></li>
            <li class="last active"><a href="<?php echo $url_history; ?>"><?php echo _('BXH'); ?></a></li>
        </ul>
    </div>
</nav>

<div class="leaderboard">
    <div class="container">
        <h4>Bảng xếp hạng hiện tại (<?php echo date('d-m-Y H:i:s', strtotime($last_circle_start_time)); ?> đến <?php echo date('d-m-Y H:i:s', strtotime($last_circle_end_time)); ?>)</h4>
        <?php
        if( !empty($top_10_winner) ) {
            if( !empty($top_10_winner[0]) ) {
                ?>
                <div class="prize first">
                    <h2>TOP 1</h2>
                    <p><span class="name"><?php echo $top_10_winner[0]['User']['username']; ?></span> | <span class=""><?php echo $top_10_winner[0][0]['count']; ?> lượt quay</span></p>
                </div>
                <?php
            }
            if( !empty($top_10_winner[1]) ) {
                ?>
                <div class="prize second">
                    <h2>TOP 2</h2>
                    <p><span class="name"><?php echo $top_10_winner[1]['User']['username']; ?></span> | <span class=""><?php echo $top_10_winner[1][0]['count']; ?> lượt quay</span></p>
                </div>
                <?php
            }
            if( !empty($top_10_winner[2]) ) {
                ?>
                <div class="prize third">
                    <h2>TOP 3</h2>
                    <p><span class="name"><?php echo $top_10_winner[2]['User']['username']; ?></span> | <span class=""><?php echo $top_10_winner[2][0]['count']; ?> lượt quay</span></p>
                </div>
                <?php
            }
            if( !empty($top_10_winner[3]) ) {
                ?>
                <div class="prize four">
                    <h2>TOP  4 - 10</h2>
                    <?php
                    for ($i=3; $i < count($top_10_winner); $i++) {
                        ?>
                        <p><span class="name"><?php echo $top_10_winner[$i]['User']['username']; ?></span> | <span class=""><?php echo $top_10_winner[$i][0]['count']; ?> lượt quay</span></p>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
        } else {
            ?>
            <p>Chưa có kết quả đua TOP</p>
            <?php
        }
        ?>
    </div>
    <div class="container">
        <h4>Bảng xếp hạng kỳ trước (<?php echo date('d-m-Y H:i:s', strtotime($last_circle_start_time_old_round)); ?> đến <?php echo date('d-m-Y H:i:s', strtotime($last_circle_start_time)); ?>)</h4>
        <?php
        if( !empty($top_10_winner_old_round) ) {
            if( !empty($top_10_winner_old_round[0]) ) {
                ?>
                <div class="prize first">
                    <h2>TOP 1</h2>
                    <p><span class="name"><?php echo $top_10_winner_old_round[0]['User']['username']; ?></span> | <span class=""><?php echo $top_10_winner_old_round[0][0]['count']; ?> lượt quay</span></p>
                </div>
                <?php
            }
            if( !empty($top_10_winner_old_round[1]) ) {
                ?>
                <div class="prize second">
                    <h2>TOP 2</h2>
                    <p><span class="name"><?php echo $top_10_winner_old_round[1]['User']['username']; ?></span> | <span class=""><?php echo $top_10_winner_old_round[1][0]['count']; ?> lượt quay</span></p>
                </div>
                <?php
            }
            if( !empty($top_10_winner_old_round[2]) ) {
                ?>
                <div class="prize third">
                    <h2>TOP 3</h2>
                    <p><span class="name"><?php echo $top_10_winner_old_round[2]['User']['username']; ?></span> | <span class=""><?php echo $top_10_winner_old_round[2][0]['count']; ?> lượt quay</span></p>
                </div>
                <?php
            }
            if( !empty($top_10_winner_old_round[3]) ) {
                ?>
                <div class="prize four">
                    <h2>TOP  4 - 10</h2>
                    <?php
                    for ($i=3; $i < count($top_10_winner_old_round); $i++) {
                        ?>
                        <p><span class="name"><?php echo $top_10_winner_old_round[$i]['User']['username']; ?></span> | <span class=""><?php echo $top_10_winner_old_round[$i][0]['count']; ?> lượt quay</span></p>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
        } else {
            ?>
            <p>Chưa có kết quả đua TOP</p>
            <?php
        }
        ?>
    </div>
</div>